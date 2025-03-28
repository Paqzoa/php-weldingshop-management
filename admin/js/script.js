document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.querySelector('#registration-form');
    const errorMessage = document.querySelector('#error-message');

    registrationForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent form submission

        if (validateRegistrationForm()) {
            // Form is valid, send the request to the backend
            const formData = new FormData(registrationForm);

            fetch('register_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Registration successful, redirect to login or a success page
                    window.location.href = 'login.php'; // Change this to your desired page
                } else {
                    // Registration failed, display error message
                    errorMessage.textContent = data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    });

    function validateRegistrationForm() {
        const username = registrationForm.querySelector('#username').value;
        const email = registrationForm.querySelector('#email').value;
        const password = registrationForm.querySelector('#password').value;
        const confirm_password = registrationForm.querySelector('#confirm-password').value;

        if (username.trim() === '' || email.trim() === '' || password.trim() === '') {
            errorMessage.textContent = 'Please fill in all fields.';
            return false;
        }

        // Add more validation rules here if needed
        // For example, you can check if the email is in a valid format

        return true;
    }
});


function togglePasswordVisibility() {
    var passwordInputs = document.querySelectorAll('.toggle-password-input');
    var icon = document.querySelector('.toggle-password-icon');

    passwordInputs.forEach(function (passwordInput) {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
        } else {
            passwordInput.type = "password";
        }
    });

    // Toggle the icon
    if (icon.classList.contains('fa-eye-slash')) {
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}






