<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="{{ asset('css/forgot-password.css') }}">
    <style>
        /* Modal styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px; 
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px; 
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px; 
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .reset-form-container {
            display: flex;
            justify-content: center; /* Center horizontally */
            flex-direction: column; /* Stack children vertically */
        }

        .reset-form-container button[type="submit"] {
            background-color: #009FEA;
            color: #fff;
            font-size: 15px;
            padding: 9px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 10rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <x-guest-layout>
        <div class="forgotpass-container mx-auto p-4 pt-6 md:p-6 lg:p-12 flex flex-wrap">
            <div class="form-container w-full md:w-1/2 xl:w-1/2 p-6">
                <div class="form">
                    <h1>RESET PASSWORD</h1>
                    <p style="text-align:center;">Enter your valid email address correctly to receive a recovery link</p>
                    <form id="resetPasswordForm" method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" name="email" id="email" required>
                        </div>
                        <div class="button">
                            <button type="submit" style="justify-content: center">Verify</button>
                        </div> 
                    </form>
                    <div class="links">
                        <a href="{{ route('login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-1/2 xl:w-1/2 p-6 bg-blue-500">
                <img src="{{ asset('images/login-image.png') }}" alt="Right Image" class="w-full h-full object-cover">
            </div>
        </div>
    </x-guest-layout>

    <!-- Modal HTML -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p>Check your email, we have sent you an email to your email address.</p>
        </div>
    </div>

    <script>
        // JavaScript to handle the modal
        document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            // Display the modal
            var modal = document.getElementById('successModal');
            modal.style.display = 'block';

            // Handle closing the modal
            var span = document.getElementsByClassName('close')[0];
            span.onclick = function() {
                modal.style.display = 'none';
                document.getElementById('resetPasswordForm').submit(); // Submit the form after closing the modal
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = 'none';
                    document.getElementById('resetPasswordForm').submit(); // Submit the form after closing the modal
                }
            }
        });
    </script>
</body>
</html>
