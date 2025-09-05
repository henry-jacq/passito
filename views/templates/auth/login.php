<!-- LOGIN TEMPLATE (Balanced for Desktop/Laptop) -->
<div class="flex items-center justify-center min-h-screen px-6 bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50">
    <div class="w-full max-w-md px-8 py-10 bg-white border border-gray-100 shadow-xl rounded-2xl">

        <!-- Logo + Title -->
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center mb-3 w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl ring-1 ring-blue-100">
                <img src="<?= $brandLogo ?>" alt="Brand Logo" class="object-contain w-9 h-9">
            </div>
            <h1 class="text-2xl font-semibold text-gray-900">Welcome Back</h1>
            <p class="mt-1 text-base text-gray-500">Sign in with your credentials</p>
        </div>

        <!-- Error Message -->
        <div id="error_message" class="hidden p-3 mb-3 text-sm font-medium text-red-800 transition-all duration-300 ease-in-out border border-red-200 rounded-md opacity-0 bg-red-50">
            <div class="flex items-center">
                <svg class="flex-shrink-0 w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <span>Invalid credentials. Try again.</span>
            </div>
        </div>

        <!-- Login Form -->
        <form id="login_form" method="POST" class="space-y-4">
            <div>
                <label for="email_address" class="block mb-1 text-sm font-medium text-gray-600">Email</label>
                <div class="relative">
                    <input type="email" id="email_address" name="email_address" required
                        class="w-full px-4 py-3 text-base transition border border-gray-200 rounded-lg pl-11 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-1 focus:border-blue-500 focus:bg-white"
                        placeholder="Enter your email" autocomplete="email">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-600">Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 text-base transition border border-gray-200 rounded-lg pl-11 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-1 focus:border-blue-500 focus:bg-white"
                        placeholder="Enter your password" autocomplete="current-password">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <button id="login_button" type="submit"
                class="flex items-center justify-center w-full px-5 py-3 text-base font-medium text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="login_text">Sign In</span>
                <svg id="loading_icon" class="hidden w-5 h-5 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <!-- Links -->
        <div class="mt-4 text-center">
            <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">Forgot password?</a>
        </div>

        <!-- Footer -->
        <div class="pt-6 mt-5 text-center border-t border-gray-100">
            <p class="text-sm font-medium text-gray-400">Students & Administrators • Outpass Management</p>
        </div>
    </div>
</div>


<!-- Enhanced JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Enhanced input focus effects
        const inputs = document.querySelectorAll('input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentNode.querySelector('svg').classList.add('text-blue-500');
                this.parentNode.querySelector('svg').classList.remove('text-gray-400');
            });

            input.addEventListener('blur', function() {
                this.parentNode.querySelector('svg').classList.remove('text-blue-500');
                this.parentNode.querySelector('svg').classList.add('text-gray-400');
            });
        });
    });
</script>