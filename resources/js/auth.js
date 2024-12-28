import Ajax from "./libs/ajax";

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login_form');
    const email = document.getElementById('email_address');
    const password = document.getElementById('password');
    const errorMessage = document.getElementById('error_message');
    const loginButton = document.getElementById('login_button');
    const loginText = document.getElementById('login_text');
    let isLoginSuccessful = false;

    form.addEventListener('submit', async function (event) {
        event.preventDefault();
        errorMessage.textContent = '';
        errorMessage.classList.add('opacity-0', 'hidden');
        errorMessage.classList.remove('opacity-100');
        loginText.textContent = 'Verifying Credentials...';
        loginButton.disabled = true;

        try {
            const response = await Ajax.post('/api/web/auth/login', {
                email: email.value,
                password: password.value,
            });

            if (response.ok) {
                // console.log('Login successful:', response.data);
                loginText.textContent = 'Logging in...';
                isLoginSuccessful = true;
                setTimeout(() => {
                    window.location = response.data.redirect;
                }, 1000);
            } else {
                handleError(response.status);
            }
        } catch (error) {
            console.error('Unexpected error:', error.message);
            errorMessage.textContent = 'An unexpected error occurred. Please try again later.';
            errorMessage.classList.remove('hidden');
            errorMessage.classList.add('opacity-100');
        } finally {
            if (!isLoginSuccessful) {
                loginText.textContent = 'Login';
                loginButton.disabled = false;
            }
        }
    });

    function handleError(status) {
        const messages = {
            400: 'Invalid request. Please check your input.',
            401: 'Invalid credentials. Please try again.',
            403: 'Access denied. Please contact support.',
            404: 'The login API is not available.',
            default: 'An unexpected error occurred. Please try again later.',
        };
        errorMessage.textContent = messages[status] || messages.default;
        errorMessage.classList.remove('hidden');
        errorMessage.classList.add('opacity-100');
    }
});
