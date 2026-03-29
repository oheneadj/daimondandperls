<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use \App\Traits\HasRoles, HasFactory, \Illuminate\Database\Eloquent\Concerns\HasUuids, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'uuid',
        'name',
        'email',
        'password',
        'role',
        'type',
        'is_active',
        'last_login_at',
        'notification_preference',
        'phone',
        'otp_code',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => \App\Enums\UserRole::class,
            'type' => \App\Enums\UserType::class,
            'notification_preference' => \App\Enums\NotificationPreference::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function isAdmin(): bool
    {
        return $this->type === \App\Enums\UserType::Admin;
    }

    public function isCustomer(): bool
    {
        return $this->type === \App\Enums\UserType::Customer;
    }

    public function confirmedBookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class, 'confirmed_by');
    }

    public function verifiedPayments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function activityLogs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get the user's preferred display name
     */
    public function displayName(): string
    {
        if ($this->isCustomer() && $this->customer) {
            return $this->customer->name;
        }

        return $this->name;
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->displayName())
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function unreadBookingsCount(): int
    {
        return $this->unreadNotifications()
            ->where('type', \App\Notifications\BookingReceivedNotification::class)
            ->count();
    }

    public function pendingPaymentsCount(): int
    {
        return \App\Models\Payment::where('status', \App\Enums\PaymentGatewayStatus::Pending)->count();
    }
}
