<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PhotoUploadService
{
    private string $disk;
    private string $directory;

    public function __construct(string $directory = 'photos', string $disk = 'public')
    {
        $this->disk = $disk;
        $this->directory = $directory;
    }

    public function store(TemporaryUploadedFile|UploadedFile $photo): string
    {
        return $photo->store($this->directory, $this->disk);
    }

    public function replace(TemporaryUploadedFile|UploadedFile $newPhoto, ?string $existingPath): string
    {
        $this->delete($existingPath);

        return $this->store($newPhoto);
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
        }
    }

    public function url(?string $path): ?string
    {
        return $path ? Storage::url($path) : null;
    }
}
