<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class InvitationController extends Controller
{
    public function accept(string $token): RedirectResponse
    {
        $user = User::where('invitation_token', $token)->firstOrFail();

        $user->update([
            'invitation_accepted_at' => now(),
            'invitation_token' => null,
            'email_verified_at' => $user->email_verified_at ?? now(),
        ]);

        return redirect()->route('login')
            ->with('status', 'Invitation accepted — sign in with the credentials from your email.');
    }
}
