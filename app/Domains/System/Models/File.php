<?php

namespace App\Domains\System\Models;

use App\Domains\System\Casts\ByteHumanReadable;
use Database\Factories\System\FileFactory;
use Exception;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Storage;

#[Fillable(['fileable_id', 'fileable_type', 'relation_name', 'name', 'path', 'size', 'disk', 'mime_type'])]
class File extends Model
{
    use HasFactory;

    protected $casts = [
        'size' => ByteHumanReadable::class,
    ];

    protected static function newFactory(): Factory
    {
        return FileFactory::new();
    }

    protected static function booted(): void
    {
        // Whenever this model is deleted from the DB, delete the physical file
        static::deleted(function (File $file) {
            if ($file->path) {
                // Ensure the disk matches where you saved it (e.g., 'public' or 's3')
                Storage::disk($file->disk)->delete($file->path);
            }
        });
    }

    /**
     * @throws Exception
     */
    public function getUrlAttribute(): string
    {
        return asset_static($this->path);
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
