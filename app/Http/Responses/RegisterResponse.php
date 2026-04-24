<?php

declare(strict_types=1);

namespace App\Http\Responses;

use App\Enums\UserType;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
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

        return redirect(route('dashboard.index'));
    }
}
