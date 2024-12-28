<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-sm p-8 bg-white rounded-lg shadow-lg">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="py-4 flex justify-center items-center text-indigo-600">
                <svg class="w-12 h-12" viewBox="0 0 92.105 92.1">
                    <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                        <g xmlns="http://www.w3.org/2000/svg">
                            <path d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z"></path>
                            <path d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z"></path>
                        </g>
                    </g>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Passito Login</h1>
        </div>

        <!-- Error Message -->
        <div id="error_message" class="hidden mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm border border-red-200 opacity-0 transition-opacity duration-500 ease-in-out">
            Invalid login credentials. Please try again.
        </div>

        <!-- Login Form -->
        <form id="login_form" action="/login" method="POST">
            <div class="mb-4">
                <label for="email_address" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input type="email" id="email_address" name="email_address" required
                    class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter your email">
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 mt-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Enter your password">
            </div>

            <button id="login_button" type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="login_text">Login</span>
            </button>
        </form>

        <!-- Additional Links -->
        <div class="mt-4 text-center">
            <a href="/forgot-password" class="text-sm text-indigo-600 hover:text-indigo-800 underline">Forgot password?</a>
        </div>
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">Don't have an account? <a href="/signup"
                    class="text-indigo-600 hover:text-indigo-800 underline">Sign up</a></p>
        </div>
    </div>
</div>
