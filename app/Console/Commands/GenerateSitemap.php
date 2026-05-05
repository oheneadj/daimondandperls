<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate the sitemap.xml file';

    public function handle(): int
    {
        $sitemap = Sitemap::create()
            ->add(Url::create('/')->setPriority(1.0)->setChangeFrequency('weekly'))
            ->add(Url::create('/menu')->setPriority(0.9)->setChangeFrequency('weekly'))
            ->add(Url::create('/event-booking')->setPriority(0.8)->setChangeFrequency('monthly'))
            ->add(Url::create('/about')->setPriority(0.7)->setChangeFrequency('monthly'))
            ->add(Url::create('/contact')->setPriority(0.7)->setChangeFrequency('monthly'))
            ->add(Url::create('/terms')->setPriority(0.3)->setChangeFrequency('yearly'))
            ->add(Url::create('/privacy')->setPriority(0.3)->setChangeFrequency('yearly'));

        Category::query()->pluck('slug')->each(function (string $slug) use ($sitemap): void {
            $sitemap->add(
                Url::create("/menu?category={$slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency('weekly'),
            );
        });

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap generated at public/sitemap.xml');

        return self::SUCCESS;
    }
}
