<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <!-- Using CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<x-guest-layout>
    <div class="container mx-auto p-4 pt-6  flex flex-wrap">
        <div class="w-full md:w-1/2 xl:w-1/2 bg-white">
            <h1 class="welcome-header">WELCOME TO</h1>
            <div class="logo"><img src="{{ asset('images/logo.png') }}" alt="DATOS Logo"></div>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="employee-id">
                    <label for="employee_id" class="block text-sm font-bold mb-2">Employee ID</label>
                    <input type="text" id="employee_id" name="employee_id" class="w-full pl-10 text-sm text-gray-700"
                        required autofocus>
                </div>
                <div class="password-input">
                    <label for="password" class="block text-sm font-bold mb-2">Password</label>
                    <input type="password" id="password" name="password">
                    <span class="show-password" onclick="togglePasswordVisibility()">
                        <i class="fas fa-eye-slash" id="eye-icon"></i>
                    </span>
                </div>
                <div style="margin-bottom: 4rem; margin-top:1rem;" class="flex items-center justify-between mb-4">
                    <label for="remember" class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="mr-2">
                        <span class="text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-500 hover:text-gray-900">Forgot
                        Password?</a>
                </div>
                <button type="submit" class=" text-white font-bold py-2 px-4 rounded w-full">Log In</button>
                <div class="account-link">Don't have an account? <a
                        href="{{ route('register') }}"class="text-blue-500 hover:text-blue-700">Create Account</a></div>
            </form>
        </div>
        <div class="w-full md:w-1/2 xl:w-1/2 p-6 bg-blue-500">
            <img src="{{ asset('images/login-image.png') }}" alt="" class="w-full h-full object-cover">
        </div>
    </div>

    <!-- Error Modal -->
    @if (session('error'))
        <div id="errorModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="{{session('error')=='Please verify your account first!'?'error-modal':'error-modal-pass'}} bg-white p-4 rounded shadow-md text-center relative z-50">
                <h2 class="text-red-500 text-lg font-bold mb-4">Error</h2>
                <p class="text-gray-700 mb-4">{{ session('error') }}</p>

                @if (session('error') == 'Please verify your Account First!')
                    <!-- Conditional message if verification is needed -->
                    <p class="text-gray-700 mb-4">
                        If not verified,
                        <input type="hidden" id="verifEmail" value="{{ session('email') }}">
                        <span class="text-blue-500 underline" id="clickable" style="cursor: pointer;">
                            please click here
                        </span>
                        to verify.
                    </p>
                @endif


                <button onclick="closeErrorModal()"
                    class="okay-button bg-blue-500 text-white px-4 py-2 rounded">OK</button>
            </div>
            <div class="fixed inset-0 bg-black opacity-10"></div>
        </div>
    @endif

</x-guest-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const handleverify = document.getElementById('clickable');

    if (handleverify) {
        handleverify.addEventListener('click', function() {
            const verifyElement = document.getElementById('verifEmail');
            const verify = verifyElement ? verifyElement.value : null;
            closeErrorModal();
            if (!verify) {
                Swal.fire({
                    title: "Error",
                    text: "Verification ID is missing.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                return;
            }

            fetch("/verification?userID=" + verify)
                .then((response) => response.json()) // Return JSON here
                .then((data) => {
                    console.log(data);

                    if (data.success) {

                        Swal.fire({
                            title: "Account Verification Sent",
                            text: "Please check your email to verify your account.",
                            icon: "success",
                            confirmButtonText: "OK",
                        });
                    } else {
                        Swal.fire({
                            title: "Failed",
                            text: "Failed to send Verification",
                            icon: "error",
                            confirmButtonText: "OK",
                        });
                    }
                })
                .catch((err) => {
                    Swal.fire({
                        title: "Error",
                        text: "An error occurred while sending Verification.",
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                    console.error("Error:", err);
                });
        });
    }
</script>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('fa-eye');
            eyeIcon.classList.remove('fa-eye-slash');

        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.add('fa-eye-slash');
            eyeIcon.classList.remove('fa-eye');

        }
    }

    function closeErrorModal() {
        document.getElementById('errorModal').style.display = 'none';
    }

    // Automatically close the error modal after 3 seconds (optional)
    // setTimeout(closeErrorModal, 8000);
</script>
