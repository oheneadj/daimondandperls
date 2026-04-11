<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Notifications;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationSlideover extends Component
{
    public bool $isOpen = false;

    public int $unreadCount = 0;

    public int $limit = 20;

    #[On('toggle-notification-slideover')]
    public function toggle(): void
    {
        $this->isOpen = ! $this->isOpen;
        if ($this->isOpen) {
            $this->refreshCount();
        }
    }

    #[On('notification-read')]
    public function refreshCount(): void
    {
        $this->unreadCount = Auth::user()->unreadNotifications()->count();
    }

    public function markAsRead(string $id): void
    {
        $notification = Auth::user()->notifications()->find($id);
        if ($notification) {
            $notification->markAsRead();
            $this->refreshCount();
            $this->dispatch('notification-read');
        }
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
        $this->refreshCount();
        $this->dispatch('notification-read');
    }

    public function loadMore(): void
    {
        $this->limit += 20;
    }

    public function render()
    {
        return view('livewire.admin.notifications.notification-slideover', [
            'notifications' => Auth::user()->notifications()->latest()->limit($this->limit)->get(),
        ]);
    }
}
