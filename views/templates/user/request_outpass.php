<div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container px-6 py-8 mx-auto lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col py-4 mb-6 space-y-2 border-b border-gray-300 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Request Outpass</h1>
                <p class="mt-1 text-base text-gray-500">Submit a new outpass request.</p>
            </div>
        </header>

        <!-- Form Section -->
        <section class="p-8 bg-white rounded-lg shadow">
            <form id="outpassRequestForm" action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                <!-- Date and Time -->
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="from_date" class="block font-medium text-gray-700">From Date</label>
                        <input type="date" name="from_date" id="from_date" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label for="to_date" class="block font-medium text-gray-700">To Date</label>
                        <input type="date" name="to_date" id="to_date" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label for="from_time" class="block font-medium text-gray-700">From Time</label>
                        <input type="time" name="from_time" id="from_time" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                    <div>
                        <label for="to_time" class="block font-medium text-gray-700">To Time</label>
                        <input type="time" name="to_time" id="to_time" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                    </div>
                </div>

                <!-- Destination -->
                <div>
                    <label for="destination" class="block font-medium text-gray-700">Destination</label>
                    <input type="text" name="destination" id="destination" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Enter Destination">
                </div>

                <!-- Outpass Type -->
                <div>
                    <label for="outpass_type" class="block font-medium text-gray-700">Outpass Type</label>
                    <select name="outpass_type" id="outpass_type" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="" disabled selected>Select type</option>
                        <option value="home">Home Pass</option>
                        <option value="outing">Outing Pass</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Reason -->
                <div id="purposeField" class="hidden">
                    <label for="purpose" class="block font-medium text-gray-700">Reason</label>
                    <textarea name="purpose" id="purpose" rows="3" required class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200" placeholder="Describe the reason of your outpass"></textarea>
                </div>

                <!-- Attachments -->
                <div id="attachmentsField" class="hidden">
                    <label for="attachments" class="block font-medium text-gray-700">Attachments</label>
                    <div class="relative w-full px-4 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm hover:border-blue-500 focus-within:border-blue-500">
                        <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="flex items-center justify-between">
                            <span id="fileLabel" class="text-sm text-gray-500 truncate">Upload supporting documents (JPG, PNG, PDF)</span>
                            <button type="button" class="text-sm font-medium text-blue-600 hover:underline">Browse</button>
                        </div>
                    </div>
                    <!-- File Preview -->
                    <div id="filePreview" class="mt-2 text-sm text-gray-600"></div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button id="outpassSubmitButton" type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg shadow text-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Submit Request
                    </button>
                </div>
            </form>
        </section>
    </main>
</div>