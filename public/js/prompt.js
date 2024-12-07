const archiveButtons = document.querySelectorAll('.archive');

archiveButtons.forEach(archive => {
    archive.style.cursor = "pointer";
    archive.addEventListener('click', function () {
        const attr = archive.getAttribute('data-id');
        const status = archive.getAttribute('data-status'); // Get the current status
        
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to archive this item?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/archive_document/${attr}`, {
                    method: 'POST',
                    body: JSON.stringify({
                        status: status, // Send current status
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Archived', data.message, 'success').then(() => {
                            // Find and remove the document element by its data-id
                            const documentElement = document.querySelector(`[data-id="${attr}"]`).closest('.document');
                            if (documentElement) {
                                documentElement.remove(); // Remove the document item from the page
                            }
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                });
            }
        });
    });
});



const notifButtons = document.querySelectorAll('.notifForward');

notifButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('notif-id');
        const status = btn.getAttribute('status');
        
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${status === "deleted" ? "delete" : (status === "viewed" ? "restore" : status)} this item?`,            
            icon: status=="Archive"||status=="delivered"?'warning':'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/deleteNotif/${id}/${status}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`${capital(status).toLowerCase()=="viewed"?"Restored":capital(status)}!`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                }
               })
            }
        });
    });
});


const sentnotifButtons = document.querySelectorAll('.notifSent');

sentnotifButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('notif-id');
        const status = btn.getAttribute('status');
            
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${status === "deleted" ? "delete" : (status === "viewed" ? "restore" : status)} this item?`,            
            icon: status=="Archive"||status=="viewed"?'warning':'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/deleteNotifsent/${id}/${status}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`${capital(status).toLocaleLowerCase()=="viewed"?"Restored":capital(status)}`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                    }
                })
                // Additional logic for archiving can be added here
            }
        });
    });
});

const declinednotifButtons = document.querySelectorAll('.notifDeclined');

declinednotifButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('notif-id');
        const status = btn.getAttribute('status');
            
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${status === "deleted" ? "delete" : (status === "viewed" ? "restore" : status)} this item?`,            
            icon: status=="Archive"||status=="delivered"?'warning':'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/deleteNotifdeclined/${id}/${status}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`${capital(status).toLocaleLowerCase()=="viewed"?"Restored":capital(status)}`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                    }
                })
                // Additional logic for archiving can be added here
            }
        });
    });
});

const reqdeclinednotifButtons = document.querySelectorAll('.notifReqDeclined');

reqdeclinednotifButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('notif-id');
        const status = btn.getAttribute('status');
            
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${status === "deleted" ? "delete" : (status === "viewed" ? "restore" : status)} this item?`,            
            icon: status=="Archive"||status=="delivered"?'warning':'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/deleteNotifReqdeclined/${id}/${status}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`${capital(status).toLocaleLowerCase()=="viewed"?"Restored":capital(status)}`, data.message, 'success').then(() => {
                        window.location.reload(); 
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                    }
                })
            }
        });
    });
});

const deleteforButtons = document.querySelectorAll('.deleteForward');

deleteforButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('delete-id');
        const status = btn.getAttribute('status');
        
        Swal.fire({
            title: 'Are you sure?',
            text:  `Do you want to ${status=="viewed"?"Restored":status} this item?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/trash/${id}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`Deleted`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                }
               })
                // Additional logic for archiving can be added here
            }
        });
    });
});



const deletesentButtons = document.querySelectorAll('.deletesent');

deletesentButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('delete-id');
        const status = btn.getAttribute('status');
        
        Swal.fire({
            title: 'Are you sure?',
            text:  `Do you want to ${status=="viewed"?"Restored":status} this item?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/trash/sent/${id}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`Deleted`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                }
               })
                // Additional logic for archiving can be added here
            }
        });
    });
});

const deletedeclinedButtons = document.querySelectorAll('.deletedeclined');

deletedeclinedButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('delete-id');
        const status = btn.getAttribute('status');
        
        Swal.fire({
            title: 'Are you sure?',
            text:  `Do you want to ${status=="viewed"?"Restored":status} this item?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/trash/declined/${id}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`Deleted`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                }
               })
                // Additional logic for archiving can be added here
            }
        });
    });
});

const deleterequestedButtons = document.querySelectorAll('.deletedeclined');

deleterequestedButtons.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('delete-id');
        const status = btn.getAttribute('status');
        
        Swal.fire({
            title: 'Are you sure?',
            text:  `Do you want to ${status=="viewed"?"Restored":status} this item?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                   
               fetch(`/trash/requested/${id}`)
               .then(res=>res.json())
               .then(data=>{
                if(data.success){
                    Swal.fire(`Deleted`, data.message, 'success').then(() => {
                        // Optionally refresh or redirect
                        window.location.reload(); // Refresh the page
                    });
                }else{
                    Swal.fire('Error!', data.message, 'error');
                }
               })
                // Additional logic for archiving can be added here
            }
        });
    });
});

const restoredocs = document.querySelectorAll('.restore');

restoredocs.forEach(btn => {
    btn.style.cursor = "pointer";
    btn.addEventListener('click', function () {
        const id = btn.getAttribute('data-id');
        const originalStatus = btn.getAttribute('data-status'); 
        
        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to restore this item?`,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/restore/${id}`, {
                    method: 'POST',
                    body: JSON.stringify({
                        status: originalStatus,
                    }),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Restored', data.message, 'success').then(() => {
                            window.location.reload(); // Refresh the page
                        });
                    } else {
                        Swal.fire('Error!', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error!', 'An unexpected error occurred.', 'error');
                });
            }
        });
    });
});


function capital(text){
    return String(text).charAt(0).toUpperCase()+String(text).slice(1);
}