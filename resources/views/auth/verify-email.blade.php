<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <link rel="stylesheet" href="{{ asset('css/verify-email.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <div class="verify-email-container">
        <div class="verify-email-card">
            <h2 class="verify-email-title">Verify Email Address</h2>
            <div class="mb-4 text-sm text-gray-600 verify-email-message">
                {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
                </div>
            @endif

            <div class="mt-4 flex items-center justify-between verify-email-buttons">
                <form method="POST" id="resend-verification-form" action="{{ route('verification.send') }}">
                    @csrf

                    <div>
                        <button type="submit" class="verify-email-button verify-email-button--primary">
                            {{ __('Resend Verification Email') }}
                        </button>
                    </div>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf

                    <button type="submit" class="verify-email-button verify-email-button--secondary ms-2">
                        {{ __('Cancel') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#resend-verification-form').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '{{ route('verification.send') }}',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('A new verification link has been sent to your email address.');
                    },
                    error: function(response) {
                        alert('There was an error resending the verification email.');
                    }
                });
            });
        });
    </script>
</body>
</html>
