<div class="px-2 space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">Import Students</h3>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="file" class="block text-sm font-medium text-gray-700">Students CSV</label>
            <input type="file" id="file" name="file" accept=".csv"
                class="block w-full px-0 py-0 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 file:mr-4 file:pr-6 file:border-0 file:bg-gray-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-100" />
        </div>
    </div>

    <ul class="mt-4 space-y-1 text-sm text-gray-500 list-disc list-inside">
        <li>Ensure CSV format matches the provided template.</li>
        <li>Selected year must align with the course duration.</li>
        <li>Academic year is required for each student.</li>
    </ul>
</div>
