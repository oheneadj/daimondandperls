<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{
    public int $unreadCount = 0;

    public function getAuthIdProperty(): int
    {
        return (int) Auth::id();
    }

    public function mount(): void
    {
        $this->refreshCount();
    }

    #[On('echo-private:App.Models.User.{authId},.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated')]
    #[On('notification-read')]
    #[On('notification-received')]
    public function refreshCount(): void
    {
        $this->unreadCount = Auth::user()->unreadNotifications()->count();
    }

    public function toggleSlideover(): void
    {
        $this->dispatch('toggle-notification-slideover');
    }

    public function render()
    {
        return view('livewire.admin.notifications.notification-bell');
    }
}
