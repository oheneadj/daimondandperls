<?php

namespace App\Providers;

use App\Listeners\LogFailedEmailJob;
use App\Listeners\LogSentEmail;
use Carbon\CarbonImmutable;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\Events\JobFailed;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoApiTransport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerBrevoTransport();
        $this->registerEmailLogging();
    }

    private function registerBrevoTransport(): void
    {
        Mail::extend('brevo', function () {
            return new BrevoApiTransport(config('mail.mailers.brevo.key'));
        });
    }

    private function registerEmailLogging(): void
    {
        Event::listen(MessageSent::class, LogSentEmail::class);
        Event::listen(JobFailed::class, LogFailedEmailJob::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
