<div class="px-2 space-y-6">
    <h3 class="text-2xl font-bold text-gray-900">Add New Institution</h3>

    <div class="space-y-5">
        <div class="space-y-2">
            <label for="institution-name" class="block font-semibold text-gray-700 text-md">Institution Name</label>
            <input type="text" id="institution-name" name="institution-name" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Name" required>
        </div>
        <div class="space-y-2">
            <label for="institution-address" class="block font-semibold text-gray-700 text-md">Institution Address</label>
            <input type="text" id="institution-address" name="institution-address" class="block w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" placeholder="Enter Address" required>
        </div>
        <div class="space-y-2">
            <label for="institution-type" class="block font-semibold text-gray-700 text-md">Institution Type</label>
            <select id="institution-type" name="institution-type" class="w-full px-4 py-2 mt-1 text-gray-800 transition duration-200 border border-gray-300 rounded-lg bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-500 text-md" required>
                <option value="college">College</option>
                <option value="university">University</option>
            </select>
        </div>
    </div>
</div>