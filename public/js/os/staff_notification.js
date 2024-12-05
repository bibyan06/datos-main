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
                    // title: `<strong>${documentName}</strong>`,
                    html: `
                        <div style="display: flex; width: 100%; height: 100%; gap: 20px;">
                            
                            <iframe src="${fileUrl}" style="width: 100%; height: 700px; border: none;"></iframe>
                            
                            <div style="width: 50%; height: 220px; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Document Name:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">${documentName}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">From:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">${sender}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Description:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">${snippet}</p>
                                </div>
                            </div>
                        </div>     
                    `,
                    showCloseButton: true,
                    confirmButtonText: 'Mark as viewed',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    customClass: {
                        popup: 'custom-swal-width',
                        confirmButton: 'custom-confirm-button',
                        cancelButton: 'custom-cancel-button',
                        actions: 'custom-actions-position'
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

document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener('click', function (e) {
        if (e.target.closest('.declined-docs')) {
            const item = e.target.closest('.declined-docs');
            // Prevent actions when clicking on email actions or checkboxes
            if (e.target.closest('.email-actions') || e.target.type === 'checkbox') {
                return; // Prevent processing if it's an email action or checkbox
            }

            // Extract relevant data from the row
            const documentId = item.getAttribute('data-id');
            const documentName = item.getAttribute('data-document');
            const sender = item.querySelector('.sender').textContent.trim();
            const dataRemark = item.getAttribute('data-remark');
            const dataStatus = item.getAttribute('data-status');
            const fileUrl = item.getAttribute('data-file-url');

            // Use SweetAlert2 to display the document details
            Swal.fire({
                html: ` 
                     <div style="display: flex; width: 100%; height: 100%; gap: 20px;">

                        <iframe src="${fileUrl}"style="width: 100%; height: 700px; border: none;"></iframe>
                        
                          <div style="width: 50%; height: 255px; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Document Name:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${documentName}</p>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">From:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${sender}</p>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Remark:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${dataRemark}</p>
                            </div>
                            <div>
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Status:</p>
                               <p style="margin: 0; font-size: 20px; color: ${
                                    dataStatus === 'Pending' ? '#F47122' :
                                    dataStatus === 'Declined' ? '#e74c3c' :
                                    dataStatus === 'Approved' ? '#87ab69' :
                                    '#555'
                                };">${dataStatus}</p>
                            </div>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                confirmButtonText: 'Mark as viewed',
                showCancelButton: true,
                cancelButtonText: 'Close',
                customClass: {
                    popup: 'custom-swal-width',
                    confirmButton: 'custom-confirm-button',
                    cancelButton: 'custom-cancel-button',
                    actions: 'custom-actions-position'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log("Attempting to send request to update status...");
                    fetch(`/declined-documents/${documentId}/update-status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('Success', 'Document status updated to "viewed".', 'success')
                            .then(() => {
                                document.querySelector(`[data-id="${documentId}"]`).classList.remove('delivered');
                                document.querySelector(`[data-id="${documentId}"] .documentname`).style.fontWeight = 'normal';
                                document.querySelector(`[data-id="${documentId}"] .sender`).style.fontWeight = 'normal';
                                document.querySelector(`[data-id="${documentId}"] .snippet`).style.fontWeight = 'normal';
                                document.querySelector('.document-type').style.fontWeight = 'normal';
                                document.querySelector('.date').style.fontWeight = 'normal';
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message || 'Failed to update document status.', 'error');
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', 'Failed to update document status. Please try again.', 'error');
                    });
                }
            });
        }
    });
});



