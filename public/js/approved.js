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


// Decline Button
document.querySelectorAll('.decline-btn').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();

        const documentId = this.dataset.documentId;
        const formAction = `{{ url('admin/documents/decline') }}/${documentId}`;

        // Set the action for the form
        const declineForm = document.getElementById('decline-form');
        declineForm.action = formAction;

        // Show the modal
        $('#remarkModal').modal('show');
    });
});
