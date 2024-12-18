<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register</title>
    <link rel="stylesheet" href="{{ asset('css/register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
<x-guest-layout>
    <div class="register-container mx-auto p-4">
        <div class="flex flex-wrap -mx-4">
            <div class="w-full md:w-1/2 xl:w-1/2 p-6 bg-blue-400">
                <img src="{{ asset('images/login-image.png') }}" alt="Left Image" style="width: 800px; height: 700px; margin-top:75px;" class="object-cover">
            </div>
            <div class="registerForm w-full md:w-1/2 xl:w-1/2 p-4">
            <form method="POST" id="registerForm" action="{{ route('register') }}">
            @csrf
             <!-- Display validation errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-container">
                            <h1 class="text-center font-bold text-2xl">CREATE ACCOUNT</h1>
                            <div class="mt-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="last_name" class="last_name block mb-2 text-sm font-medium text-gray-900">Last Name</label>
                                        <input type="text" id="last_name" name="last_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div>
                                        <label for="first_name" class="first_name block mb-2 text-sm font-medium text-gray-900">First Name</label>
                                        <input type="text" id="first_name" name="first_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="middle_name" class="middle_name block mb-2 text-sm font-medium text-gray-900">Middle Name</label>
                                        <input type="text" id="middle_name" name="middle_name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                    <div>
                                        <label for="age" class="age block mb-2 text-sm font-medium text-gray-900">Age</label>
                                        <input type="number" id="age" name="age" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <div id="age-error" class="text-red-500 text-sm mt-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="gender" class="gender block mb-2 text-sm font-medium text-gray-900">Gender</label>
                                        <select id="gender" name="gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label for="phone_number" class="phone_number block mb-2 text-sm font-medium text-gray-900">Phone Number</label>
                                        <input type="tel" id="phone_number" name="phone_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" 
                                        pattern="\d{11}" 
                                        title="Phone number must be exactly 11 digits" 
                                        required>     
                                        <div id="phone_number_error" class="text-red-500 text-sm mt-1"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-1">
                                <label for="home_address" class="home_address block mb-2 text-sm font-medium text-gray-900">Home Address</label>
                                <input type="text" id="home_address" name="home_address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                            </div>

                            <div class="mt-3">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="employee_id" class="employee_id block mb-2 text-sm font-medium text-gray-900">Employee ID</label>
                                        <input type="text" id="employee_id" name="employee_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <div id="employee-id-error" class="text-red-500 text-sm mt-1"></div>
                                    </div>
                                    <div>
                                        <label for="username" class="username block mb-2 text-sm font-medium text-gray-900">Username</label>
                                        <input type="text" id="username" name="username" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label for="email" class="email block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input type="email" id="email" name="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                <div id="email_error" class="text-red-500 text-sm mt-1"></div>
                            </div>

                            <div class="mt-3">
                                <div class="grid grid-cols-2 gap-4">
                                <div class="password-container">
                                    <label for="password" class="password block mb-2 text-sm font-medium text-gray-900">Password</label>
                                    <div class="input-container">
                                        <input type="password" id="password" name="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <span class="register-show-password" onclick="togglePasswordVisibility('password', 'password-eye-icon')">
                                            <i class="fas fa-eye-slash" id="password-eye-icon"></i>
                                        </span>
                                    </div>
                                    <div id="password-error" class="text-red-500 text-sm mt-1"></div> 
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900">Confirm Password</label>
                                    <div class="input-container">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                        <span class="register-show-password" onclick="togglePasswordVisibility('password_confirmation', 'confirm-password-eye-icon')">
                                            <i class="fas fa-eye-slash" id="confirm-password-eye-icon"></i>
                                        </span>
                                    </div>
                                    <div id="confirm-password-error" class="text-red-500 text-sm mt-1"></div>
                                </div>
                                </div>
                            </div>

                            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                                <div class="mt-4">
                                    <label for="terms" class="block mb-2 text-sm font-medium text-gray-900">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="terms" name="terms" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" required>
                                            <span class="ms-2">
                                                {!! __('I agree to the :terms_and_conditions', [
                                                    'terms_and_conditions' => '<a target="_blank" href="'.route('terms-and-conditions.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms and Conditions').'</a>',
                                                ]) !!}
                                            </span>
                                        </div>
                                    </label>
                                </div>
                            @endif

                            <div class="flex items-center justify-between mt-4">
                                <button type="submit" class="bg-blue-500 text-white font-bold py-2 px-4 rounded-lg">Register</button>
                            </div>

                            <div id="form-messages" class="text-red-500 text-sm mt-4"></div>

                            <div class="mt-4 text-center">
                                <span class="text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-blue-500 underline">Login here</a></span>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    console.log($('meta[name="csrf-token"]').attr('content'));

    function togglePasswordVisibility(inputId, iconId) {
        const passwordInput = document.getElementById(inputId);
        const eyeIcon = document.getElementById(iconId);
        
        // Toggle the password input type between 'password' and 'text'
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        }
    }
    window.onload = function() {
        @if(session('status'))
            alert("{{ session('status') }}");
        @endif
    }
    
    $(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var formData = new FormData(this);


        $.ajax({
            url: '{{ route('register') }}',
            type: 'POST',
            data: formData,
            processData: false,  // Required for FormData
            contentType: false,  // Required for FormData
            
            success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registration Successful!',
                        text: 'Please check your email for verification instructions.',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#registerForm')[0].reset(); // Reset the form
                            window.location.href = "{{ route('login') }}";// Redirect to email verification page
                        }
                    });
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                let response = JSON.parse(xhr.responseText);
                console.log(response); // Log the entire response for debugging
                if (response.errors) {
                    // Loop through each error and display it
                    if (response.errors.last_name) {
                        $('#last_name-error').text(response.errors.last_name[0]);
                    }
                    if (response.errors.first_name) {
                        $('#first_name-error').text(response.errors.first_name[0]);
                    }
                    if (response.errors.middle_name) {
                        $('#middle_name-error').text(response.errors.middle_name[0]);
                    }
                    if (response.errors.age) {
                        $('#age-error').text(response.errors.age[0]);
                    }
                    if (response.errors.phone_number) {
                        $('#phone_number_error').text(response.errors.phone_number[0]);
                    }
                    if (response.errors.email) {
                        $('#email_error').text(response.errors.email[0]);
                    }
                    if (response.errors.password) {
                        $('#password-error').text(response.errors.password[0]);
                    }
                    if (response.errors.password_confirmation) {
                        $('#confirm-password-error').text(response.errors.password_confirmation[0]);
                    }
                    if (response.errors.employee_id) {
                        $('#employee-id-error').text(response.errors.employee_id[0]);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Registration Failed',
                            text: sresponse.responseJSON.message || 'There was an error with your registration. Please try again.',
                        confirmButtonText: 'Try Again'
                    });

                    // Displaying specific validation errors
                    if(xhr.status === 422) {
                        var errors = xhr.responseJSON.errors;
                        
                        // Clear previous errors
                        $('#form-messages').empty();
                        $('.text-red-500').empty();

                        // Iterate over errors and display them under the corresponding input fields
                        $.each(errors, function(key, value) {
                            $('#' + key + '-error').text(value[0]); // Show the error under the relevant field
                        });

                    } else {
                        // For other errors
                        $('#form-messages').text('An unknown error occurred. Please try again later.');
                    }
                }
            }
        });
    });
});
</script>
</body>
</html>