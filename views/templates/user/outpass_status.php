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
                <h1 class="text-3xl font-bold text-gray-800">Outpass Status</h1>
                <p class="text-base text-gray-500 mt-1">View your current and recent outpass status.</p>
            </div>
            <!-- Apply outpass button -->
            <div class="mt-4 md:mt-0">
                <button onclick="window.location.href='<?= $this->urlFor('student.outpass.request') ?>'" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700 shadow-md transition focus:outline-none focus:ring focus:ring-blue-300">
                    Apply for New Outpass
                </a>
            </div>
        </header>

        <!-- Current Outpass Section -->
        <section class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Current Status</h2>
            <div class="flex items-center bg-gray-50 text-gray-800 rounded-lg p-6 shadow-sm hover:shadow-md transition border-l-4 border-gray-200 cursor-pointer" onclick="window.location.href='<?= $this->urlFor('student.outpass.status') ?>/1';">
                <div class="flex-1">
                    <h3 class="text-xl font-bold">Industrial Visit</h3>
                    <p class="mt-2 text-gray-600">Outpass from <strong>2024-12-24</strong> to <strong>2024-12-26</strong></p>
                    <p class="mt-1 text-gray-500">Destination: Bengaluru</p>
                </div>
                <div class="ml-6 flex items-center space-x-3">
                    <span class="px-3 py-1 rounded-full bg-green-200 text-green-800 text-base font-medium">
                        <i class="fa-solid fa-check me-2"></i> Approved
                    </span>
                </div>
            </div>
        </section>

        <!-- Recent Outpasses Section -->
        <section class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-semibold text-gray-700">Recent Outpass Logs</h2>
                <a href="<?= $this->urlFor('student.outpass.history') ?>" class="text-blue-600 hover:text-blue-800 hover:underline text-sm font-medium">
                    View All History &rarr;
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Approved Outpass -->
                <div class="bg-gray-50 border-l-4 border-gray-200 rounded-lg p-5 shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-700">Family Function</h3>
                    <p class="mt-2 text-gray-600">Outpass from <strong>2024-10-01</strong> to <strong>2024-10-03</strong></p>
                    <p class="text-gray-500">Destination: 123 Main St, City</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800"><i class="fa fa-check me-2"></i>Approved</span>
                        <a href="<?= $this->urlFor('student.outpass.status') ?>/2" class="text-sm text-blue-500 hover:underline">View Details</a>
                    </div>
                </div>

                <!-- Pending Outpass -->
                <div class="bg-gray-50 border-l-4 border-gray-200 rounded-lg p-5 shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-700">Medical Checkup</h3>
                    <p class="mt-2 text-gray-600">Outpass from <strong>2024-12-10</strong> to <strong>2024-12-12</strong></p>
                    <p class="text-gray-500">Destination: City Hospital</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"><i class="fa-regular fa-hourglass-half me-2"></i>Pending</span>
                        <a href="<?= $this->urlFor('student.outpass.status') ?>/3" class="text-sm text-blue-500 hover:underline">View Details</a>
                    </div>
                </div>

                <!-- Rejected Outpass -->
                <div class="bg-gray-50 border-l-4 border-gray-200 rounded-lg p-5 shadow-md hover:shadow-lg transition">
                    <h3 class="text-lg font-semibold text-gray-700">Beach Resort</h3>
                    <p class="mt-2 text-gray-600">Outpass from <strong>2024-11-20</strong> to <strong>2024-11-22</strong></p>
                    <p class="text-gray-500">Destination: Sunshine Resort</p>
                    <div class="mt-4 flex justify-between items-center">
                        <span class="px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800"><i class="fa-solid fa-xmark me-2"></i>Rejected</span>
                        <a href="<?= $this->urlFor('student.outpass.status') ?>" class="text-sm text-blue-500 hover:underline">View Details</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
