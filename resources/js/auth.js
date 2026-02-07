import Ajax from "./libs/ajax";
import Auth from "./libs/auth";

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('login_form');
    const email = document.getElementById('email_address');
    const password = document.getElementById('password');
    const errorMessage = document.getElementById('error_message');
    const loginButton = document.getElementById('login_button');
    const loginText = document.getElementById('login_text');
    let isLoginSuccessful = false;
    let didRetryCsrf = false;

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
                if (response.data?.token) {
                    Auth.setToken(response.data.token);
                }
                setTimeout(() => {
                    window.location = response.data.redirect;
                }, 1000);
            } else {
                if (response.status === 403 && !didRetryCsrf) {
                    didRetryCsrf = true;
                    const retryResponse = await Ajax.post('/api/web/auth/login', {
                        email: email.value,
                        password: password.value,
                    });

                    if (retryResponse.ok) {
                        loginText.textContent = 'Logging in...';
                        isLoginSuccessful = true;
                        if (retryResponse.data?.token) {
                            Auth.setToken(retryResponse.data.token);
                        }
                        setTimeout(() => {
                            window.location = retryResponse.data.redirect;
                        }, 1000);
                        return;
                    }

                    handleError(retryResponse.status);
                } else {
                    handleError(response.status);
                }
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

document.addEventListener('DOMContentLoaded', function () {
    const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
    inputs.forEach((input) => {
        input.addEventListener('focus', function () {
            const icon = this.parentNode?.querySelector('svg');
            if (!icon) return;
            icon.classList.add('text-blue-500');
            icon.classList.remove('text-gray-400');
        });

        input.addEventListener('blur', function () {
            const icon = this.parentNode?.querySelector('svg');
            if (!icon) return;
            icon.classList.remove('text-blue-500');
            icon.classList.add('text-gray-400');
        });
    });
});
