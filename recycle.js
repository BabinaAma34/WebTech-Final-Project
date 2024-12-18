document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('waste-pickup-form');
    const pickupTimeInput = document.getElementById('pickup-time');

    // Function to set the minimum date and time to the current time
    function setMinDateTime() {
        const now = new Date();
        const localISOTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
            .toISOString()
            .slice(0, 16);
        pickupTimeInput.setAttribute("min", localISOTime);
    }

    // Set the minimum date and time when the page loads
    setMinDateTime();

    // Update the minimum time whenever the user focuses on the input
    pickupTimeInput.addEventListener("focus", setMinDateTime);

    // Handle form submission with AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);

        // Remove any existing response message
        const existingMessage = document.getElementById('response-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create a response container
        const responseContainer = document.createElement('div');
        responseContainer.id = 'response-message';

        // Fetch request to process form data
        fetch('recycle.php', { // Ensure this matches your PHP script filename
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Set response message text and style
            responseContainer.textContent = data.message;
            responseContainer.className = data.status === 'success' ? 'success' : 'error';

            // Insert the response message below the form
            form.parentNode.insertBefore(responseContainer, form.nextSibling);

            // Reset form on success
            if (data.status === 'success') {
                form.reset();
                setMinDateTime();
                window.location.href = '../php_files/recycle.php';
                 // Reset the pickup time input validation
            }
        })
        .catch(error => {
            // Handle errors
            responseContainer.textContent = 'An unexpected error occurred. Please try again.';
            responseContainer.className = 'error';
            form.parentNode.insertBefore(responseContainer, form.nextSibling);
            console.error('Error:', error);
        });
    });
});
