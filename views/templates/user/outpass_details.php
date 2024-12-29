<div class="min-h-screen bg-gray-100">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-6 lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 border-b space-y-2 sm:space-y-0 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Outpass Details</h1>
                <p class="text-base text-gray-500 mt-1">View your outpass request details.</p>
            </div>
            <!-- Apply outpass button -->
            <div class="mt-4 md:mt-0">
                <button onclick="window.print()" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-md transition focus:outline-none focus:ring focus:ring-blue-300">
                    <i class="fa-solid fa-arrow-down mr-1"></i>
                    <span>Download Outpass</span>
                </button>
            </div>
        </header>
        
        <!-- Outpass Details Section -->
        <section class="bg-white rounded-xl shadow-md p-6 md:p-8 mb-8">
            <!-- Row 1: Dates and Times -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-clock text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">From Date & Time</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1">2024-08-10, 08:00AM</p>
                    </div>
                </div>
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-clock text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">To Date & Time</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1">2024-08-12, 08:00PM</p>
                    </div>
                </div>
            </div>

            <!-- Row 2: Pass Type and Destination -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-regular fa-id-card text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Pass Type</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1">Home</p>
                    </div>
                </div>
                <div class="flex items-center align-center space-x-4">
                    <i class="fa-solid fa-location-dot text-2xl text-gray-500"></i>
                    <div>
                        <label class="block text-base font-medium text-gray-500">Destination</label>
                        <p class="text-base md:text-lg text-gray-800 font-medium mt-1">Chennai</p>
                    </div>
                </div>
            </div>

            <!-- Split Columns: Status, Purpose, and QR Code -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Column 1: Status, Purpose, Approval Time, and Remarks -->
                <div class="space-y-8">
                    <div class="flex items-center align-center space-x-4">
                        <i class="fa-solid fa-info-circle text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Status</label>
                            <p class="text-base md:text-lg text-yellow-800 font-medium mt-1">Pending</p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fa-solid fa-question-circle text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Purpose</label>
                            <p class="text-lg text-gray-800 leading-relaxed mt-1">Hometown</p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fas fa-calendar-check text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Approval Time</label>
                            <p class="text-base md:text-lg text-gray-800 mt-1">2024-08-10, 10.00AM</p>
                        </div>
                    </div>
                    <div class="flex items-center align-center space-x-4">
                        <i class="fas fa-pencil-alt text-2xl text-gray-500"></i>
                        <div>
                            <label class="block text-base font-medium text-gray-500">Warden Remarks</label>
                            <p class="text-base md:text-lg text-gray-800 mt-1">None</p>
                        </div>
                    </div>
                </div>

                <!-- Column 2: QR Code and Download Button -->
                <div class="space-y-10">
                    <div class="bg-white rounded-lg border p-6 flex flex-col items-center">
                        <img class="w-48 h-48 object-contain" src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="Outpass QR Code">
                    </div>

                    <!-- QR Status and Download Button -->
                    <div class="flex justify-between items-center space-x-1">
                        <div class="flex items-center space-x-2">
                            <i class="fa-solid fa-check-circle text-gray-500 text-xl"></i>
                            <p class="text-sm md:text-base text-gray-600">
                                QR Validity: <span class="px-3 py-1 rounded-full text-md font-medium bg-green-100 text-green-800">Active</span>
                            </p>
                        </div>
                        <a href="#" onclick="window.print()" class="text-blue-600 hover:text-blue-700 hover:underline text-base">
                            <i class="fa-solid fa-download mr-1"></i>
                            Download QR
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
