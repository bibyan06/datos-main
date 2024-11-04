function showPopupForm() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("popup-form").style.display = "block";
}

function hidePopupForm() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popup-form").style.display = "none";
}

function showPopupForm1() {
    document.getElementById("overlay").style.display = "block";
    document.getElementById("popup-form-1").style.display = "block";
}
function hidePopupForm1() {
    document.getElementById("overlay").style.display = "none";
    document.getElementById("popup-form-1").style.display = "none";
}

function addAccount() {
    document.getElementById("loading-overlay").style.display = "flex";
    // Collect input data
    const lastName = document.getElementById("last-name").value;
    const firstName = document.getElementById("first-name").value;
    const middleName = document.getElementById("middle-name").value;
    const email = document.getElementById("email").value;
    const college_id = document.getElementById("college").value;
    const password = document.getElementById("password").value;
    const employeeId = document.getElementById("employee-id").value;
    const passwordHelp = document.getElementById("password-help").value;
    // Debugging logs
    console.log("Add Account button clicked.");
    console.log("Collected form data:", {
        lastName,
        firstName,
        middleName,
        email,
        college_id,
        password,
        employeeId,
    });

    if (!passwordHelp) {
        // Simple validation
        if (
            !lastName ||
            !firstName ||
            !email ||
            !college_id ||
            !password ||
            !employeeId ||
            passwordHelp
        ) {
            document.getElementById("loading-overlay").style.display = "none";
            console.error("Please fill in all required fields.");
            Swal.fire({
                title: "Error",
                text: "Please fill in all required fields.",
                icon: "error",
                confirmButtonText: "OK",
            });
            return;
        }

        // Send data to server with fetch
        fetch("/add-dean-account", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                last_name: lastName,
                first_name: firstName,
                middle_name: middleName,
                email: email,
                college_id: college_id,
                password: password,
                employee_id: employeeId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                document.getElementById("loading-overlay").style.display = "none";
                console.log("Server response:", data);
                if (data.success) {
                    Swal.fire({
                        title: "Account Added",
                        text: "The college dean account has been added successfully!",
                        icon: "success",
                        confirmButtonText: "OK",
                    });
                    // Hide form
                    hidePopupForm();
                } else {
                    Swal.fire({
                        title: "Failed",
                        text: "Failed to add account: " + data.message,
                        icon: "error",
                        confirmButtonText: "OK",
                    });
                }
            })

            .catch((error) => {
                document.getElementById("loading-overlay").style.display = "none";
                Swal.fire({
                    title: "Error",
                    text: "An error occurred while adding the account.",
                    icon: "error",
                    confirmButtonText: "OK",
                });
                console.error("Error:", error);
            });
    }
}

function addCollege() {
    // Collect input data
    document.getElementById("loading-overlay").style.display = "flex";
    const collegeName = document.getElementById("college-name").value;

    // Debugging logs
    console.log("Add College button clicked.");
    console.log("Collected form data:", { collegeName });

    // Simple validation
    if (!collegeName) {
        document.getElementById("loading-overlay").style.display = "none";
        console.error("Please fill in the required field.");
        Swal.fire({
            title: "Error",
            text: "Please provide the college name.",
            icon: "error",
            confirmButtonText: "OK",
        });
        return;
    }

    // Send data to server with fetch
    fetch("/add-college", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
        body: JSON.stringify({
            college_name: collegeName,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("loading-overlay").style.display = "none"; // Hide loading overlay
            console.log("Server response:", data);
            if (data.success) {
                Swal.fire({
                    title: "College Added",
                    text: "The college has been added successfully!",
                    icon: "success",
                    confirmButtonText: "OK",
                });
            } else {
                Swal.fire({
                    title: "Failed",
                    text: "Failed to add college: " + data.message,
                    icon: "error",
                    confirmButtonText: "OK",
                });
            }
        })
        .catch((error) => {
            document.getElementById("loading-overlay").style.display = "none";
            Swal.fire({
                title: "Error",
                text: "An error occurred while adding the college.",
                icon: "error",
                confirmButtonText: "OK",
            });
            console.error("Error:", error);
        });

    // Hide form
    hidePopupForm1();
}

document.addEventListener("DOMContentLoaded", function () {
    // Now the DOM is fully loaded, and the CSRF meta tag should be available
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        console.log("CSRF token found:", csrfToken.getAttribute("content"));
    } else {
        console.error("CSRF token not found");
    }
});
