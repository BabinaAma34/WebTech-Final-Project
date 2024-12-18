// Function to fetch the total number of users (simulation)
function getTotalUsers() {
    // Simulating a request for total users (you can replace this with actual data fetch)
   

    // Update the count on the dashboard
    document.getElementById('totalUsers').textContent = totalUsers;
}

// Call the function when the page loads
window.onload = function() {
    getTotalUsers();
};
