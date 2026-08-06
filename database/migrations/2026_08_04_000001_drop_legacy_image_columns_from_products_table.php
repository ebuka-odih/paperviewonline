<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * `products.images` and `products.featured_image` predate the polymorphic
 * `images` table and are no longer written to. Worse, the `images` column
 * shadowed the `images()` relation — `$product->images` returned the (null)
 * column instead of the related models, which silently broke image cleanup on
 * product deletion. Dropping them lets the relation resolve normally.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'images')) {
                $table->dropColumn('images');
            }
            if (Schema::hasColumn('products', 'featured_image')) {
                $table->dropColumn('featured_image');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('images')->nullable();
            $table->string('featured_image')->nullable();
        });
    }
};
