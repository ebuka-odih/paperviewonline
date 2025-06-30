<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    use HasUuids;

    protected $fillable = [
        'filename',
        'original_name',
        'mime_type',
        'extension',
        'path',
        'disk',
        'size',
        'imageable_type',
        'imageable_id',
        'alt_text',
        'caption',
        'is_featured',
        'sort_order',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean',
        'size' => 'integer',
        'sort_order' => 'integer',
    ];

    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getFullPathAttribute()
    {
        return Storage::disk($this->disk)->path($this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        $pathInfo = pathinfo($this->path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
        
        if (Storage::disk($this->disk)->exists($thumbnailPath)) {
            return Storage::disk($this->disk)->url($thumbnailPath);
        }
        
        return $this->url;
    }

    public function getMediumUrlAttribute()
    {
        $pathInfo = pathinfo($this->path);
        $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['basename'];
        
        if (Storage::disk($this->disk)->exists($mediumPath)) {
            return Storage::disk($this->disk)->url($mediumPath);
        }
        
        return $this->url;
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function deleteFile()
    {
        if (Storage::disk($this->disk)->exists($this->path)) {
            Storage::disk($this->disk)->delete($this->path);
        }

        // Delete thumbnail if exists
        $pathInfo = pathinfo($this->path);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['basename'];
        if (Storage::disk($this->disk)->exists($thumbnailPath)) {
            Storage::disk($this->disk)->delete($thumbnailPath);
        }

        // Delete medium if exists
        $mediumPath = $pathInfo['dirname'] . '/medium/' . $pathInfo['basename'];
        if (Storage::disk($this->disk)->exists($mediumPath)) {
            Storage::disk($this->disk)->delete($mediumPath);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($image) {
            $image->deleteFile();
        });
    }
}
