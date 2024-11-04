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


// Profile Update
document.getElementById('saveChangesBtn').addEventListener('click', function () {
    const formData = new FormData(document.getElementById('updateProfileForm'));

    fetch(profileUpdateUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.errors) {
            let errorMessages = '';
            for (let field in data.errors) {
                errorMessages += `<p>${data.errors[field]}</p>`;
            }
            // Use SweetAlert2 for error messages
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                html: errorMessages, 
                confirmButtonText: 'Close'
            });
        } else if (data.success) {
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.success,
                confirmButtonText: 'Ok'
            }).then(() => {
                document.getElementById('myModal').style.display = 'none';
                location.reload(); 
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Something went wrong. Please try again later.',
            confirmButtonText: 'Close'
        });
    });
});