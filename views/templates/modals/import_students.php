<div class="px-2 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Import Students</h3>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="year" class="block text-sm font-medium text-gray-700">Academic Year</label>
            <select id="year" name="year" class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select Year</option>
                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>
                <option value="4">4th Year</option>
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="institution" class="block text-sm font-medium text-gray-700">Institution</label>
            <select id="institution" name="institution" class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500">
                <option value="" disabled selected>Select Institution</option>
                <?php foreach ($institutions as $institution): ?>
                    <option value="<?= $institution['id'] ?>"><?= $institution['name'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="sm:col-span-2">
            <label for="file" class="block text-sm font-medium text-gray-700">Student CSV</label>
            <input type="file" id="file" name="file" accept=".csv"
                class="block w-full px-0 py-0 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:pr-6 file:border-0 file:bg-gray-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-100" />
        </div>
    </div>

    <ul class="mt-4 space-y-1 text-sm text-gray-500 list-disc list-inside">
        <li>Ensure CSV format matches the provided template.</li>
        <li>Selected year must align with the course duration.</li>
        <li>Students are imported year-wise for better categorization.</li>
    </ul>
</div>