<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto p-6 space-y-8">
        <!-- Page Title -->
        <header class="flex justify-between items-center py-4 border-b">
            <h1 class="text-2xl font-bold text-gray-800">Request Outpass</h1>
            <p class="text-sm text-gray-500">Submit a new outpass request.</p>
        </header>

        <!-- Form Section -->
        <section class="bg-white rounded-lg shadow p-8">
            <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Outpass Type -->
                <div>
                    <label for="outpass_type" class="block text-gray-700 font-medium">Outpass Type</label>
                    <select name="outpass_type" id="outpass_type" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                        <option value="" disabled selected>Select type</option>
                        <option value="day">Day Outpass</option>
                        <option value="night">Night Outpass</option>
                        <option value="special">Special Outpass</option>
                    </select>
                </div>

                <!-- Purpose -->
                <div>
                    <label for="purpose" class="block text-gray-700 font-medium">Purpose</label>
                    <textarea name="purpose" id="purpose" rows="3" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500" placeholder="Briefly describe the purpose of your outpass"></textarea>
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-gray-700 font-medium">Location</label>
                    <input type="text" name="location" id="location" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500" placeholder="Enter location">
                </div>

                <!-- Date and Time -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="from_date" class="block text-gray-700 font-medium">From Date</label>
                        <input type="date" name="from_date" id="from_date" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="to_date" class="block text-gray-700 font-medium">To Date</label>
                        <input type="date" name="to_date" id="to_date" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="from_time" class="block text-gray-700 font-medium">From Time</label>
                        <input type="time" name="from_time" id="from_time" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                    </div>
                    <div>
                        <label for="to_time" class="block text-gray-700 font-medium">To Time</label>
                        <input type="time" name="to_time" id="to_time" required class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                    </div>
                </div>

                <!-- Attachment -->
                <div>
                    <label for="attachment" class="block text-gray-700 font-medium">Attachment (if required)</label>
                    <input type="file" name="attachment" id="attachment" class="mt-1 w-full rounded border-gray-300 focus:ring-2 focus:ring-purple-500">
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-3 bg-purple-600 text-white rounded-lg shadow hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        Submit Request
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>
