@php
    /**
     * Shared product editor used by both create and edit.
     *
     * $product   — Product (unsaved on create)
     * $categories — active categories
     * $mode      — 'create' | 'edit'
     */
    $isEdit = $mode === 'edit';
    $old = fn ($key, $default = null) => old($key, $default);
    $variantRows = old('variants', $isEdit
        ? $product->variants->map(fn ($v) => [
            'id' => $v->id,
            'color' => $v->color,
            'color_hex' => $v->color_hex,
            'size' => $v->size,
            'sku' => $v->sku,
            'price' => $v->price,
            'sale_price' => $v->sale_price,
            'stock' => $v->stock,
            'is_active' => $v->is_active ? 1 : 0,
        ])->values()->all()
        : []);
@endphp

<form method="POST"
      action="{{ $isEdit ? route('admin.products.update', $product) : route('admin.products.store') }}"
      enctype="multipart/form-data"
      id="productForm">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <div class="row g-gs">
        {{-- ------------------------------------------------------- main column --}}
        <div class="col-lg-8">

            {{-- Details --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="mb-3">
                        <div class="pv-section-title">Details</div>
                        <div class="pv-section-hint">What shoppers see on the product page.</div>
                    </div>

                    <div class="mb-3">
                        <label for="name" class="form-label">Product name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ $old('name', $product->name) }}"
                               placeholder="e.g. A4 Matte Photo Paper" required autofocus>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="6"
                                  placeholder="Describe the material, finish, use cases…">{{ $old('description', $product->description) }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">Plain text. Line breaks are preserved on the storefront.</div>
                    </div>
                </div>
            </div>

            {{-- Media --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="mb-3 d-flex justify-content-between align-items-start gap-2">
                        <div>
                            <div class="pv-section-title">Photos</div>
                            <div class="pv-section-hint">
                                @if($isEdit)
                                    Drop files to upload straight away. Drag a photo to reorder, or click the star to make it the cover.
                                @else
                                    Drop files here — they upload when you save the product.
                                @endif
                            </div>
                        </div>
                        @if($isEdit)
                            <span class="badge bg-outline-light text-soft" id="mediaCount">
                                {{ $product->images->count() }} {{ Str::plural('photo', $product->images->count()) }}
                            </span>
                        @endif
                    </div>

                    <div class="pv-dropzone" id="mediaDropzone" tabindex="0" role="button"
                         aria-label="Add photos"
                         data-upload-url="{{ $isEdit ? route('admin.products.images.store', $product) : '' }}"
                         data-reorder-url="{{ $isEdit ? route('admin.products.images.reorder', $product) : '' }}">
                        <em class="icon ni ni-upload-cloud pv-dropzone-icon"></em>
                        <div class="pv-dropzone-title">Drag &amp; drop photos, or click to browse</div>
                        <div class="pv-dropzone-hint">JPG, PNG, GIF or WEBP &middot; up to 5&nbsp;MB each &middot; several at once</div>
                        <input type="file" id="mediaInput" name="images[]" accept="image/*" multiple hidden>
                    </div>

                    @error('images.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

                    <div class="pv-media-grid" id="mediaGrid">
                        @if($isEdit)
                            @foreach($product->images as $image)
                                <div class="pv-media-item {{ $image->is_featured ? 'is-featured' : '' }}"
                                     draggable="true" data-image-id="{{ $image->id }}">
                                    <img src="{{ $image->url }}" alt="{{ $image->alt_text }}" loading="lazy">
                                    @if($image->is_featured)<span class="pv-media-badge">Cover</span>@endif
                                    <div class="pv-media-actions">
                                        <button type="button" class="pv-media-feature" title="Make cover photo">&#9733; Cover</button>
                                        <button type="button" class="pv-media-remove" title="Remove photo">&times;</button>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <div class="pv-media-empty {{ $isEdit && $product->images->count() ? 'd-none' : '' }}" id="mediaEmpty">
                        No photos yet. The first one you add becomes the cover.
                    </div>
                </div>
            </div>

            {{-- Variants --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="mb-3">
                        <div class="pv-section-title">Colours &amp; sizes</div>
                        <div class="pv-section-hint">
                            Add the options you stock. Leave this empty if the product has only one version.
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Colours</label>
                            <div class="pv-chip-input" id="colorChips" data-kind="color">
                                <input type="text" placeholder="Type a colour, press Enter" aria-label="Add a colour">
                            </div>
                            <div class="form-text">Press Enter or comma to add. Click a swatch to set its shade.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sizes</label>
                            <div class="pv-chip-input" id="sizeChips" data-kind="size">
                                <input type="text" placeholder="Type a size, press Enter" aria-label="Add a size">
                            </div>
                            <div class="form-text">
                                Or use a preset:
                                @foreach(['A5, A4, A3, A2' => 'Paper', 'S, M, L, XL' => 'Clothing'] as $preset => $label)
                                    <button type="button" class="btn btn-link btn-sm p-0 align-baseline pv-size-preset"
                                            data-preset="{{ $preset }}">{{ $label }}</button>@if(!$loop->last),@endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm" id="generateVariants">
                            <em class="icon ni ni-grid-alt"></em><span>Generate combinations</span>
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm" id="addVariantRow">
                            <em class="icon ni ni-plus"></em><span>Add one manually</span>
                        </button>
                        <button type="button" class="btn btn-outline-light btn-sm ms-auto" id="applyStockAll">
                            Set stock for all…
                        </button>
                    </div>

                    <div class="pv-variant-table">
                        <div class="pv-variant-head" id="variantHead">
                            <div></div>
                            <div>Colour</div>
                            <div>Size</div>
                            <div>SKU</div>
                            <div>Price</div>
                            <div>Stock</div>
                            <div></div>
                        </div>
                        <div id="variantRows"></div>
                    </div>

                    <div class="pv-media-empty" id="variantEmpty">
                        No variants — this product is sold as a single item using the stock below.
                    </div>

                    <div class="alert alert-light border mt-3 mb-0 py-2 px-3 small d-none" id="variantStockNotice">
                        <em class="icon ni ni-info text-info me-1"></em>
                        Total stock is the sum of your variants (<strong id="variantStockTotal">0</strong>), so the stock field is managed for you.
                    </div>
                </div>
            </div>
        </div>

        {{-- -------------------------------------------------------- side column --}}
        <div class="col-lg-4">

            {{-- Status --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="pv-section-title mb-3">Visibility</div>

                    <input type="hidden" name="is_active" value="0">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ $old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                            <span class="d-block text-soft" style="font-size:.75rem">Visible in the shop</span>
                        </label>
                    </div>

                    <input type="hidden" name="is_featured" value="0">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1"
                               {{ $old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_featured">
                            Featured
                            <span class="d-block text-soft" style="font-size:.75rem">Highlighted on the homepage</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Organisation --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="pv-section-title mb-3">Organisation</div>

                    <div class="mb-0">
                        <label for="category_id" class="form-label">Category</label>
                        <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                            <option value="">Uncategorised</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ (string) $old('category_id', $product->category_id) === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @if($categories->isEmpty())
                            <div class="form-text">
                                No categories yet — <a href="{{ route('admin.categories.index') }}">create one</a>.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="pv-section-title mb-3">Pricing</div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ $old('price', $product->price) }}" required>
                            @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <div class="mb-0">
                        <label for="sale_price" class="form-label">Sale price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0"
                                   class="form-control @error('sale_price') is-invalid @enderror"
                                   id="sale_price" name="sale_price" value="{{ $old('sale_price', $product->sale_price) }}">
                            @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-text" id="discountHint">Leave empty for no discount.</div>
                    </div>
                </div>
            </div>

            {{-- Inventory --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="pv-section-title mb-3">Inventory</div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock <span class="text-danger">*</span></label>
                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror"
                               id="stock" name="stock" value="{{ $old('stock', $product->stock ?? 0) }}" required>
                        @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku" value="{{ $old('sku', $product->sku) }}"
                               placeholder="Generated automatically">
                        @error('sku')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="barcode" class="form-label">Barcode</label>
                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                               id="barcode" name="barcode" value="{{ $old('barcode', $product->barcode) }}">
                        @error('barcode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="card card-bordered mb-3">
                <div class="card-inner">
                    <div class="pv-section-title mb-3">Shipping</div>

                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" class="form-control @error('weight') is-invalid @enderror"
                               id="weight" name="weight" value="{{ $old('weight', $product->weight) }}">
                        @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-0">
                        <label for="dimensions" class="form-label">Dimensions</label>
                        <input type="text" class="form-control @error('dimensions') is-invalid @enderror"
                               id="dimensions" name="dimensions" value="{{ $old('dimensions', $product->dimensions) }}"
                               placeholder="e.g. 30 × 21 × 2 cm">
                        @error('dimensions')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-bordered mt-3">
        <div class="pv-sticky-actions">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-light me-auto">Cancel</a>
            @if($isEdit)
                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProductModal">
                    <em class="icon ni ni-trash"></em><span>Delete</span>
                </button>
            @endif
            <button type="submit" class="btn btn-primary">
                <em class="icon ni ni-save"></em><span>{{ $isEdit ? 'Save changes' : 'Create product' }}</span>
            </button>
        </div>
    </div>
</form>

@if($isEdit)
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Delete product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            Delete <strong>{{ $product->name }}</strong>? Its photos and
                            {{ $product->variants->count() }} {{ Str::plural('variant', $product->variants->count()) }}
                            will be removed too. This cannot be undone.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
(function () {
    'use strict';

    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var MAX_BYTES = 5 * 1024 * 1024;
    var ACCEPTED = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    function toast(message, type) {
        var stack = document.getElementById('pvToastStack');
        if (!stack) return alert(message);
        var el = document.createElement('div');
        el.className = 'pv-toast pv-toast-' + (type || 'info');
        el.innerHTML = '<em class="icon ni ni-' + (type === 'error' ? 'cross-circle' : 'check-circle') + '"></em>' +
                       '<span></span><button type="button" class="pv-toast-close">&times;</button>';
        el.querySelector('span').textContent = message;
        el.querySelector('.pv-toast-close').addEventListener('click', function () { el.remove(); });
        stack.appendChild(el);
        setTimeout(function () { el.remove(); }, 5000);
    }

    /* =====================================================================
       Media uploader
       On edit the files POST immediately; on create they are staged in the
       file input and travel with the form.
       ===================================================================== */
    var dropzone = document.getElementById('mediaDropzone');
    var input = document.getElementById('mediaInput');
    var grid = document.getElementById('mediaGrid');
    var empty = document.getElementById('mediaEmpty');
    var counter = document.getElementById('mediaCount');
    var uploadUrl = dropzone.dataset.uploadUrl;
    var reorderUrl = dropzone.dataset.reorderUrl;
    var staged = [];   // create mode only

    function refreshEmptyState() {
        var has = grid.children.length > 0;
        empty.classList.toggle('d-none', has);
        if (counter) {
            var n = grid.querySelectorAll('.pv-media-item').length;
            counter.textContent = n + ' ' + (n === 1 ? 'photo' : 'photos');
        }
    }

    function validate(file) {
        if (ACCEPTED.indexOf(file.type) === -1) {
            toast('"' + file.name + '" is not a supported image (use JPG, PNG, GIF or WEBP).', 'error');
            return false;
        }
        if (file.size > MAX_BYTES) {
            toast('"' + file.name + '" is larger than 5 MB.', 'error');
            return false;
        }
        return true;
    }

    function buildTile(options) {
        var item = document.createElement('div');
        item.className = 'pv-media-item' + (options.featured ? ' is-featured' : '');
        item.draggable = !!options.id;
        if (options.id) item.dataset.imageId = options.id;
        item.innerHTML =
            '<img alt="">' +
            (options.featured ? '<span class="pv-media-badge">Cover</span>' : '') +
            '<div class="pv-media-actions">' +
                (options.id ? '<button type="button" class="pv-media-feature" title="Make cover photo">&#9733; Cover</button>' : '') +
                '<button type="button" class="pv-media-remove" title="Remove photo">&times;</button>' +
            '</div>';
        item.querySelector('img').src = options.url;
        return item;
    }

    function markFeatured(item) {
        grid.querySelectorAll('.pv-media-item').forEach(function (el) {
            el.classList.remove('is-featured');
            var badge = el.querySelector('.pv-media-badge');
            if (badge) badge.remove();
        });
        item.classList.add('is-featured');
        var badge = document.createElement('span');
        badge.className = 'pv-media-badge';
        badge.textContent = 'Cover';
        item.prepend(badge);
    }

    /* ---- create mode: keep a staged list and rebuild the input's FileList -- */
    function syncStagedInput() {
        var transfer = new DataTransfer();
        staged.forEach(function (file) { transfer.items.add(file); });
        input.files = transfer.files;
    }

    function stageFiles(files) {
        Array.prototype.forEach.call(files, function (file) {
            if (!validate(file)) return;
            staged.push(file);
            var reader = new FileReader();
            reader.onload = function (e) {
                var tile = buildTile({ url: e.target.result, featured: staged.length === 1 });
                tile.dataset.stagedName = file.name;
                tile.dataset.stagedSize = file.size;
                grid.appendChild(tile);
                refreshEmptyState();
            };
            reader.readAsDataURL(file);
        });
        syncStagedInput();
    }

    /* ---- edit mode: upload straight away ---------------------------------- */
    function uploadFiles(files) {
        var valid = Array.prototype.filter.call(files, validate);
        if (!valid.length) return;

        var placeholder = document.createElement('div');
        placeholder.className = 'pv-media-item';
        placeholder.innerHTML = '<div class="pv-media-progress">Uploading ' + valid.length + '…</div>';
        grid.appendChild(placeholder);
        refreshEmptyState();

        var body = new FormData();
        valid.forEach(function (file) { body.append('images[]', file); });

        fetch(uploadUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            body: body
        })
            .then(function (response) {
                return response.json().then(function (data) {
                    if (!response.ok) throw new Error(data.message || 'Upload failed.');
                    return data;
                });
            })
            .then(function (data) {
                placeholder.remove();
                data.images.forEach(function (image) {
                    grid.appendChild(buildTile({ id: image.id, url: image.url, featured: image.is_featured }));
                });
                refreshEmptyState();
                toast(data.images.length + ' ' + (data.images.length === 1 ? 'photo' : 'photos') + ' uploaded.', 'success');
            })
            .catch(function (error) {
                placeholder.remove();
                refreshEmptyState();
                toast(error.message || 'Upload failed. Please try again.', 'error');
            });
    }

    function handleFiles(files) {
        if (!files || !files.length) return;
        if (uploadUrl) { uploadFiles(files); } else { stageFiles(files); }
        input.value = uploadUrl ? '' : input.value;
    }

    dropzone.addEventListener('click', function () { input.click(); });
    dropzone.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); input.click(); }
    });
    ['dragenter', 'dragover'].forEach(function (type) {
        dropzone.addEventListener(type, function (event) {
            event.preventDefault();
            dropzone.classList.add('is-dragging');
        });
    });
    ['dragleave', 'drop'].forEach(function (type) {
        dropzone.addEventListener(type, function (event) {
            event.preventDefault();
            dropzone.classList.remove('is-dragging');
        });
    });
    dropzone.addEventListener('drop', function (event) { handleFiles(event.dataTransfer.files); });
    input.addEventListener('change', function () {
        // In create mode the input is the payload, so only stage the new picks.
        if (uploadUrl) { handleFiles(input.files); return; }
        var picked = Array.prototype.slice.call(input.files);
        input.value = '';
        syncStagedInput();
        stageFiles(picked);
    });

    grid.addEventListener('click', function (event) {
        var item = event.target.closest('.pv-media-item');
        if (!item) return;

        if (event.target.closest('.pv-media-remove')) {
            var id = item.dataset.imageId;
            if (!confirm('Remove this photo?')) return;

            if (!id) {
                staged = staged.filter(function (file) {
                    return !(file.name === item.dataset.stagedName && String(file.size) === item.dataset.stagedSize);
                });
                syncStagedInput();
                item.remove();
                refreshEmptyState();
                return;
            }

            fetch('{{ url('admin/products/images') }}/' + id, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
                .then(function (response) { if (!response.ok) throw new Error(); return response.json(); })
                .then(function () {
                    var wasFeatured = item.classList.contains('is-featured');
                    item.remove();
                    var next = grid.querySelector('.pv-media-item');
                    if (wasFeatured && next) markFeatured(next);
                    refreshEmptyState();
                    toast('Photo removed.', 'success');
                })
                .catch(function () { toast('Could not remove the photo.', 'error'); });
            return;
        }

        if (event.target.closest('.pv-media-feature') && item.dataset.imageId) {
            fetch('{{ url('admin/products/images') }}/' + item.dataset.imageId + '/featured', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }
            })
                .then(function (response) { if (!response.ok) throw new Error(); return response.json(); })
                .then(function () { markFeatured(item); toast('Cover photo updated.', 'success'); })
                .catch(function () { toast('Could not set the cover photo.', 'error'); });
        }
    });

    /* ---- drag to reorder (edit mode) -------------------------------------- */
    var dragged = null;

    grid.addEventListener('dragstart', function (event) {
        var item = event.target.closest('.pv-media-item');
        if (!item || !item.dataset.imageId) return;
        dragged = item;
        item.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
    });

    grid.addEventListener('dragover', function (event) {
        if (!dragged) return;
        event.preventDefault();
        var target = event.target.closest('.pv-media-item');
        if (!target || target === dragged) return;
        var after = event.clientX > target.getBoundingClientRect().left + target.offsetWidth / 2;
        grid.insertBefore(dragged, after ? target.nextSibling : target);
    });

    grid.addEventListener('dragend', function () {
        if (!dragged) return;
        dragged.classList.remove('is-dragging');
        dragged = null;

        var ids = Array.prototype.map.call(grid.querySelectorAll('.pv-media-item[data-image-id]'), function (el) {
            return el.dataset.imageId;
        });
        if (!reorderUrl || !ids.length) return;

        fetch(reorderUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify({ image_ids: ids })
        }).catch(function () { toast('Could not save the new photo order.', 'error'); });
    });

    refreshEmptyState();

    /* =====================================================================
       Variant builder
       ===================================================================== */
    var rowsHost = document.getElementById('variantRows');
    var variantEmpty = document.getElementById('variantEmpty');
    var stockNotice = document.getElementById('variantStockNotice');
    var stockTotalEl = document.getElementById('variantStockTotal');
    var stockField = document.getElementById('stock');
    var rowIndex = 0;

    var DEFAULT_SWATCHES = {
        red: '#e85347', blue: '#2263b3', green: '#1ee0ac', black: '#1c2b46', white: '#ffffff',
        grey: '#8094ae', gray: '#8094ae', yellow: '#f4bd0e', orange: '#ff9f43', pink: '#f2426d',
        purple: '#816bff', brown: '#8a5a44', navy: '#1f3c88', beige: '#e8dccb', cream: '#fdf6e3'
    };

    function swatchFor(name) {
        return DEFAULT_SWATCHES[String(name).trim().toLowerCase()] || '#dbdfea';
    }

    function chipStore(host) {
        return Array.prototype.map.call(host.querySelectorAll('.pv-chip'), function (chip) {
            return { value: chip.dataset.value, hex: chip.dataset.hex || null };
        });
    }

    function addChip(host, value, hex) {
        value = String(value).trim();
        if (!value) return;
        var exists = chipStore(host).some(function (c) {
            return c.value.toLowerCase() === value.toLowerCase();
        });
        if (exists) return;

        var isColor = host.dataset.kind === 'color';
        var chip = document.createElement('span');
        chip.className = 'pv-chip';
        chip.dataset.value = value;
        if (isColor) chip.dataset.hex = hex || swatchFor(value);
        chip.innerHTML =
            (isColor ? '<input type="color" class="pv-chip-dot" aria-label="Shade for ' + value + '">' : '') +
            '<span class="pv-chip-label"></span>' +
            '<button type="button" aria-label="Remove">&times;</button>';
        chip.querySelector('.pv-chip-label').textContent = value;

        if (isColor) {
            var picker = chip.querySelector('input[type="color"]');
            picker.value = chip.dataset.hex;
            picker.addEventListener('input', function () {
                chip.dataset.hex = picker.value;
                rowsHost.querySelectorAll('.pv-variant-row').forEach(function (row) {
                    var colorInput = row.querySelector('[data-field="color"]');
                    var hexInput = row.querySelector('[data-field="color_hex"]');
                    if (colorInput && hexInput && colorInput.value.toLowerCase() === value.toLowerCase()) {
                        hexInput.value = picker.value;
                        row.querySelector('.pv-swatch').value = picker.value;
                    }
                });
            });
        }

        chip.querySelector('button').addEventListener('click', function () { chip.remove(); });
        host.insertBefore(chip, host.querySelector('input[type="text"]'));
    }

    function wireChipInput(host) {
        var field = host.querySelector('input[type="text"]');

        host.addEventListener('click', function (event) {
            if (event.target === host) field.focus();
        });

        field.addEventListener('keydown', function (event) {
            if (event.key === 'Enter' || event.key === ',') {
                event.preventDefault();
                field.value.split(',').forEach(function (part) { addChip(host, part); });
                field.value = '';
            } else if (event.key === 'Backspace' && !field.value) {
                var chips = host.querySelectorAll('.pv-chip');
                if (chips.length) chips[chips.length - 1].remove();
            }
        });

        field.addEventListener('blur', function () {
            field.value.split(',').forEach(function (part) { addChip(host, part); });
            field.value = '';
        });
    }

    var colorHost = document.getElementById('colorChips');
    var sizeHost = document.getElementById('sizeChips');
    wireChipInput(colorHost);
    wireChipInput(sizeHost);

    document.querySelectorAll('.pv-size-preset').forEach(function (button) {
        button.addEventListener('click', function () {
            button.dataset.preset.split(',').forEach(function (size) { addChip(sizeHost, size); });
        });
    });

    function pruneUnusedChip(host, field, value) {
        if (!value) return;
        var stillUsed = Array.prototype.some.call(rowsHost.querySelectorAll('.pv-variant-row'), function (row) {
            return row.querySelector('[data-field="' + field + '"]').value.trim().toLowerCase() === value.toLowerCase();
        });
        if (stillUsed) return;

        Array.prototype.forEach.call(host.querySelectorAll('.pv-chip'), function (chip) {
            if (chip.dataset.value.toLowerCase() === value.toLowerCase()) chip.remove();
        });
    }

    function addVariantRow(data) {
        data = data || {};
        var i = rowIndex++;
        var row = document.createElement('div');
        row.className = 'pv-variant-row';
        row.innerHTML =
            '<div class="form-check form-switch m-0">' +
                '<input type="hidden" name="variants[' + i + '][is_active]" value="0">' +
                '<input class="form-check-input" type="checkbox" name="variants[' + i + '][is_active]" value="1" data-field="is_active" title="Available for sale">' +
            '</div>' +
            '<div class="d-flex gap-1 align-items-center">' +
                '<input type="color" class="pv-swatch" name="variants[' + i + '][color_hex]" data-field="color_hex">' +
                '<input type="text" class="form-control form-control-sm" name="variants[' + i + '][color]" data-field="color" placeholder="Colour">' +
            '</div>' +
            '<input type="text" class="form-control form-control-sm" name="variants[' + i + '][size]" data-field="size" placeholder="Size" aria-label="Size">' +
            '<input type="text" class="form-control form-control-sm" name="variants[' + i + '][sku]" data-field="sku" placeholder="SKU (auto)" aria-label="SKU">' +
            '<input type="number" step="0.01" min="0" class="form-control form-control-sm" name="variants[' + i + '][price]" data-field="price" placeholder="Price (default)" aria-label="Price override">' +
            '<input type="number" min="0" class="form-control form-control-sm" name="variants[' + i + '][stock]" data-field="stock" value="0" placeholder="Stock" aria-label="Stock">' +
            '<button type="button" class="pv-variant-remove" aria-label="Remove variant">&times;</button>' +
            '<input type="hidden" name="variants[' + i + '][id]" data-field="id">' +
            '<input type="hidden" name="variants[' + i + '][sale_price]" data-field="sale_price">';

        var set = function (field, value) {
            var el = row.querySelector('[data-field="' + field + '"]');
            if (el && value !== null && value !== undefined) el.value = value;
        };
        set('id', data.id || '');
        set('color', data.color || '');
        set('color_hex', data.color_hex || swatchFor(data.color || ''));
        set('size', data.size || '');
        set('sku', data.sku || '');
        set('price', data.price === null || data.price === undefined ? '' : data.price);
        set('sale_price', data.sale_price === null || data.sale_price === undefined ? '' : data.sale_price);
        set('stock', data.stock === undefined ? 0 : data.stock);

        var active = row.querySelector('[data-field="is_active"]');
        active.checked = data.is_active === undefined ? true : !!Number(data.is_active);
        row.classList.toggle('is-inactive', !active.checked);
        active.addEventListener('change', function () {
            row.classList.toggle('is-inactive', !active.checked);
        });

        row.querySelector('.pv-variant-remove').addEventListener('click', function () {
            var color = row.querySelector('[data-field="color"]').value.trim();
            var size = row.querySelector('[data-field="size"]').value.trim();
            row.remove();
            // Drop a chip once no row uses it, so "Generate combinations"
            // doesn't quietly bring the variant the admin just deleted back.
            pruneUnusedChip(colorHost, 'color', color);
            pruneUnusedChip(sizeHost, 'size', size);
            refreshVariantState();
        });
        row.querySelector('[data-field="stock"]').addEventListener('input', refreshVariantState);

        rowsHost.appendChild(row);
        return row;
    }

    function currentCombos() {
        var seen = {};
        rowsHost.querySelectorAll('.pv-variant-row').forEach(function (row) {
            var key = row.querySelector('[data-field="color"]').value.trim().toLowerCase() + '|' +
                      row.querySelector('[data-field="size"]').value.trim().toLowerCase();
            seen[key] = true;
        });
        return seen;
    }

    function refreshVariantState() {
        var rows = rowsHost.querySelectorAll('.pv-variant-row');
        var has = rows.length > 0;
        variantEmpty.classList.toggle('d-none', has);
        stockNotice.classList.toggle('d-none', !has);

        var total = 0;
        rows.forEach(function (row) {
            total += parseInt(row.querySelector('[data-field="stock"]').value, 10) || 0;
        });
        stockTotalEl.textContent = total;

        if (has) {
            stockField.value = total;
            stockField.readOnly = true;
            stockField.classList.add('bg-light');
        } else {
            stockField.readOnly = false;
            stockField.classList.remove('bg-light');
        }
    }

    document.getElementById('generateVariants').addEventListener('click', function () {
        var colors = chipStore(colorHost);
        var sizes = chipStore(sizeHost);

        if (!colors.length && !sizes.length) {
            toast('Add at least one colour or size first.', 'error');
            return;
        }

        var existing = currentCombos();
        var combos = [];

        if (colors.length && sizes.length) {
            colors.forEach(function (color) {
                sizes.forEach(function (size) {
                    combos.push({ color: color.value, color_hex: color.hex, size: size.value });
                });
            });
        } else if (colors.length) {
            colors.forEach(function (color) { combos.push({ color: color.value, color_hex: color.hex, size: '' }); });
        } else {
            sizes.forEach(function (size) { combos.push({ color: '', color_hex: '', size: size.value }); });
        }

        var added = 0;
        combos.forEach(function (combo) {
            var key = combo.color.toLowerCase() + '|' + combo.size.toLowerCase();
            if (existing[key]) return;
            addVariantRow({ color: combo.color, color_hex: combo.color_hex, size: combo.size, stock: 0, is_active: 1 });
            added++;
        });

        refreshVariantState();
        toast(added
            ? added + ' ' + (added === 1 ? 'variant' : 'variants') + ' added. Set the stock for each.'
            : 'Those combinations already exist.', added ? 'success' : 'info');
    });

    document.getElementById('addVariantRow').addEventListener('click', function () {
        addVariantRow({ stock: 0, is_active: 1 });
        refreshVariantState();
    });

    document.getElementById('applyStockAll').addEventListener('click', function () {
        var rows = rowsHost.querySelectorAll('.pv-variant-row');
        if (!rows.length) { toast('There are no variants yet.', 'error'); return; }
        var value = prompt('Set the stock quantity for all ' + rows.length + ' variants:', '10');
        if (value === null) return;
        var qty = parseInt(value, 10);
        if (isNaN(qty) || qty < 0) { toast('Enter a whole number of 0 or more.', 'error'); return; }
        rows.forEach(function (row) { row.querySelector('[data-field="stock"]').value = qty; });
        refreshVariantState();
    });

    // Seed existing / old-input variants, and prefill the chip inputs from them.
    @json($variantRows).forEach(function (variant) {
        addVariantRow(variant);
        if (variant.color) addChip(colorHost, variant.color, variant.color_hex);
        if (variant.size) addChip(sizeHost, variant.size);
    });
    refreshVariantState();

    /* ---- live discount hint ----------------------------------------------- */
    var priceField = document.getElementById('price');
    var saleField = document.getElementById('sale_price');
    var hint = document.getElementById('discountHint');

    function updateDiscount() {
        var price = parseFloat(priceField.value);
        var sale = parseFloat(saleField.value);
        if (!isNaN(price) && !isNaN(sale) && sale > 0) {
            if (sale >= price) {
                hint.textContent = 'This is not a discount — it is at or above the regular price.';
                hint.className = 'form-text text-danger';
            } else {
                hint.textContent = Math.round(((price - sale) / price) * 100) + '% off — customers pay $' + sale.toFixed(2) + '.';
                hint.className = 'form-text text-success';
            }
        } else {
            hint.textContent = 'Leave empty for no discount.';
            hint.className = 'form-text';
        }
    }
    priceField.addEventListener('input', updateDiscount);
    saleField.addEventListener('input', updateDiscount);
    updateDiscount();
})();
</script>
@endpush
