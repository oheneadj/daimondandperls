<?php

namespace App\Http\Responses;

use App\Enums\UserType;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user->type === UserType::Admin) {
            return redirect()->intended(route('admin.dashboard'));
        }

        // Always send customers to their dashboard — never follow a stored
        // "intended" URL which may point to an admin-only route from a prior session.
        return redirect(route('dashboard.index'));
    }
}
