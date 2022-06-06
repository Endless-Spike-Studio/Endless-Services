<?php

namespace App\Http\Controllers;

use App\Events\UserEmailChanged;
use App\Events\UserPasswordChanged;
use App\Events\UserRegistered;
use App\Http\Requests\UserLoginApiRequest;
use App\Http\Requests\UserRegisterApiRequest;
use App\Http\Requests\UserSettingUpdateApiRequest;
use App\Http\Requests\UserVerifyApiRequest;
use App\Http\Traits\HasMessage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;

class UserController extends Controller
{
    use HasMessage;

    public function register(UserRegisterApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = User::create($data);
        Auth::login($user, true);
        UserRegistered::dispatch($user);

        $this->pushSuccessMessage(
            __('messages.register_success')
        );

        return to_route('home');
    }

    public function login(UserLoginApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (!Auth::attempt($data, true)) {
            $this->pushErrorMessage(
                __('messages.login_failed')
            );

            return back();
        }

        return Redirect::intended();
    }

    public function verify(UserVerifyApiRequest $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            $this->pushErrorMessage(
                __('messages.email_already_verified')
            );

            return back();
        }

        $user->markEmailAsVerified();
        $this->pushSuccessMessage(
            __('messages.email_verified')
        );

        return to_route('home');
    }

    public function logout(): RedirectResponse
    {
        Auth::logoutCurrentDevice();
        return to_route('home');
    }

    public function resendEmailVerification(): RedirectResponse
    {
        $user = Request::user();

        $attempt = RateLimiter::attempt(
            "gdcn:resendEmailVerification:$user->id",
            1,
            function () use ($user) {
                if ($user->hasVerifiedEmail()) {
                    $this->pushErrorMessage(
                        __('messages.email_already_verified')
                    );

                    return false;
                }

                $user->sendEmailVerificationNotification();
                $this->pushSuccessMessage(
                    __('messages.verification_sent')
                );

                return true;
            }, 3600);

        if (!$attempt) {
            $this->pushErrorMessage(
                __('messages.too_fast')
            );
        }

        return back();
    }

    public function updateSetting(UserSettingUpdateApiRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $user = $request->user();
        $user->update($data);

        if ($user->wasChanged('password')) {
            UserPasswordChanged::dispatch($user);
        }

        if ($user->wasChanged('email')) {
            $user->update([
                'email_verified_at' => null
            ]);

            UserEmailChanged::dispatch($user);
        }

        $this->pushSuccessMessage(
            __('messages.profile_updated')
        );

        return back();
    }
}
