<?php

namespace App\Filament\Auth;

use App\Filament\Response\LoginResponse as LResponse;
use App\Models\Relative;
use App\Models\Staff;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login;


class LoginController extends Login
{

    /**
     * @return LoginResponse|null
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();
            return null;
        }

        $data = $this->form->getState();
        $email = $data['email'];
        $password = $data['password'];


        if (User::where('email', $email)->exists()) {
            auth()->shouldUse('admin');
        } elseif (Relative::where('email', $email)->exists()) {
            auth()->shouldUse('relative');
        } elseif (Staff::where('email', $email)->exists()) {
            auth()->shouldUse('staff');
        }

        if (auth()->attempt(['email' => $email, 'password' => $password])) {
            session()->regenerate();

            return app(LResponse::class);
        }
        $this->throwFailureValidationException();
    }

}
