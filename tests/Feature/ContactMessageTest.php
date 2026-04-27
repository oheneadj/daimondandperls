<?php

declare(strict_types=1);

use App\Livewire\Admin\ContactMessages\Index;
use App\Livewire\ContactForm;
use App\Models\ContactMessage;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeAdminWithAllPermissions(): User
{
    $role = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => 'Super Admin']);
    $admin = User::factory()->admin()->create();
    $admin->assignRole($role);

    return $admin;
}

// ── ContactForm ──────────────────────────────────────────────────────────────

it('saves contact message to database on submit', function (): void {
    Livewire::test(ContactForm::class)
        ->set('name', 'Kwame Mensah')
        ->set('email', 'kwame@example.com')
        ->set('phone', '0244000001')
        ->set('inquiry_type', 'General Inquiry')
        ->set('message', 'I would like to enquire about your catering services.')
        ->call('submit')
        ->assertSet('submitted', true);

    expect(ContactMessage::count())->toBe(1);
    $msg = ContactMessage::first();
    expect($msg->name)->toBe('Kwame Mensah')
        ->and($msg->email)->toBe('kwame@example.com')
        ->and($msg->status)->toBe('new');
});

it('notifies admins with receive_contact_notifications permission', function (): void {
    Notification::fake();

    $perm = Permission::updateOrCreate(['slug' => 'receive_contact_notifications'], ['name' => 'Receive Contact Notifications', 'description' => '']);
    $role = Role::updateOrCreate(['slug' => 'super_admin'], ['name' => 'Super Admin', 'description' => '']);
    $role->permissions()->syncWithoutDetaching([$perm->id]);

    $admin = User::factory()->admin()->create(['is_active' => true]);
    $admin->assignRole($role);

    $noPermAdmin = User::factory()->admin()->create(['is_active' => true]);

    Livewire::test(ContactForm::class)
        ->set('name', 'Ama Sarpong')
        ->set('email', 'ama@example.com')
        ->set('phone', '0244000002')
        ->set('inquiry_type', 'Pricing')
        ->set('message', 'Please send me your pricing for a 200-person event.')
        ->call('submit');

    Notification::assertSentTo($admin, \App\Notifications\ContactMessageReceivedNotification::class);
    Notification::assertNotSentTo($noPermAdmin, \App\Notifications\ContactMessageReceivedNotification::class);
});

// ── Admin Index ───────────────────────────────────────────────────────────────

it('shows contact messages list to authorised admin', function (): void {
    $admin = makeAdminWithAllPermissions();
    $this->actingAs($admin);

    ContactMessage::factory()->create(['name' => 'Test User', 'status' => 'new']);

    Livewire::test(Index::class)
        ->assertSee('Test User')
        ->assertSee('new');
});

it('marks message as read when opened', function (): void {
    $admin = makeAdminWithAllPermissions();
    $this->actingAs($admin);

    $msg = ContactMessage::factory()->create(['status' => 'new']);

    Livewire::test(Index::class)
        ->call('openMessage', $msg->id);

    expect($msg->fresh()->status)->toBe('read');
});

it('marks message as responded with notes', function (): void {
    $admin = makeAdminWithAllPermissions();
    $this->actingAs($admin);

    $msg = ContactMessage::factory()->create(['status' => 'read']);

    Livewire::test(Index::class)
        ->call('openMessage', $msg->id)
        ->set('responseNotes', 'Called back, resolved.')
        ->call('markResponded');

    $fresh = $msg->fresh();
    expect($fresh->status)->toBe('responded')
        ->and($fresh->response_notes)->toBe('Called back, resolved.')
        ->and($fresh->responded_by_id)->toBe($admin->id)
        ->and($fresh->responded_at)->not->toBeNull();
});

it('deletes a contact message', function (): void {
    $admin = makeAdminWithAllPermissions();
    $this->actingAs($admin);

    $msg = ContactMessage::factory()->create();

    Livewire::test(Index::class)
        ->call('deleteMessage', $msg->id);

    expect(ContactMessage::find($msg->id))->toBeNull();
});

it('filters messages by status', function (): void {
    $admin = makeAdminWithAllPermissions();
    $this->actingAs($admin);

    ContactMessage::factory()->create(['name' => 'New Person', 'status' => 'new']);
    ContactMessage::factory()->create(['name' => 'Done Person', 'status' => 'responded']);

    Livewire::test(Index::class)
        ->set('filterStatus', 'new')
        ->assertSee('New Person')
        ->assertDontSee('Done Person');
});
