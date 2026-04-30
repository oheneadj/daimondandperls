<?php

declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class OptimiseImage implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 30;

    public function __construct(
        public readonly string $disk,
        public readonly string $path,
        public readonly string $modelClass,
        public readonly int $modelId,
        public readonly string $modelColumn,
    ) {}

    // I convert the uploaded image to WebP, compress it, and cap the size —
    // then update the model so it points to the new file.
    public function handle(): void
    {
        $fullPath = Storage::disk($this->disk)->path($this->path);

        if (! file_exists($fullPath)) {
            return; // I skip silently — file may have been replaced before this job ran
        }

        $manager = new ImageManager(new Driver);
        $image = $manager->read($fullPath);
        $image->scaleDown(width: 1920, height: 1920); // I never upscale

        $webpPath = (string) preg_replace('/\.[^.]+$/', '.webp', $this->path);
        $webpFullPath = Storage::disk($this->disk)->path($webpPath);

        $image->toWebp(quality: 75)->save($webpFullPath);

        // I only delete the original when the extension actually changed
        if ($this->path !== $webpPath && file_exists($fullPath)) {
            unlink($fullPath);
        }

        $this->modelClass::where('id', $this->modelId)
            ->update([$this->modelColumn => $webpPath]);
    }
}
