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
    setInterval(updateTime, 1000);
});


document.addEventListener("DOMContentLoaded", () => {
    const emailItems = document.querySelectorAll('.email-item');

    emailItems.forEach(item => {
        item.addEventListener('click', function (e) {
            // Check if the click was inside the email-actions or the checkbox
            if (e.target.closest('.email-actions') || e.target.type === 'checkbox') {
                return;
            }

            // Get relevant information from the clicked row
            // const documentName = this.querySelector('.document-name').textContent.trim();
            const receiverName = this.querySelector('.receiver').textContent.trim();
            const currentStatus = this.getAttribute('data-status');
            const message = this.getAttribute('data-message');
            const documentName = this.getAttribute('data-document-name');
            const status = (currentStatus == 'archiveNotif' || currentStatus == 'deleted' || currentStatus == 'restored' ) 
               ? 'viewed' 
               : (currentStatus == 'delivered' ? 'delivered' : currentStatus);
            const snippet = this.getAttribute('data-snippet');
            const fileUrl = this.getAttribute('data-file-url');

            Swal.fire({
                html: `
                    <div style="display: flex; width: 100%; height: 100%; gap: 20px;">

                        <iframe src="${fileUrl}" style="width: 100%; height: 700px; border: none;"></iframe>
                        
                        <div style="width: 50%; height: 293px; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Document Name:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${documentName}</p>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">To:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${receiverName}</p>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Message:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${message}</p>
                            </div>
                            <div>
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Status:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${status ? status : 'delivered'}</p>
                            </div>
                        </div>
                    </div>
                `,
                
                showCloseButton: true,
                focusConfirm: false,
                confirmButtonText: 'Close',
                confirmButtonColor: '#888',
                customClass: {
                    popup: 'custom-swal-width',
                    actions: 'custom-actions-position',
                }
            });
        });
    });
});



document.addEventListener('DOMContentLoaded', function() {
    const emailItems = document.querySelectorAll('.sent-items');

    emailItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('.email-actions') && !e.target.closest('.checkbox')) {
                const receiverName = this.querySelector('.receiver').textContent.trim();
                const currentStatus = this.getAttribute('data-status');
                const message = this.getAttribute('data-message');
                const documentName = this.getAttribute('data-document-name');
                const status = (currentStatus == 'archiveNotif' || currentStatus == 'deleted' || currentStatus == 'restored') 
                   ? 'viewed' 
                   : (currentStatus == 'delivered' ? 'delivered' : currentStatus);
                const snippet = this.getAttribute('data-snippet');
                const fileUrl = this.getAttribute('data-file-url');

                Swal.fire({
                    html: `
                        <div style="display: flex; width: 100%; height: 100%; gap: 20px;">
                            
                            <iframe src="${fileUrl}" style="width: 100%; height: 700px; border: none;"></iframe>
                            
                            <div style="width: 50%; height: 220px; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Document Name:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">
                                    ${documentName}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">From:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">${receiverName}</p>
                                </div>
                                <div style="margin-bottom: 20px;">
                                    <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Message:</p>
                                    <p style="margin: 0; font-size: 20px; color: #555;">${status}</p>
                                </div>
                            </div>
                        </div>     
                    `,
                    showCloseButton: true,
                    confirmButtonText: 'Mark as viewed',
                    showCancelButton: true,
                    cancelButtonText: 'Close',
                    customClass: {
                        confirmButton: 'custom-confirm-button',
                        cancelButton: 'custom-cancel-button',
                        popup: 'custom-swal-width',
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
                                        document.querySelector(`[data-id="${forwardedDocumentId}"] .snippet`).style.fontWeight = 'normal';
                                        location.reload();
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