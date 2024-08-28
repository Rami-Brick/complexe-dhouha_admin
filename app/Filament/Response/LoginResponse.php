<?php

namespace App\Filament\Response;

class LoginResponse implements \Filament\Http\Responses\Auth\Contracts\LoginResponse
{
    public function toResponse($request)
    {
        return redirect()->intended(auth()->guard()->name);
    }
}
