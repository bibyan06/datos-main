/*
document.getElementById('file-upload').addEventListener('change', function() {
    var fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    for (var i = 0; i < this.files.length; i++) {
        var li = document.createElement('li');
        li.textContent = this.files[i].name;
        fileList.appendChild(li);
    }
});

document.getElementById('doc-category').addEventListener('change', function() {
    var otherCategoryInput = document.getElementById('other-category');
    if (this.value === 'other') {
        otherCategoryInput.style.display = 'block';
        otherCategoryInput.required = true;
    } else {
        otherCategoryInput.style.display = 'none';
        otherCategoryInput.required = false;
    }
});
*/

document.getElementById('category').addEventListener('change', function () {
    var otherCategoryInput = document.getElementById('other-category');
    if (this.value === 'other') {
        otherCategoryInput.style.display = 'block';
        otherCategoryInput.required = true;
    } else {
        otherCategoryInput.style.display = 'none';
        otherCategoryInput.required = false;
    }
});

document.getElementById('file-input').addEventListener('change', function () {
    var fileList = document.getElementById('file-list');
    fileList.innerHTML = '';
    for (var i = 0; i < this.files.length; i++) {
        var li = document.createElement('li');
        li.textContent = this.files[i].name;
        fileList.appendChild(li);
    }
});


document.addEventListener('DOMContentLoaded', () => {
    const documentNumberField = document.getElementById('document-number');
    const editButton = document.getElementById('edit-document-number-btn');
    const errorText = document.getElementById('documentNumberError');

    // Toggle the disabled state of the input field when the edit button is clicked
    editButton.addEventListener('click', () => {
        if (documentNumberField.disabled) {
            // Enable the field for editing
            documentNumberField.disabled = false;
            documentNumberField.focus(); // Focus the input field when it's enabled
            documentNumberField.style.borderColor = "#007BFF"; // Optional: Highlight the field with blue border
            editButton.innerHTML = '<i class="bi bi-check-circle-fill"></i>'; // Change to "confirm" icon
        } else {
            // Disable the field again after editing
            documentNumberField.disabled = true;
            documentNumberField.style.borderColor = "#CCCCCC"; // Optional: Reset the border color
            editButton.innerHTML = '<i class="bi bi-pencil-square"></i>'; // Revert to "edit" icon
        }
    });

    // Validate document number on blur (when the input field loses focus)
    documentNumberField.addEventListener('blur', () => {
        const documentNumber = documentNumberField.value.trim();

        if (!documentNumber) {
            errorText.textContent = ""; // Clear error if the field is empty
            documentNumberField.style.borderColor = "#CCCCCC"; // Reset the border color
            return;
        }

        // Check if the document number already exists via AJAX (example route)
        fetch("{{ route('admin.check_document_number') }}", {
            method: "POST",
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ document_number: documentNumber })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                errorText.textContent = "Document number already exists!";
                documentNumberField.style.borderColor = "#DC3545"; // Red border for error
            } else {
                errorText.textContent = "";
                documentNumberField.style.borderColor = "#CCCCCC"; // Reset the border color
            }
        })
        .catch(err => {
            console.error('Error:', err);
        });
    });
});
