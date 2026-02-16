import Ajax from "./libs/ajax";
import Auth from "./libs/auth";

document.addEventListener('DOMContentLoaded', function () {
    // Login/forgot/reset pages are unauthenticated contexts; clear stale client token.
    Auth.clearToken();

    const setAlert = (el, text, type) => {
        if (!el) return;
        const textEl = el.querySelector('span') || el;
        if (textEl) textEl.textContent = text;

        el.classList.remove('hidden');
        el.classList.remove('opacity-0');
        el.classList.add('opacity-100');

        // Reset styles
        el.classList.remove('bg-red-50', 'border-red-200', 'text-red-800');
        el.classList.remove('bg-green-50', 'border-green-200', 'text-green-800');

        if (type === 'success') {
            el.classList.add('bg-green-50', 'border-green-200', 'text-green-800');
        } else if (type === 'error') {
            el.classList.add('bg-red-50', 'border-red-200', 'text-red-800');
        }
    };

    // Login page
    const loginForm = document.getElementById('login_form');
    if (loginForm) {
        const returnUrl = new URLSearchParams(window.location.search).get('returnUrl') || '';
        const email = document.getElementById('email_address');
        const password = document.getElementById('password');
        const errorMessage = document.getElementById('error_message');
        const loginButton = document.getElementById('login_button');
        const loginText = document.getElementById('login_text');
        let isLoginSuccessful = false;
        let didRetryCsrf = false;

        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (errorMessage) {
                errorMessage.textContent = '';
                errorMessage.classList.add('opacity-0', 'hidden');
                errorMessage.classList.remove('opacity-100');
            }

            if (loginText) loginText.textContent = 'Verifying Credentials...';
            if (loginButton) loginButton.disabled = true;

            try {
                const response = await Ajax.post('/api/web/auth/login', {
                    email: email?.value || '',
                    password: password?.value || '',
                    return_url: returnUrl,
                });

                if (response.ok) {
                    if (loginText) loginText.textContent = 'Logging in...';
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
                            email: email?.value || '',
                            password: password?.value || '',
                            return_url: returnUrl,
                        });

                        if (retryResponse.ok) {
                            if (loginText) loginText.textContent = 'Logging in...';
                            isLoginSuccessful = true;
                            if (retryResponse.data?.token) {
                                Auth.setToken(retryResponse.data.token);
                            }
                            setTimeout(() => {
                                window.location = retryResponse.data.redirect;
                            }, 1000);
                            return;
                        }

                        handleLoginError(retryResponse.status);
                    } else {
                        handleLoginError(response.status);
                    }
                }
            } catch (error) {
                console.error('Unexpected error:', error.message);
                if (errorMessage) {
                    errorMessage.textContent = 'An unexpected error occurred. Please try again later.';
                    errorMessage.classList.remove('hidden');
                    errorMessage.classList.add('opacity-100');
                }
            } finally {
                if (!isLoginSuccessful) {
                    if (loginText) loginText.textContent = 'Login';
                    if (loginButton) loginButton.disabled = false;
                }
            }
        });

        function handleLoginError(status) {
            const messages = {
                400: 'Invalid request. Please check your input.',
                401: 'Invalid credentials. Please try again.',
                403: 'Access denied. Please contact support.',
                404: 'The login API is not available.',
                default: 'An unexpected error occurred. Please try again later.',
            };
            if (errorMessage) {
                errorMessage.textContent = messages[status] || messages.default;
                errorMessage.classList.remove('hidden');
                errorMessage.classList.add('opacity-100');
            }
        }
    }

    // Forgot password page
    const forgotForm = document.getElementById('forgot_password_form');
    if (forgotForm) {
        const email = document.getElementById('forgot_email');
        const button = document.getElementById('forgot_button');
        const text = document.getElementById('forgot_text');
        const loading = document.getElementById('forgot_loading');
        const messageBox = document.getElementById('forgot_message');
        const messageText = document.getElementById('forgot_message_text');

        forgotForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (button) button.disabled = true;
            if (text) text.textContent = 'Sending...';
            if (loading) loading.classList.remove('hidden');
            if (messageBox) messageBox.classList.add('hidden');

            try {
                const response = await Ajax.post('/api/web/auth/password/forgot', {
                    email: email?.value || '',
                });

                if (response.ok) {
                    if (messageText) messageText.textContent = response.data?.message || 'Check your email for a reset link.';
                    setAlert(messageBox, messageText?.textContent || '', 'success');
                } else {
                    const msg = response.data?.message || 'Unable to send reset link. Please try again.';
                    if (messageText) messageText.textContent = msg;
                    setAlert(messageBox, msg, 'error');
                }
            } catch (error) {
                const msg = 'An unexpected error occurred. Please try again later.';
                if (messageText) messageText.textContent = msg;
                setAlert(messageBox, msg, 'error');
            } finally {
                if (loading) loading.classList.add('hidden');
                if (text) text.textContent = 'Send reset link';
                if (button) button.disabled = false;
            }
        });
    }

    // Reset password page
    const resetForm = document.getElementById('reset_password_form');
    if (resetForm) {
        const password = document.getElementById('new_password');
        const confirm = document.getElementById('confirm_password');
        const button = document.getElementById('reset_button');
        const text = document.getElementById('reset_text');
        const loading = document.getElementById('reset_loading');
        const messageBox = document.getElementById('reset_message');
        const messageText = document.getElementById('reset_message_text');

        const tokenFromAttr = resetForm.getAttribute('data-token') || '';
        const tokenFromUrl = new URLSearchParams(window.location.search).get('token') || '';
        const token = tokenFromAttr || tokenFromUrl;

        resetForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (button) button.disabled = true;
            if (text) text.textContent = 'Updating...';
            if (loading) loading.classList.remove('hidden');
            if (messageBox) messageBox.classList.add('hidden');

            try {
                const response = await Ajax.post('/api/web/auth/password/reset', {
                    token,
                    password: password?.value || '',
                    password_confirmation: confirm?.value || '',
                });

                if (response.ok) {
                    const msg = response.data?.message || 'Password updated. Redirecting...';
                    if (messageText) messageText.textContent = msg;
                    setAlert(messageBox, msg, 'success');
                    setTimeout(() => {
                        window.location = '/auth/login';
                    }, 1200);
                } else {
                    const msg = response.data?.message || 'Unable to reset password. Please request a new link.';
                    if (messageText) messageText.textContent = msg;
                    setAlert(messageBox, msg, 'error');
                }
            } catch (error) {
                const msg = 'An unexpected error occurred. Please try again later.';
                if (messageText) messageText.textContent = msg;
                setAlert(messageBox, msg, 'error');
            } finally {
                if (loading) loading.classList.add('hidden');
                if (text) text.textContent = 'Update password';
                if (button) button.disabled = false;
            }
        });
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
