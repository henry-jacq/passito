<!-- Main Container -->
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <!-- Setup Box -->
    <div id="setup-container" class="bg-white rounded-2xl shadow-xl p-10 md:p-16">
        <!-- Welcome Screen -->
        <div id="welcome-screen" class="text-center space-y-14 max-w-3xl">
            <!-- Logo and Title -->
            <div class="flex justify-center items-center space-x-3">
                <svg class="w-9 h-9 text-gray-800" viewBox="0 0 92.105 92.1">
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
                <span class="font-heading font-medium text-3xl text-gray-800">Passito</span>
            </div>
            <div class="space-y-6">
                <h1 class="text-2xl md:text-3xl font-medium text-gray-800">Welcome to the Setup Wizard</h1>
                <p class="text-gray-500 text-sm md:text-md text-start">
                    We're excited to have you on board! This setup wizard will guide you through a few simple steps to
                    configure
                    your application and get everything up and running in no time.
                </p>
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md text-start">
                    <p class="text-blue-700 text-sm md:text-md">
                        Make sure you have the necessary details like institution information and user roles ready. If
                        you need
                        assistance, check out our <a href="#" class="text-blue-600 underline">documentation</a> or
                        contact
                        <a href="#" class="text-blue-600 underline">support</a>.
                    </p>
                </div>
            </div>

            <!-- Start Button -->
            <div>
                <button onclick="startSetup()"
                    class="px-8 py-3 bg-blue-600 text-white text-lg font-medium rounded-3xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition duration-300 ease-in-out">
                    Get Started
                    <i class="fa-solid fa-arrow-right ms-2"></i>
                </button>
            </div>
        </div>

        <!-- Setup Steps -->
        <div id="setup-steps" class="hidden flex justify-center items-center w-full h-full">
            <div class="max-w-3xl w-full mx-auto space-y-10">
                <!-- Progress Bar -->
                <div class="relative mb-8">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span id="progress-start" class="w-4 h-4 bg-gray-300 rounded-full"></span>
                        </div>
                        <div class="flex-1 h-2 bg-gray-300 rounded-full mx-2">
                            <div id="progress-bar" class="h-2 bg-blue-600 rounded-full transition-all"
                                style="width: 0%;"></div>
                        </div>
                        <div class="flex items-center">
                            <span id="progress-end" class="w-4 h-4 bg-gray-300 rounded-full"></span>
                        </div>
                    </div>
                    <div class="absolute inset-0 flex justify-between items-center text-sm text-gray-600">
                        <span id="step1-icon" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        <span id="step2-icon" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        <span id="step3-icon" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        <span id="step4-icon" class="w-8 h-8 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-sm font-bold">4</span>
                    </div>
                </div>

                <!-- Step Content -->
                <div id="step-content">
                    <!-- Step 1: Create Chief Warden -->
                    <div id="step1" class="hidden space-y-4">
                        <h2 class="text-3xl font-medium text-gray-800">1. Create Chief Warden</h2>
                        <p class="text-gray-500 text-sm">Enter the details for the first chief warden of the
                            application.</p>
                        <form onsubmit="goToStep(2); return false;" class="space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <input type="email" placeholder="Email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <input type="password" placeholder="Password" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <div class="grid grid-cols-2 gap-6">
                                <input type="text" placeholder="Full Name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                <select
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                    <option disabled selected>Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg shadow-lg hover:bg-blue-700 transition duration-300">
                                Next
                            </button>
                        </form>
                    </div>

                    <!-- Step 2: Create Institution -->
                    <div id="step2" class="hidden space-y-4">
                        <h2 class="text-3xl font-medium text-gray-800">2. Create Institution</h2>
                        <p class="text-gray-500 text-sm">Enter the institution details to proceed.</p>
                        <form onsubmit="goToStep(3); return false;" class="space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <input type="text" placeholder="Institution Name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <input type="text" placeholder="Institution Address" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                <option value="college">College</option>
                                <option value="university">University</option>
                            </select>
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                Next
                            </button>
                        </form>
                    </div>

                    <!-- Step 3: Create Wardens -->
                    <div id="step3" class="hidden space-y-4">
                        <h2 class="text-3xl font-medium text-gray-800">3. Create Wardens</h2>
                        <p class="text-gray-500 text-sm">Add wardens and their details.</p>
                        <form onsubmit="goToStep(4); return false;" class="space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <input type="text" placeholder="Warden Name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <input type="email" placeholder="Warden Email" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <input type="text" placeholder="Phone Number" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                Next
                            </button>
                        </form>
                    </div>

                    <!-- Step 4: Create Hostels -->
                    <div id="step4" class="hidden space-y-4">
                        <h2 class="text-3xl font-medium text-gray-800">4. Create Hostels</h2>
                        <p class="text-gray-500 text-sm">Add hostel details and assign wardens.</p>
                        <form onsubmit="finishSetup(); return false;" class="space-y-6">
                            <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                            <input type="text" placeholder="Hostel Name" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                <option value="" disabled selected>Hostel Type</option>
                                <option value="gents">Gents Hostel</option>
                                <option value="ladies">Ladies Hostel</option>
                            </select>
                            <select class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 overflow-hidden">
                                <option value="" disabled selected>Assign Warden</option>
                                <option value="warden1">Warden 1</option>
                                <option value="warden2">Warden 2</option>
                            </select>
                            <button type="submit" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-300">
                                Finish Setup
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    function startSetup() {
        document.getElementById('welcome-screen').classList.add('hidden');
        document.getElementById('setup-steps').classList.remove('hidden');
        goToStep(1);
    }

    function goToStep(step) {
        const steps = [1, 2, 3, 4];
        steps.forEach((s) => {
            document.getElementById(`step${s}`).classList.toggle('hidden', s !== step);
            const stepIcon = document.getElementById(`step${s}-icon`);
            stepIcon.classList.toggle('bg-blue-600', s <= step);
            stepIcon.classList.toggle('text-white', s <= step);
            stepIcon.classList.toggle('bg-gray-300', s > step);
            stepIcon.classList.toggle('text-gray-600', s > step);
        });

        const progressBar = document.getElementById('progress-bar');
        if (progressBar) {
            progressBar.style.width = `${((step - 1) / (steps.length - 1)) * 100}%`;
        }
    }

    function finishSetup() {
        alert('Setup Complete! Redirecting to <?= $this->urlFor('auth.login') ?>...');
        window.location.href = '<?= $this->urlFor('auth.login') ?>';
    }
</script>
