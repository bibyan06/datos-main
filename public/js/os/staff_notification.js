// Date and time update
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
setInterval(updateTime, 1000);

document.addEventListener('DOMContentLoaded', function() {
    const emailItems = document.querySelectorAll('.email-item');

    emailItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.email-actions') && !e.target.closest('.checkbox')) {
                const forwardedDocumentId = this.getAttribute('data-id');
                const sender = this.getAttribute('data-sender');
                const documentName = this.getAttribute('data-document');
                const snippet = this.getAttribute('data-snippet');
                const fileUrl = this.getAttribute('data-file-url');

                Swal.fire({
                    title: `<strong>${documentName}</strong>`,
                    html: `
                        <div style="text-align: left; margin-top: 10px;">
                            <p><strong>Sender:</strong> ${sender}</p>
                            <p><strong>Description:</strong> ${snippet}</p>
                        </div>
                        <iframe src="${fileUrl}" width="100%" height="400px" style="border:none; margin-top: 20px;"></iframe>
                    `,
                    showCloseButton: true,
                    confirmButtonText: 'Mark as viewed',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    customClass: {
                        popup: 'custom-swal-width',
                        title: 'custom-title'
                    }
                }).then((result) => {
                  
                    if (result.isConfirmed && forwardedDocumentId) {
                        console.log("Attempting to send request to update status...");
                        
                        fetch(`/forwarded-documents/${forwardedDocumentId}/update-status`, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => {
                            console.log("Received response:", response);
                            if (!response.ok) {
                                throw new Error(`HTTP error! Status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log("Response data:", data);
                            if (data.success) {
                                Swal.fire('Success', 'Document status updated to "viewed".', 'success')
                                    .then(() => {
                                        document.querySelector(`[data-id="${forwardedDocumentId}"]`).classList.remove('delivered');
                                        document.querySelector(`[data-id="${forwardedDocumentId}"] .sender`).style.fontWeight = 'normal';
                                        document.querySelector(`[data-id="${forwardedDocumentId}"] .data-document`).style.fontWeight = 'normal';
                                    });
                            } else {
                                Swal.fire('Error', data.message || 'Failed to update document status.', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error during fetch operation:', error);
                            Swal.fire('Error', 'Failed to update document status. Please try again.', 'error');
                        });
                    }
                });
            }
        });
    });
});
