<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class FileHelper
{
    /**
     * Save an uploaded file to the specified path.
     *
     * @param UploadedFile $file
     * @param string $path
     * @return string
     */
    public static function saveFile(UploadedFile $file, string $path = 'images'): string
    {
        return Storage::disk('public')->put($path, $file);
    }

    /**
     * Delete a file at the specified path.
     *
     * @param string $filePath
     * @return bool
     */
    public static function deleteFile(string $filePath): bool
    {
        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    /**
     * Update a file by replacing the old file with a new one.
     *
     * @param UploadedFile $file
     * @param string $newPath
     * @param string $oldPath
     * @return string
     */
    public static function updateFile(UploadedFile $file, string $newPath = 'images', string $oldPath = 'images'): string
    {
        if ($newPath !== $oldPath) {
            FileHelper::deleteFile($oldPath);
        }

        return FileHelper::saveFile($file, $newPath);
    }
}
