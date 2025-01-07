<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-6 lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 border-b border-gray-300 space-y-2 sm:space-y-0 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Request Outpass</h1>
                <p class="text-base text-gray-500 mt-1">Submit a new outpass request.</p>
            </div>
        </header>

        <!-- Form Section -->
        <section class="bg-white rounded-lg shadow p-8">
            <form id="outpassRequestForm" action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="from_date" class="block text-gray-700 font-medium">From Date</label>
                        <input type="date" name="from_date" id="from_date" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition">
                    </div>
                    <div>
                        <label for="to_date" class="block text-gray-700 font-medium">To Date</label>
                        <input type="date" name="to_date" id="to_date" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition">
                    </div>
                    <div>
                        <label for="from_time" class="block text-gray-700 font-medium">From Time</label>
                        <input type="time" name="from_time" id="from_time" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition">
                    </div>
                    <div>
                        <label for="to_time" class="block text-gray-700 font-medium">To Time</label>
                        <input type="time" name="to_time" id="to_time" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition">
                    </div>
                </div>

                <!-- Outpass Type -->
                <div>
                    <label for="outpass_type" class="block text-gray-700 font-medium">Outpass Type</label>
                    <select name="outpass_type" id="outpass_type" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition">
                        <option value="" disabled selected>Select type</option>
                        <option value="home">Home Pass</option>
                        <option value="outing">Outing Pass</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-gray-700 font-medium">Purpose</label>
                    <textarea name="purpose" id="purpose" rows="3" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition" placeholder="Describe the purpose of your outpass"></textarea>
                </div>

                <!-- Location -->
                <div>
                    <label for="destination" class="block text-gray-700 font-medium">Destination</label>
                    <input type="text" name="destination" id="destination" required class="mt-1 w-full rounded-md border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 transition" placeholder="Enter destination">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="outpassSubmitButton" type="submit" class="px-4 py-2 bg-purple-600 text-lg text-white rounded-lg shadow hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Submit Request
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>