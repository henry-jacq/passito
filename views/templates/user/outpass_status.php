<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto p-8 space-y-12">
        <!-- Page Title -->
        <header class="flex justify-between items-center pb-6 border-b border-gray-200">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Outpass Status</h1>
                <p class="text-base text-gray-500 mt-1">Track your current outpass and recent activities seamlessly.</p>
            </div>
        </header>

        <!-- Current Outpass Status Section -->
        <section class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Current Outpass Details</h2>
            <div class="border rounded-xl p-6 bg-gradient-to-br from-gray-50 to-white">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Outpass ID:</span> 56789</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Type:</span> Short Leave</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Purpose:</span> Attend Wedding</p>
                    </div>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Dates:</span> 25 Dec, 2024 - 26 Dec, 2024</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Time:</span> 9:00 AM - 8:00 PM</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-800">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-200 text-yellow-800 shadow-sm">Pending</span>
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Outpasses Section -->
        <section class="bg-white rounded-2xl shadow-md p-8">
            <h2 class="text-xl font-semibold text-gray-700 mb-6">Recent Outpasses</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <!-- Example Recent Outpass Card -->
                <div class="border rounded-xl p-6 bg-gray-100 hover:shadow-lg transition-shadow duration-300 ease-in-out">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Outpass #56788</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Type:</span> Outstation</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Purpose:</span> Family Trip</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Dates:</span> 20 Dec, 2024 - 21 Dec, 2024</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-800">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-200 text-green-800 shadow-sm">Approved</span>
                        </p>
                    </div>
                </div>

                <div class="border rounded-xl p-6 bg-gray-100 hover:shadow-lg transition-shadow duration-300 ease-in-out">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Outpass #56787</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Type:</span> Short Leave</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Purpose:</span> Doctor's Appointment</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Dates:</span> 18 Dec, 2024</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-800">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-200 text-red-800 shadow-sm">Rejected</span>
                        </p>
                    </div>
                </div>

                <div class="border rounded-xl p-6 bg-gray-100 hover:shadow-lg transition-shadow duration-300 ease-in-out">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Outpass #56786</h3>
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Type:</span> Short Leave</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Purpose:</span> Personal Errand</p>
                        <p class="text-sm text-gray-600"><span class="font-semibold text-gray-800">Dates:</span> 15 Dec, 2024</p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-gray-800">Status:</span>
                            <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-200 text-green-800 shadow-sm">Approved</span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-8 text-right">
                <a href="<?= $this->urlFor('student.outpass.history') ?>" class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                    View All History &rarr;
                </a>
            </div>
        </section>
    </main>
</div>
