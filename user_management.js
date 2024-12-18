document.addEventListener('DOMContentLoaded', function () {
    // Handle button clicks for approving, rejecting, and setting as pending
    setupButtonListeners();
});

function setupButtonListeners() {
    // Approve button
    document.querySelectorAll('.approve-btn').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            // console.log("button clicks");
            approveUser(userId);
            
        });
    });

    // Reject button
    document.querySelectorAll('.reject-btn').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            rejectUser(userId);
        });
    });

    // Pending button
    document.querySelectorAll('.pending-btn').forEach(button => {
        button.addEventListener('click', () => {
            const userId = button.dataset.userId;
            UserPending(userId);
        });
    });
}

// Function to approve user
function approveUser(userId) {
    // console.log("user approved");
    if (confirm('Are you sure you want to approve this user?')) {
        changeStatus(userId, 'approved');
    }
}

// Function to reject user
function rejectUser(userId) {
    if (confirm('Are you sure you want to reject this user?')) {
        changeStatus(userId, 'rejected');
    }
}

// Function to set user status as pending
function UserPending(userId) {
    if (confirm('Are you sure you want to set this user as pending?')) {
        changeStatus(userId, 'pending');
    }
}

// Function to change user status in the database
function changeStatus(userId, status) {
    fetch('update_status.php', { // Call the PHP script to update the status
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${userId}&status=${status}` // Send user ID and status
    })
    .then(response => response.text())
    .then(data => {
        if (data === 'success') {
            alert(`User status updated to: ${status}`);
            document.getElementById(`status-${userId}`).innerText = status; // Update status in the DOM
        } else {
            alert('Unable to update user status.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Unable to update user status.');
    });
}
