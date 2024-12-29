<div class="flex flex-col items-center justify-center min-h-screen bg-gradient-to-r from-gray-100 via-gray-200 to-gray-300 p-6">
    <div class="max-w-3xl w-full bg-white rounded-lg shadow-2xl p-8">
        <h1 class="text-4xl font-semibold text-center text-gray-800 mb-6">Application Setup</h1>
        <p class="text-lg text-center text-gray-600 mb-8">Welcome, Super Admin! Please complete the application setup to get started.</p>
        
        <!-- Progress Bar -->
        <div class="w-full mb-8">
            <div class="flex justify-between mb-4">
                <span id="setup-institution-heading" class="text-sm font-semibold text-blue-600">Setup Institution</span>
                <span id="setup-hostel-heading" class="text-sm font-semibold text-gray-400">Setup Hostels</span>
                <span id="setup-warden-heading" class="text-sm font-semibold text-gray-400">Setup Wardens</span>
                <span id="setup-finish-heading" class="text-sm font-semibold text-gray-400">Finish</span>
            </div>
            <div class="relative w-full h-2 bg-gray-300 rounded-full">
                <div class="absolute h-full bg-blue-500 rounded-full transition-all duration-500" style="width: 10%;"></div>
            </div>
        </div>

        <!-- Multi-step Form -->
        <div class="space-y-8">
            <!-- Step 1: Institution Setup -->
            <div class="step step-1">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Step 1: Institution Setup</h3>
                <form id="institutionForm">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="mb-4">
                            <label for="institutionName" class="block text-gray-700 text-sm font-medium mb-2">Institution Name</label>
                            <input type="text" id="institutionName" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" placeholder="Enter institution name" required>
                        </div>
                        <div class="mb-4">
                            <label for="institutionAddress" class="block text-gray-700 text-sm font-medium mb-2">Institution Address</label>
                            <input type="text" id="institutionAddress" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" placeholder="Enter institution address" required>
                        </div>
                        <div class="mb-4">
                            <label for="institutionType" class="block text-gray-700 text-sm font-medium mb-2">Institution Type</label>
                            <select id="institutionType" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" required>
                                <option value="college">College</option>
                                <option value="university">University</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" id="nextStep1" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all duration-300 ease-in-out">
                            Next Step
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Step 2: Hostels Setup -->
            <div class="step step-2 hidden">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Step 2: Hostels Setup</h3>
                <form id="hostelForm">
                    <div class="space-y-6">
                        <div class="mb-4">
                            <label for="hostelName" class="block text-gray-700 text-sm font-medium mb-2">Hostel Name</label>
                            <input type="text" id="hostelName" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" placeholder="Enter hostel name" required>
                        </div>
                        <div class="mb-4">
                            <label for="hostelType" class="block text-gray-700 text-sm font-medium mb-2">Hostel Type</label>
                            <select id="hostelType" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" required>
                                <option value="gents">Gents</option>
                                <option value="ladies">Ladies</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" id="prevStep2" class="bg-gray-300 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">Previous</button>
                        <button type="button" id="nextStep2" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Next Step</button>
                    </div>
                </form>
            </div>

            <!-- Step 3: Wardens Setup -->
            <div class="step step-3 hidden">
                <h3 class="text-2xl font-semibold text-gray-800 mb-4">Step 3: Wardens Setup</h3>
                <form id="wardenForm">
                    <div class="space-y-6">
                        <div class="mb-4">
                            <label for="wardenName" class="block text-gray-700 text-sm font-medium mb-2">Warden Name</label>
                            <input type="text" id="wardenName" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" placeholder="Enter warden's name" required>
                        </div>
                        <div class="mb-4">
                            <label for="wardenPhone" class="block text-gray-700 text-sm font-medium mb-2">Warden Phone Number</label>
                            <input type="tel" id="wardenPhone" class="block w-full p-3 border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500" placeholder="Enter warden's phone number" required>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" id="prevStep3" class="bg-gray-300 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-500">Previous</button>
                        <button type="button" id="nextStep3" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">Complete Setup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Smooth transitions for each step -->
<script>
    // Step 1 to Step 2
    document.getElementById('nextStep1').addEventListener('click', function() {
        document.querySelector('.step-1').classList.add('hidden');
        document.querySelector('#setup-hostel-heading').classList.add('text-blue-600');
        document.querySelector('#setup-hostel-heading').classList.remove('text-gray-400');
        document.querySelector('.step-2').classList.remove('hidden');
        document.querySelector('.relative div').style.width = '40%';
    });

    // Step 2 to Step 1
    document.getElementById('prevStep2').addEventListener('click', function() {
        document.querySelector('.step-2').classList.add('hidden');
        document.querySelector('#setup-hostel-heading').classList.remove('text-blue-600');
        document.querySelector('#setup-hostel-heading').classList.add('text-gray-400');
        document.querySelector('.step-1').classList.remove('hidden');
        document.querySelector('.relative div').style.width = '10%';
    });

    // Step 2 to Step 3
    document.getElementById('nextStep2').addEventListener('click', function() {
        document.querySelector('.step-2').classList.add('hidden');
        document.querySelector('.step-3').classList.remove('hidden');
        document.querySelector('#setup-warden-heading').classList.add('text-blue-600');
        document.querySelector('#setup-warden-heading').classList.remove('text-gray-400');
        document.querySelector('.relative div').style.width = '70%';
    });

    // Step 3 to Step 2
    document.getElementById('prevStep3').addEventListener('click', function() {
        document.querySelector('.step-3').classList.add('hidden');
        document.querySelector('.step-2').classList.remove('hidden');
        document.querySelector('#setup-warden-heading').classList.remove('text-blue-600');
        document.querySelector('#setup-warden-heading').classList.add('text-gray-400');
        document.querySelector('.relative div').style.width = '40%';
    });

    // Step 3 to Step 4
    document.getElementById('nextStep3').addEventListener('click', function() {
        document.querySelector('.step-3').classList.add('hidden');
        document.querySelector('#setup-finish-heading').classList.add('text-blue-600');
        document.querySelector('#setup-finish-heading').classList.remove('text-gray-400');
        document.querySelector('.relative div').style.width = '100%';
    });
</script>
