<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-8 bg-white rounded-lg shadow-lg">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="py-4 flex justify-center items-center text-indigo-700">
                <div class="w-32">
                    <img src="<?= $brandLogo ?>" alt="Brand Logo">
                </div>
            </div>
            <!-- <h1 class="text-xl font-heading font-semibold text-[#054AA1]">Login</h1> -->
        </div>

        <!-- Error Message -->
        <div id="error_message" class="hidden mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-200 opacity-0 transition-opacity duration-500 ease-in-out">
            Invalid login credentials. Please try again.
        </div>

        <!-- Login Form -->
        <form id="login_form" method="POST">
            <div class="mb-4">
                <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email_address" name="email_address" required
                    class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 ease-in-out"
                    placeholder="Enter your email">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-all duration-200 ease-in-out"
                    placeholder="Enter your password">
            </div>

            <button id="login_button" type="submit"
                class="w-full bg-blue-800 text-white py-2 rounded-md hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 ease-in-out">
                <span id="login_text">Login</span>
            </button>
        </form>

        <!-- Additional Links -->
        <div class="mt-4 text-center">
            <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-800 underline">Forgot password?</a>
        </div>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Don't have an account? <a href="/signup"
                    class="text-blue-600 hover:text-blue-800 underline">Sign up</a></p>
        </div>
    </div>
</div>