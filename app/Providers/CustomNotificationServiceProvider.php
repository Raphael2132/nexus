<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use App\Notifications\CustomResetPassword;

class CustomNotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        ResetPasswordNotification::toMailUsing(function ($notifiable, $token) {
            return (new CustomResetPassword($token))->toMail($notifiable);
        });
    }
}
