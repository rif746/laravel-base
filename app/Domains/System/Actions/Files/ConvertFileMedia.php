<?php

namespace App\Domains\System\Actions\Files;

use App\UI\Enums\FileType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConvertFileMedia
{
    /**
     * Execute media file conversion and compression based on MIME classification.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return array{0: string, 1: int, 2: string} Returns array of [storedPath, fileSize, mimeType]
     */
    public function execute(UploadedFile $file, string $directory, string $disk): array
    {
        $mimeType = $file->getMimeType();

        // 1. Image Conversion Routing (Raster images to WebP)
        if (FileType::IMAGE->supportsWebpConversion($mimeType)) {
            return $this->convertImageToWebp($file, $directory, $disk);
        }

        // 2. Video Conversion Placeholder (e.g. FFMPEG compression / MP4 transcode)
        if (str_starts_with($mimeType, 'video/')) {
            return $this->convertVideoMedia($file, $directory, $disk);
        }

        // 3. Audio Conversion Placeholder (e.g. AAC / MP3 compression)
        if (str_starts_with($mimeType, 'audio/')) {
            return $this->convertAudioMedia($file, $directory, $disk);
        }

        // Fallback for non-convertible files (Documents, SVGs, Plain text)
        return [
            $file->store($directory, $disk),
            $file->getSize(),
            $mimeType,
        ];
    }

    /**
     * Convert raster image input to optimized WebP format via GD driver.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return array{0: string, 1: int, 2: string}
     */
    protected function convertImageToWebp(UploadedFile $file, string $directory, string $disk): array
    {
        $image = match ($file->getMimeType()) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getRealPath()),
            'image/png' => imagecreatefrompng($file->getRealPath()),
            'image/gif' => imagecreatefromgif($file->getRealPath()),
            default => null,
        };

        if (! $image) {
            return [
                $file->store($directory, $disk),
                $file->getSize(),
                $file->getMimeType(),
            ];
        }

        // Preserve PNG/GIF alpha channel transparency
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Generate clean UUID target path with .webp extension
        $filename = sha1(time().rand(1, time())) . '.webp';
        $trimmedDir = trim($directory, '/');
        $relativePath = $trimmedDir ? "{$trimmedDir}/{$filename}" : $filename;

        // Capture compressed WebP buffer output
        ob_start();
        imagewebp($image, null, 80);
        $webpContent = ob_get_clean();
        imagedestroy($image);

        // Store binary buffer to target storage disk
        Storage::disk($disk)->put($relativePath, $webpContent);

        return [
            $relativePath,
            strlen($webpContent),
            'image/webp',
        ];
    }

    /**
     * Placeholder method for video media transcode and compression.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return array{0: string, 1: int, 2: string}
     */
    protected function convertVideoMedia(UploadedFile $file, string $directory, string $disk): array
    {
        // Reserved hook for FFMpeg / External Transcoder background processing
        return [
            $file->store($directory, $disk),
            $file->getSize(),
            $file->getMimeType(),
        ];
    }

    /**
     * Placeholder method for audio media transcode and compression.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string $disk
     * @return array{0: string, 1: int, 2: string}
     */
    protected function convertAudioMedia(UploadedFile $file, string $directory, string $disk): array
    {
        // Reserved hook for Audio bitrate compression
        return [
            $file->store($directory, $disk),
            $file->getSize(),
            $file->getMimeType(),
        ];
    }
}
