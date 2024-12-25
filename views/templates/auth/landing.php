<!-- Navigation Bar -->
<header class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 flex justify-between items-center py-4">
        <!-- Brand -->
        <a href="/" class="flex items-center space-x-2">
            <svg class="w-8 h-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 92.105 92.1" fill="currentColor">
                <g transform="translate(-2.76 -2.77) scale(0.3254)" fill="currentColor">
                    <!-- Icon Content -->
                    <g xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M202.9,120.4L156,82.2c-3.5-2.9-8.6-2.9-12.1,0L97,120.5c-1.9,1.5-1.9,4.5,0,6l14.7,12c2.1,1.7,5.1,1.7,7.3,0l30.9-25.2l30.9,25.3c2.1,1.7,5.1,1.7,7.3,0l14.7-12C204.8,124.9,204.8,122,202.9,120.4z">
                        </path>
                        <path
                            d="M249.9,158.9l40.2-32.7c1.9-1.5,1.9-4.5,0-6L156,10.7c-3.5-2.9-8.6-2.9-12.1,0L9.9,120c-1.9,1.5-1.9,4.5,0,6l40.2,32.8L9.9,191.6c-1.9,1.5-1.9,4.5,0,6L124,290.7c2.5,2,6.2,0.3,6.2-3V193c0-2.9-1.3-5.7-3.6-7.5L94,158.9L72,141l-19-15.7c-1.4-1.1-1.4-3.3,0-4.5l93.4-76.2c2.1-1.7,5.1-1.7,7.3,0l93.4,76.2c1.4,1.1,1.4,3.3,0,4.5L228,140.9l-22.1,18l-32.6,26.6c-2.3,1.8-3.6,4.6-3.6,7.5v94.7c0,3.2,3.8,5,6.2,3L290,197.6c1.9-1.5,1.9-4.5,0-6L249.9,158.9zM73.9,178.3l26.7,21.8c1.3,1.1,2.2,2.8,2.2,4.5v28.8c0,1.6-1.9,2.6-3.1,1.5L53,196.8c-1.4-1.1-1.4-3.3,0-4.5l17.3-14.1C71.3,177.3,72.8,177.3,73.9,178.3zM197.3,233.4v-28.8c0-1.7,0.8-3.4,2.2-4.5l26.7-21.8c1-0.9,2.5-0.9,3.6,0l17.3,14.1c1.4,1.1,1.4,3.3,0,4.5L200.4,235C199.1,235.9,197.3,235.1,197.3,233.4z">
                        </path>
                    </g>
                </g>
            </svg>
            <span class="text-2xl font-heading font-medium text-gray-800">Passito</span>
        </a>

        <!-- Nav Links -->
        <div class="flex items-center space-x-6">
            <a href="/features" class="text-gray-600 hover:text-indigo-600 transition">Features</a>
            <a href="/pricing" class="text-gray-600 hover:text-indigo-600 transition">Pricing</a>
            <a href="<?= $this->urlFor('auth.login') ?>" class="text-gray-600 hover:text-indigo-600 transition">Login</a>
            <a href="<?= $this->urlFor('auth.login') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Sign Up</a>
        </div>
    </div>
</header>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-20">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 text-center">
        <h1 class="text-5xl font-bold mb-6">Instant, Seamless Gatepass Management</h1>
        <p class="text-lg mb-8">
            Eliminate paperwork, ensure secure approvals, and streamline operations with Passito.
        </p>
        <a href="/register" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold shadow-lg hover:bg-gray-100 transition">Get Started</a>
    </div>
</section>

<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-12">How Passito Works</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Step 1 -->
            <div class="text-center">
                <div class="bg-indigo-600 text-white w-16 h-16 mx-auto flex items-center justify-center rounded-full mb-4">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-2">Request Outpass</h3>
                <p class="text-gray-600">Students submit their outpass requests online through our intuitive app or web portal.</p>
            </div>
            <!-- Step 2 -->
            <div class="text-center">
                <div class="bg-indigo-600 text-white w-16 h-16 mx-auto flex items-center justify-center rounded-full mb-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-2">Approve/Verify</h3>
                <p class="text-gray-600">Admins or parents review and approve requests based on preset criteria.</p>
            </div>
            <!-- Step 3 -->
            <div class="text-center">
                <div class="bg-indigo-600 text-white w-16 h-16 mx-auto flex items-center justify-center rounded-full mb-4">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-2">Generate QR Code</h3>
                <p class="text-gray-600">Once approved, students receive a secure QR code for their outpass.</p>
            </div>
            <!-- Step 4 -->
            <div class="text-center">
                <div class="bg-indigo-600 text-white w-16 h-16 mx-auto flex items-center justify-center rounded-full mb-4">
                    <i class="fas fa-door-open text-2xl"></i>
                </div>
                <h3 class="font-bold text-xl text-gray-800 mb-2">Check-In/Out</h3>
                <p class="text-gray-600">Students scan their QR codes at gates for seamless check-ins and check-outs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-12">Why Choose Passito?</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-bolt text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Streamlined Processes</h3>
                <p class="text-gray-600">Effortlessly manage outpasses with instant approvals and automated workflows.</p>
            </div>
            <!-- Feature 2 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-user-shield text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Enhanced Security</h3>
                <p class="text-gray-600">Special provisions for female students include parent approval for added safety.</p>
            </div>
            <!-- Feature 3 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-mobile-alt text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Mobile Access</h3>
                <p class="text-gray-600">Students can apply for outpasses on the go with our dedicated mobile app.</p>
            </div>
            <!-- Feature 4 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-desktop text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Elegant Dashboard</h3>
                <p class="text-gray-600">Admins get a modern dashboard to manage requests efficiently and track activity.</p>
            </div>
            <!-- Feature 5 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-recycle text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Eco-Friendly</h3>
                <p class="text-gray-600">Eliminate paper usage with Passito’s digital-first approach to management.</p>
            </div>
            <!-- Feature 6 -->
            <div class="text-center bg-white p-8 rounded-lg shadow-md transform hover:scale-105 transition">
                <i class="fas fa-qrcode text-indigo-600 text-4xl mb-4"></i>
                <h3 class="font-bold text-xl mb-2">Automated Check-ins</h3>
                <p class="text-gray-600">Verify outpasses with trusted QR devices at gates for hassle-free entry/exit.</p>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action -->
<section class="bg-indigo-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10 text-center">
        <h2 class="text-3xl font-bold mb-6">Start Managing Your Outpasses Today</h2>
        <p class="text-lg mb-8">Join the growing number of organizations transforming their gatepass systems.</p>
        <a href="/register" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold shadow-lg hover:bg-gray-100 transition">Get Started for Free</a>
    </div>
</section>

<!-- Footer -->
<footer class="bg-gray-800 text-gray-400 py-8">
    <div class="max-w-7xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex flex-wrap justify-between items-center">
            <p class="text-sm">© <?= date("Y") ?> Passito. All rights reserved.</p>
            <div class="flex space-x-6">
                <a href="/privacy-policy" class="hover:text-white transition">Privacy Policy</a>
                <a href="/terms" class="hover:text-white transition">Terms of Service</a>
                <a href="/contact" class="hover:text-white transition">Contact Us</a>
            </div>
        </div>
    </div>
</footer>