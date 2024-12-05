document.addEventListener("DOMContentLoaded", () => {
    const dateTimeElement = document.getElementById("current-date-time");


    function updateTime() {
        const now = new Date();
        const hours = now.getHours();
        const minutes = now.getMinutes();
        const seconds = now.getSeconds();
        const ampm = hours >= 12 ? 'PM' : 'AM';
        const formattedHours = hours % 12 || 12;
        const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        const formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

        const timeString = `${formattedHours}:${formattedMinutes}:${formattedSeconds} ${ampm}`;
        const dateString = now.toLocaleDateString('en-US', { weekday: 'long', day: 'numeric', month: 'numeric', year: 'numeric' });

        if (dateTimeElement) {
            dateTimeElement.textContent = `${dateString} ${timeString}`;
        }
    }

    updateTime();
    setInterval(updateTime, 1000); // Update every second
});

function showPopupForm(request,id,data1,data2) {
    document.getElementById('overlay').style.display = 'block';
    document.getElementById('popup-form').style.display = 'block';
    document.getElementById('document-subject').value = data1;
    document.getElementById('document-purpose').value = data2;
    document.getElementById('request_id').value = id;
    document.getElementById('docu-id').value = request;
}

function hidePopupForm() {
    document.getElementById('request_id').value = 0;

    document.getElementById('overlay').style.display = 'none';
    document.getElementById('popup-form').style.display = 'none';
    document.getElementById('document-subject').value = '';
    document.getElementById('document-purpose').value = '';
    document.getElementById('docu-id').value = '';

}

function sendDocument() {
    const senderName = document.getElementById('sender-name').value;
    const documentSubject = document.getElementById('document-subject').value;
    const documentPurpose = document.getElementById('document-purpose').value;
    const documentId = document.getElementById('document-id').value;
    const fileName = document.getElementById('file-name').value;
    const date = document.getElementById('date').value;

    console.log('Sender Name:', senderName);
    console.log('Document Subject:', documentSubject);
    console.log('Document Purpose:', documentPurpose);
    console.log('Document ID:', documentId);
    console.log('File Name:', fileName);
    console.log('Date:', date);

    hidePopupForm();
}

function declineDocument(requestId) {
    Swal.fire({
        title: 'Decline Document',
        input: 'text',
        // inputLabel: 'Reason for Decline',
        inputPlaceholder: 'Enter your remark here...',
        showCancelButton: true,
        confirmButtonText: 'Decline',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'swal-decline-btn',
            cancelButton: 'swal-cancel-btn'
        },
        inputValidator: (value) => {
            if (!value) {
                return 'Please enter a remark for declining!';
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const remarks = result.value || '';
            const declineUrl = document.querySelector(`[data-document-id="${requestId}"]`).getAttribute('data-decline-url');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // Retrieve CSRF token

            fetch(declineUrl, {
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrfToken,  // Use CSRF token
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    request_id: requestId,
                    approval_status: 'Declined',
                    remarks: remarks
                })
            })
            .then(response => {
                if (!response.ok) throw new Error('Network response was not OK');
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Declined',
                        text: 'The document has been declined successfully.',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload(); // Refresh page after action
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred while declining the document.',
                    });
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong. Please try again.',
                });
            });
        }
    });
}
