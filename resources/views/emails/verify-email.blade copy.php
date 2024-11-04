<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 verify-email-message">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between verify-email-buttons">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit" class="verify-email-button verify-email-button--primary">
                        {{ __('Resend Verification Email') }}
                    </x-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf

                <button type="submit" class="verify-email-button verify-email-button--secondary ms-2">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
</body>
</html>
