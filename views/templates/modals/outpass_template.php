<div class="px-2 space-y-6">
    <h3 class="text-xl font-semibold text-gray-900">Create Outpass Template</h3>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Template Name</label>
        <input type="text" id="template-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g., Medical Emergency Pass">
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Template Description</label>
        <textarea id="template-description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Short description of this template"></textarea>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Visibility</label>
        <select class="w-full px-3 py-2 border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <option value="visible">Visible</option>
            <option value="hidden">Hidden</option>
            <option value="readonly">Read Only</option>
        </select>
    </div>

    <div class="flex items-center space-x-2">
        <input type="checkbox" id="allow-attachments" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
        <label for="allow-attachments" class="text-sm text-gray-700">Allow students to upload attachments</label>
    </div>

    <div id="template-fields" class="space-y-4">
        <!-- Template block (hidden for cloning) -->
        <div class="hidden p-4 space-y-4 transition bg-white border border-gray-200 rounded-md shadow-sm group">
            <div class="grid grid-cols-12 gap-4">
                <!-- Field Name -->
                <div class="col-span-10 md:col-span-5">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Field Name</label>
                    <input type="text" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 field-name" placeholder="e.g., Location">
                </div>

                <!-- Field Type -->
                <div class="col-span-10 md:col-span-4">
                    <label class="block mb-1 text-sm font-medium text-gray-700">Type</label>
                    <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 field-type">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                        <option value="date">Date</option>
                        <option value="time">Time</option>
                    </select>
                </div>

                <!-- Remove Button -->
                <div class="flex items-end col-span-10 md:col-span-3">
                    <button type="button" class="w-full px-2 py-2.5 text-sm font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-field">
                        <i class="mr-1 fa fa-trash"></i> Delete Field
                    </button>
                </div>
            </div>

            <!-- Required Toggle -->
            <div class="flex items-center space-x-2">
                <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded field-required focus:ring-blue-500">
                <label class="text-sm text-gray-700 select-none">Required Field</label>
            </div>
        </div>
    </div>

    <div>
        <button type="button" id="add-field" class="text-sm text-blue-600 hover:underline">+ Add Another Field</button>
    </div>
</div>