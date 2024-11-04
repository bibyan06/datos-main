<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <style>
        .input-group {
            position: relative;
            width: 100%;
        }

        .input-group input {
            padding-right: 40px; /* Space for the eye icon */
        }

        .eye-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            border: none; /* Remove default button styles */
            background: transparent; /* Make button background transparent */
        }
    </style>
</head>

<body>
    <x-guest-layout>
        <div class="reset-pass-container mx-auto p-4 pt-6 md:p-6 lg:p-12 flex flex-wrap">
            <div class="w-full md:w-1/2 xl:w-1/2 p-6">
                <x-validation-errors class="mb-4" />
                <div class="reset-form-container">
                    <h1>CHANGE PASSWORD</h1>
                    <p style="text-align:center;">Enter a new password below to change your password</p>
                    <form method="POST" action="{{ route('password.update') }}" onsubmit="return validatePassword()">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            <div class="mb-4">
                                <x-label for="email" value="{{ __('Email') }}" />
                                <x-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email', $request->email)" required autofocus autocomplete="username" />
                            </div>

                            <div class="mb-4">
                                <x-label for="password" value="{{ __('Password') }}" />
                                <div class="input-group">
                                    <x-input id="password" class="block mt-1 w-full" type="password" name="password"
                                        required autocomplete="new-password" />
                                    <button type="button" class="eye-icon" onclick="togglePasswordVisibility('password')">👁️</button>
                                </div>
                                <small id="passwordHelp" class="text-muted">
                                    Password must be at least 8 characters long, contain one uppercase letter, one
                                    lowercase letter, and one number.
                                </small>
                            </div>

                            <div class="mb-4">
                                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                                <div class="input-group">
                                    <x-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                        name="password_confirmation" required autocomplete="new-password" />
                                    <button type="button" class="eye-icon" onclick="togglePasswordVisibility('password_confirmation')">👁️</button>
                                </div>
                                <small id="error" style="color: rgb(145, 4, 4)">
                                </small>
                            </div>
                        </div>
                        <x-button> {{ __('Change') }} </x-button>
                    </form>
                    <div class="links">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
            <div class="bg-blue-500 w-full md:w-1/2 xl:w-1/2 p-6" style="height: 590px;">
                <img src="{{ asset('images/login-image.png') }}" alt="Right Image" class="w-full h-full object-cover">
            </div>
        </div>
    </x-guest-layout>

    <script>
        function togglePasswordVisibility(fieldId) {
            const passwordField = document.getElementById(fieldId);
            if (passwordField.type === "password") {
                passwordField.type = "text";
            } else {
                passwordField.type = "password";
            }
        }

        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const error = document.getElementById('error');

            // Regular expressions for validation
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const isValidLength = password.length >= 8;

            if (!isValidLength) {
                error.innerHTML = 'Password must be at least 8 characters long';
                return false;
            }
            if (!hasUpperCase) {
                error.innerHTML = 'Password must contain one uppercase letter';
                return false;
            }
            if (!hasLowerCase) {
                error.innerHTML = 'Password must contain one lowercase letter';
                return false;
            }
            if (!hasNumber) {
                error.innerHTML = 'Password must contain a number.';
                return false;
            }
            if (password !== confirmPassword) {
                error.innerHTML = 'Passwords do not match.';
                return false;
            }

            error.innerHTML = '';
            return true;
        }
    </script>

</body>

</html>
