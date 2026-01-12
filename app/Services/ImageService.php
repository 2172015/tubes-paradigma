<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    /**
     * Upload gambar ke storage.
     */
    public function upload(UploadedFile $file, string $directory = 'products', string $disk = 'public'): string
    {
        // Generate nama file unik (opsional, default laravel sudah hash name)
        return $file->store($directory, $disk);
    }

    /**
     * Hapus gambar dari storage.
     */
    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}