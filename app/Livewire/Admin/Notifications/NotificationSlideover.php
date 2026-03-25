<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationSlideover extends Component
{
    public bool $isOpen = false;

    #[On('toggle-notification-slideover')]
    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
        if ($this->isOpen) {
            $this->dispatch('notification-slideover-opened');
        }
    }

    public function markAsRead(string $id): void
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->dispatch('notification-read');
    }

    public function render()
    {
        return view('livewire.admin.notifications.notification-slideover', [
            'notifications' => Auth::user()->notifications()->latest()->limit(20)->get(),
        ]);
    }
}
