<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // ─── Branded Email Verification Email ────────────────────────────────
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify Your Email — Learnerium')
                ->view('emails.verify-email', [
                    'userName'        => $notifiable->name,
                    'verificationUrl' => $url,
                ]);
        });

        // ─── Branded Password Reset Email ─────────────────────────────────────
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Your Password — Learnerium')
                ->view('emails.reset-password', [
                    'userName' => $notifiable->name,
                    'resetUrl' => $url,
                ]);
        });
    }
}
