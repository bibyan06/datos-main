document.addEventListener("DOMContentLoaded", function () {
    var openModalBtn = document.getElementById("openModalBtn");
    var modal = document.getElementById("myModal");
    var closeModal = document.getElementsByClassName("close")[0];
    var saveChangesBtn = document.getElementById("saveChangesBtn");
    var form = document.getElementById("updateProfileForm");
    var infoDetails = document.querySelector(".info-details");
    var ageInput = document.getElementById('age');

    // Open the modal
    openModalBtn.onclick = function () {
        modal.style.display = "block";
    };

    // Close the modal
    closeModal.onclick = function () {
        modal.style.display = "none";
    };

    // Close the modal if clicking outside of it
    window.onclick = function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    };

    // Function to validate age
    function validateAge() {
        const currentYear = new Date().getFullYear();
        const age = parseInt(ageInput.value);
        const birthYear = currentYear - age;

        if (ageInput.value === "" || birthYear > currentYear) {
            ageInput.setCustomValidity('Age cannot be set in the future.');
        } else {
            ageInput.setCustomValidity('');
        }
        ageInput.reportValidity();
    }

    // Function to validate the entire form
    function validateForm(event) {
        let isValid = true;

        // Check all input fields
        const inputs = form.querySelectorAll('input[required]');
        inputs.forEach(input => {
            if (!input.checkValidity()) {
                input.reportValidity();
                isValid = false;
            }
        });

        // If the form is invalid, prevent submission
        if (!isValid) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            // Update info-details section
            updateInfoDetails();
            // Hide the modal
            modal.style.display = "none";
            event.preventDefault(); // Prevent the default form submission
        }
    }

    // Function to update info-details section
    function updateInfoDetails() {
        // Update address
        var street = form.querySelector("#street").value.trim();
        var barangay = form.querySelector("#barangay").value.trim();
        var city = form.querySelector("#city").value.trim();
        var province = form.querySelector("#province").value.trim();

        var address = [street, barangay, city, province].filter(Boolean).join(", ");
        var addressSpan = infoDetails.querySelector('.value[data-field="address"]');
        if (addressSpan) {
            addressSpan.textContent = address;
        }

        // Update other fields
        var fields = form.querySelectorAll("input[data-field]");
        fields.forEach(function (field) {
            var dataField = field.getAttribute('data-field');
            var valueSpan = infoDetails.querySelector(`.value[data-field="${dataField}"]`);
            if (valueSpan && dataField !== "address") {
                valueSpan.textContent = field.value.trim();
            }
        });
    }

    // Event listener for age validation
    ageInput.addEventListener('input', validateAge);
    ageInput.addEventListener('blur', validateAge);

    // Event listener for form submission
    form.addEventListener('submit', validateForm);

    // Save changes and update profile information
    saveChangesBtn.onclick = function () {
        var fields = form.querySelectorAll("input[data-field]");
        var allFieldsFilled = true;
        var emailValid = true;

        // Validate inputs
        fields.forEach(function (field) {
            field.setCustomValidity(""); // Clear previous validity message

            if (field.value.trim() === "" && field.required) {
                allFieldsFilled = false;
                field.setCustomValidity("This field is required.");
            } else if (field.type === "email" && !field.checkValidity()) {
                emailValid = false;
                field.setCustomValidity("Please enter a valid email address.");
            }
        });

        if (!allFieldsFilled || !emailValid) {
            // Show validation warnings if any field is invalid
            var warningContent = document.getElementById("warningContent");
            warningContent.innerHTML = "<p>Please correct the errors in the form.</p>";
            document.getElementById("warningModal").style.display = "block";
        } else {
            // If everything is valid, update details and close modal
            updateInfoDetails();
            modal.style.display = "none";
        }
    };

    // Close warning modal
    document.getElementById("warningCloseBtn").onclick = function () {
        document.getElementById("warningModal").style.display = "none";
    };
});


// dean account save changes
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("myModal");
    const passwordInput = document.getElementById('password');
    const errorPassword = document.getElementById('error-password');
    const confirmationField = document.getElementById('password_confirmation');
    const currentPasswordField = document.getElementById("current_password");
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('password_confirmation');
    const openModalBtn = document.getElementById("openModalBtn");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const saveChangesBtn = document.getElementById("saveChangesBtn");
   
    [passwordField, confirmPasswordField].forEach(field => {
        if (field.value === '') {
            field.classList.remove('is-invalid');  
        }

        // Listen for input changes to remove red outline dynamically
        field.addEventListener('input', function () {
            if (field.value === '') {
                field.classList.remove('is-invalid');
            }
        });
    });

    // Function to validate password strength and match confirmation
    function validatePasswords() {
        const passwordValue = passwordInput.value;
        errorPassword.textContent = '';

        // Check password length
        if (passwordValue.length < 8) {
            errorPassword.textContent = 'Password must be at least 8 characters long.';
        } else if (!/\d/.test(passwordValue)) {
            errorPassword.textContent = 'Password must contain at least one number.';
        } else if (!/[A-Z]/.test(passwordValue)) {
            errorPassword.textContent = 'Password must contain at least one uppercase letter.';
        }

        // Check if password and confirmation match
        if (passwordValue !== confirmationField.value) {
            errorPassword.textContent = 'Passwords do not match. Please try again.';
        }

        if (errorPassword.textContent !== '') {
            errorPassword.style.display = 'block';
        } else {
            errorPassword.style.display = 'none';
            passwordField.classList.remove('is-invalid');
            confirmPasswordField.classList.remove('is-invalid');
        }
    }

    function togglePasswordValidation() {
        if (passwordField.value === "") {
            // If password is empty, remove error classes and validation requirements
            passwordField.classList.remove("is-invalid"); // this class adds the red border
            confirmPasswordField.classList.remove("is-invalid");
            currentPasswordField.removeAttribute("required");
            passwordField.removeAttribute("required");
            confirmPasswordField.removeAttribute("required");
        } else {
            // Add the 'required' attribute back if user starts entering a password
            currentPasswordField.setAttribute("required", true);
            passwordField.setAttribute("required", true);
            confirmPasswordField.setAttribute("required", true);
        }
    }

    passwordField.addEventListener("input", togglePasswordValidation);
    confirmPasswordField.addEventListener("input", togglePasswordValidation);

    // Event listener for password input
    passwordInput.addEventListener('input', validatePasswords);
    confirmationField.addEventListener('input', validatePasswords);

    // Open Modal
    openModalBtn.addEventListener("click", function () {
        modal.style.display = "block";
    });

    // Close Modal
    closeModalBtn.addEventListener("click", function () {
        modal.style.display = "none";
    });

    // Save Changes with AJAX Request
    saveChangesBtn.addEventListener("click", function () {
        const form = document.getElementById("updateProfileForm");
        const formData = new FormData(form);
    
        // Clear previous error messages
        document.querySelectorAll('.error-message').forEach(el => el.textContent = "");
    
        // Check if password fields are filled to validate
        if (passwordInput.value || confirmationField.value) {
            validatePasswords();
            if (errorPassword.textContent !== '') return; // Stop if there are validation errors
        }
    
        fetch(profileUpdateUrl, {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Remove the 'is-invalid' class from password fields if the profile update was successful
                passwordField.classList.remove('is-invalid');
                confirmPasswordField.classList.remove('is-invalid');
        
                // Optionally, clear the input fields after successful update
                passwordField.value = '';
                confirmPasswordField.value = '';
        
                // Remove any inline styles that might be applied (e.g., red border)
                passwordField.style.border = '';
                confirmPasswordField.style.border = '';
        
                Swal.fire({
                    title: 'Success',
                    text: data.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        modal.style.display = 'none'; 
                        location.reload(); 
                    }
                });
            } else if (data.errors) {
                // Handle validation errors
                for (const [field, messages] of Object.entries(data.errors)) {
                    const errorField = document.getElementById(`error-${field}`);
                    if (errorField) errorField.textContent = messages[0]; 
                }
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fix the errors and try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })        
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: 'An unexpected error occurred. Please try again later.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            console.error('Error:', error);
        });
    });
    

    // Close modal when clicking outside
    window.addEventListener("click", function (event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Toggle current password visibility
    document.getElementById('toggleCurrentPassword').addEventListener('click', function (e) {
        const currentPasswordField = document.getElementById('current_password'); 
        const icon = e.target;
        
        // Toggle visibility for current password field
        if (currentPasswordField.type === 'password') {
            currentPasswordField.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye'); // Switch to eye icon
        } else {
            currentPasswordField.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash'); // Switch to eye-slash icon
        }
    });

    // Toggle new password visibility
    document.getElementById('toggleNewPassword').addEventListener('click', function (e) {
        const passwordField = document.getElementById('password');
        const icon = e.target;

        // Toggle visibility for new password field
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.replace('bi-eye-slash', 'bi-eye'); // Switch to eye icon
        } else {
            passwordField.type = 'password';
            icon.classList.replace('bi-eye', 'bi-eye-slash'); // Switch to eye-slash icon
        }
    });
});