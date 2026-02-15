<div class="flex items-center justify-center min-h-screen px-6 bg-gradient-to-br from-slate-50 via-gray-50 to-blue-50">
    <div class="w-full max-w-md px-8 py-10 bg-white border border-gray-100 shadow-xl rounded-2xl">
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center mb-3 w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl ring-1 ring-blue-100">
                <img src="<?= $brandLogo ?>" alt="Brand Logo" class="object-contain w-9 h-9">
            </div>
            <h1 class="text-2xl font-semibold text-gray-900">Forgot your password?</h1>
            <p class="mt-1 text-base text-gray-500">We'll email you a reset link.</p>
        </div>

        <div id="forgot_message" class="hidden p-3 mb-3 text-sm font-medium transition-all duration-300 ease-in-out border rounded-md">
            <span id="forgot_message_text"></span>
        </div>

        <form id="forgot_password_form" method="POST" class="space-y-4">
            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
            <div>
                <label for="forgot_email" class="block mb-1 text-sm font-medium text-gray-600">Email</label>
                <div class="relative">
                    <input type="email" id="forgot_email" name="email" required
                        class="w-full px-4 py-3 text-base transition border border-gray-200 rounded-lg pl-11 bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:ring-offset-1 focus:border-blue-500 focus:bg-white"
                        placeholder="Enter your email" autocomplete="email">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <button id="forgot_button" type="submit"
                class="flex items-center justify-center w-full px-5 py-3 text-base font-medium text-white bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="forgot_text">Send reset link</span>
                <svg id="forgot_loading" class="hidden w-5 h-5 ml-2 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
        </form>

        <div class="mt-4 text-center">
            <a href="<?= $this->urlFor('auth.login') ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline">Back to sign in</a>
        </div>
    </div>
</div>

