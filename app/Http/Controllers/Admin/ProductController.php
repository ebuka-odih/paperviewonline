<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Services\ImageService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->query('search', '')),
            'category' => $request->query('category', ''),
            'status' => $request->query('status', ''),
            'stock' => $request->query('stock', ''),
            'sort' => $request->query('sort', 'newest'),
        ];

        $products = Product::query()
            ->with(['category', 'images', 'variants'])
            ->withCount('variants')
            ->when($filters['search'], function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->when($filters['category'], fn ($query, $category) => $query->where('category_id', $category))
            ->when($filters['status'] !== '', function ($query) use ($filters) {
                if ($filters['status'] === 'active') {
                    $query->where('is_active', true);
                } elseif ($filters['status'] === 'inactive') {
                    $query->where('is_active', false);
                } elseif ($filters['status'] === 'featured') {
                    $query->where('is_featured', true);
                }
            })
            ->when($filters['stock'] !== '', function ($query) use ($filters) {
                if ($filters['stock'] === 'out') {
                    $query->where('stock', '<=', 0);
                } elseif ($filters['stock'] === 'low') {
                    $query->whereBetween('stock', [1, 10]);
                } elseif ($filters['stock'] === 'in') {
                    $query->where('stock', '>', 10);
                }
            })
            ->when(true, fn ($query) => match ($filters['sort']) {
                'oldest' => $query->orderBy('created_at'),
                'name' => $query->orderBy('name'),
                'price_high' => $query->orderByDesc('price'),
                'price_low' => $query->orderBy('price'),
                'stock_low' => $query->orderBy('stock'),
                default => $query->orderByDesc('created_at'),
            })
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'low_stock' => Product::whereBetween('stock', [1, 10])->count(),
            'out_of_stock' => Product::where('stock', '<=', 0)->count(),
        ];

        return view('admin.product.index', compact('products', 'categories', 'filters', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product = new Product(['is_active' => true, 'stock' => 0]);

        return view('admin.product.create', compact('product', 'categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);

        $product = Product::create($this->productAttributes($data));

        if ($request->hasFile('images')) {
            $this->imageService->storeMultipleImages(
                $request->file('images'),
                $product,
                ['alt_text' => $product->name]
            );
        }

        if ($failure = $this->trySyncVariants($product, $request->input('variants', []))) {
            return $failure;
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', "\"{$product->name}\" was created. You can keep editing it below.");
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $product->load(['images', 'variants']);

        return view('admin.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request, $product);

        $product->update($this->productAttributes($data, $product));

        if ($request->hasFile('images')) {
            $this->imageService->storeMultipleImages(
                $request->file('images'),
                $product,
                ['alt_text' => $product->name]
            );
        }

        if ($failure = $this->trySyncVariants($product, $request->input('variants', []))) {
            return $failure;
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;

        // Variant images are not cascaded by the database, so clear them first.
        foreach ($product->variants as $variant) {
            $this->imageService->deleteImagesByImageable($variant);
        }

        $this->imageService->deleteImagesByImageable($product);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', "\"{$name}\" was deleted.");
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return back()->with('success', "\"{$product->name}\" is now " . ($product->is_active ? 'active' : 'inactive') . '.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => ! $product->is_featured]);

        return back()->with('success', "\"{$product->name}\" is " . ($product->is_featured ? 'now featured' : 'no longer featured') . '.');
    }

    /**
     * Async upload used by the media panel on the product editor.
     */
    public function storeImages(Request $request, Product $product)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'variant_id' => ['nullable', Rule::exists('product_variants', 'id')->where('product_id', $product->id)],
        ]);

        $target = $request->variant_id
            ? $product->variants()->findOrFail($request->variant_id)
            : $product;

        $startingOrder = (int) $target->images()->max('sort_order') + 1;

        $images = collect($request->file('images'))->map(function ($file, $index) use ($target, $startingOrder, $product) {
            return $this->imageService->storeImage($file, $target, [
                'alt_text' => $product->name,
                'sort_order' => $startingOrder + $index,
                'is_featured' => false,
            ]);
        });

        // Guarantee the target always has exactly one featured image.
        if (! $target->images()->where('is_featured', true)->exists() && $images->isNotEmpty()) {
            $this->imageService->setFeaturedImage($target, $images->first()->id);
        }

        return response()->json([
            'success' => true,
            'images' => $images->map(fn ($image) => [
                'id' => $image->id,
                'url' => $image->url,
                'name' => $image->original_name,
                'size' => $image->formatted_size,
                'is_featured' => (bool) $image->fresh()->is_featured,
            ])->values(),
        ]);
    }

    public function deleteImage(Image $image)
    {
        $wasFeatured = $image->is_featured;
        $owner = $image->imageable;

        $this->imageService->deleteImage($image);

        // Promote the next image so a product is never left without a cover.
        if ($wasFeatured && $owner) {
            $next = $owner->images()->orderBy('sort_order')->first();

            if ($next) {
                $next->update(['is_featured' => true]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Image deleted.']);
    }

    public function setFeaturedImage(Image $image)
    {
        $owner = $image->imageable;

        abort_if(! $owner, 404);

        $this->imageService->setFeaturedImage($owner, $image->id);

        return response()->json(['success' => true]);
    }

    public function reorderImages(Request $request, Product $product)
    {
        $request->validate([
            'image_ids' => 'required|array',
            'image_ids.*' => 'exists:images,id',
            'variant_id' => ['nullable', Rule::exists('product_variants', 'id')->where('product_id', $product->id)],
        ]);

        $target = $request->variant_id
            ? $product->variants()->findOrFail($request->variant_id)
            : $product;

        $this->imageService->reorderImages($target, $request->image_ids);

        return response()->json(['success' => true]);
    }

    protected function validateProduct(Request $request, ?Product $product = null): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => ['nullable', 'string', 'max:255', Rule::unique('products', 'sku')->ignore($product?->id)],
            'barcode' => ['nullable', 'string', 'max:255', Rule::unique('products', 'barcode')->ignore($product?->id)],
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|string',
            'variants.*.color' => 'nullable|string|max:100',
            'variants.*.color_hex' => 'nullable|string|max:7',
            'variants.*.size' => 'nullable|string|max:100',
            'variants.*.sku' => 'nullable|string|max:255',
            'variants.*.price' => 'nullable|numeric|min:0',
            'variants.*.sale_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
            'variants.*.is_active' => 'nullable|boolean',
        ], [
            'sale_price.lt' => 'The sale price must be lower than the regular price.',
            'images.*.max' => 'Each image must be 5 MB or smaller.',
            'images.*.image' => 'Only image files (JPG, PNG, GIF, WEBP) can be uploaded.',
        ]);
    }

    protected function productAttributes(array $data, ?Product $product = null): array
    {
        $attributes = collect($data)
            ->except(['images', 'variants'])
            ->all();

        $attributes['slug'] = $this->uniqueSlug($data['name'], $product);
        $attributes['is_active'] = (bool) ($data['is_active'] ?? false);
        $attributes['is_featured'] = (bool) ($data['is_featured'] ?? false);
        $attributes['sku'] = $attributes['sku'] ?: ($product->sku ?? null);

        return $attributes;
    }

    protected function uniqueSlug(string $name, ?Product $product = null): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $suffix = 2;

        while (Product::where('slug', $slug)->when($product, fn ($q) => $q->where('id', '!=', $product->id))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * Run the variant sync, turning a duplicate-combination collision into a
     * readable message instead of a 500. Returns a redirect on failure, null on
     * success.
     */
    protected function trySyncVariants(Product $product, array $rows)
    {
        try {
            $this->syncVariants($product, $rows);
        } catch (UniqueConstraintViolationException $exception) {
            return redirect()->route('admin.products.edit', $product)
                ->with('error', 'The product was saved, but the variants were not: two rows use the same colour and size. Give each variant a unique combination and try again.');
        }

        return null;
    }

    /**
     * Create, update and prune variants from the editor's variant table.
     *
     * Rows with neither a colour nor a size are ignored. Rows are matched to
     * existing variants by id first and then by colour/size, and stale variants
     * are deleted *before* the writes — otherwise a combination that the admin
     * removed and re-added in the same save would collide with the row still in
     * the table on the (product, colour, size) unique index.
     */
    protected function syncVariants(Product $product, array $rows): void
    {
        $normalised = [];
        $seenCombos = [];

        foreach (array_values($rows) as $index => $row) {
            $color = trim((string) ($row['color'] ?? '')) ?: null;
            $size = trim((string) ($row['size'] ?? '')) ?: null;

            if (! $color && ! $size) {
                continue;
            }

            // The unique index is case sensitive but admins are not; treat
            // "Red / A4" and "red / a4" as the same variant and keep the first.
            $combo = Str::lower($color . '|' . $size);

            if (isset($seenCombos[$combo])) {
                continue;
            }
            $seenCombos[$combo] = true;

            $normalised[] = [
                'id' => $row['id'] ?? null,
                'combo' => $combo,
                'attributes' => array_filter([
                    'sku' => $row['sku'] ?? null,
                ]) + [
                    'color' => $color,
                    'color_hex' => $row['color_hex'] ?? null,
                    'size' => $size,
                    'price' => ($row['price'] ?? '') === '' ? null : $row['price'],
                    'sale_price' => ($row['sale_price'] ?? '') === '' ? null : $row['sale_price'],
                    'stock' => (int) ($row['stock'] ?? 0),
                    'is_active' => (bool) ($row['is_active'] ?? false),
                    'sort_order' => $index,
                ],
            ];
        }

        DB::transaction(function () use ($product, $normalised) {
            $existing = $product->variants()->get();
            $submittedIds = collect($normalised)->pluck('id')->filter()->all();

            // Drop everything the admin removed before writing, so freed
            // colour/size combinations can be reused in the same request.
            foreach ($existing->whereNotIn('id', $submittedIds) as $variant) {
                $this->imageService->deleteImagesByImageable($variant);
                $variant->delete();
            }

            $remaining = $existing->whereIn('id', $submittedIds);
            $byId = $remaining->keyBy('id');
            $byCombo = $remaining->keyBy(fn ($variant) => Str::lower($variant->color . '|' . $variant->size));
            $consumed = [];

            foreach ($normalised as $row) {
                $variant = $row['id'] ? $byId->get($row['id']) : null;

                // Fall back to matching on colour/size, but never reuse a
                // variant another row already claimed by id.
                if (! $variant) {
                    $candidate = $byCombo->get($row['combo']);
                    $variant = $candidate && ! isset($consumed[$candidate->id]) ? $candidate : null;
                }

                if ($variant) {
                    $consumed[$variant->id] = true;
                    $variant->update($row['attributes']);
                } else {
                    $product->variants()->create($row['attributes']);
                }
            }
        });

        $product->refresh()->syncStockFromVariants();
    }
}
