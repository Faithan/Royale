document.getElementById('logout').addEventListener('click', function () {
    Swal.fire({
        title: 'Log Out',
        text: 'Are you sure you want to log out?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Log Out',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform log out action here
            window.location.href = '../logout.php';
            Swal.fire('Logged Out', 'You have been logged out successfully!', 'success');
        }
    });
});