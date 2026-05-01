<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\NotificationPreference;
use App\Enums\PaymentGatewayStatus;
use App\Enums\UserRole;
use App\Enums\UserType;
use App\Notifications\Auth\ResetPasswordNotification;
use App\Notifications\BookingReceivedNotification;
use App\Traits\HasRoles;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, \Illuminate\Database\Eloquent\Concerns\HasUuids, Notifiable, TwoFactorAuthenticatable;

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
        'phone_verified_at',
        'otp_code',
        'otp_expires_at',
        'saved_momo_number',
        'saved_momo_network',
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'must_change_password',
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
            'phone_verified_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'otp_expires_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'type' => UserType::class,
            'notification_preference' => NotificationPreference::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'invitation_sent_at' => 'datetime',
            'invitation_accepted_at' => 'datetime',
            'must_change_password' => 'boolean',
        ];
    }

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class);
    }

    public function hasVerifiedPhone(): bool
    {
        return $this->phone_verified_at !== null;
    }

    public function isAdmin(): bool
    {
        return $this->type === UserType::Admin;
    }

    public function isCustomer(): bool
    {
        return $this->type === UserType::Customer;
    }

    public function confirmedBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'confirmed_by');
    }

    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function activityLogs(): HasMany
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
            ->where('type', BookingReceivedNotification::class)
            ->count();
    }

    public function pendingPaymentsCount(): int
    {
        return Payment::where('status', PaymentGatewayStatus::Pending)->count();
    }

    public function newContactMessagesCount(): int
    {
        // Cached for 60s so the dashboard and sidebar share one query per minute.
        return Cache::remember('contact_messages.new_count', 60, fn () => ContactMessage::where('status', 'new')->count());
    }

    public function sendPasswordResetNotification(#[\SensitiveParameter] $token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
