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
            const documentName = this.querySelector('.document-name').textContent.trim();
            const receiverName = this.querySelector('.receiver').textContent.trim();
            const status = this.getAttribute('data-status'); 
            const fileUrl = this.getAttribute('data-file-url');

            // Use SweetAlert2 to display the document details
            Swal.fire({
                html: `
                    <div style="display: flex; width: 100%; height: 100%; gap: 20px;">

                        <iframe src="${fileUrl}" style="width: 100%; height: 700px; border: none;"></iframe>
                        
                        <div style="width: 50%; height: 188px; text-align: left; display: flex; flex-direction: column; justify-content: center;">
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">Document Name:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${documentName}</p>
                            </div>
                            <div style="margin-bottom: 20px;">
                                <p style="margin: 0; font-size: 15px; font-weight: bold; color: #888;">To:</p>
                                <p style="margin: 0; font-size: 20px; color: #555;">${receiverName}</p>
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



