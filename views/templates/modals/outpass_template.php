<?php
$template = $template_data ?? null;
if (!is_array($template)) {
    $template = [];
}
$isEdit = !empty($template['id']);
$fields = $template['fields'] ?? [];
?>
<div class="px-2 space-y-6">
    <h3 class="text-xl font-semibold text-gray-900"><?= $isEdit ? 'Update Outpass Template' : 'Create Outpass Template' ?></h3>
    <?php if ($isEdit): ?>
        <input type="hidden" id="template-id" value="<?= htmlspecialchars((string) $template['id']) ?>">
    <?php endif; ?>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Template Name</label>
        <input type="text" id="template-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="e.g., Medical Emergency Pass" value="<?= htmlspecialchars((string) ($template['name'] ?? '')) ?>">
    </div>

    <div>
        <label class="block mb-1 text-sm font-medium text-gray-700">Template Description</label>
        <textarea id="template-description" rows="2" class="w-full px-3 py-2 transition-all transition border border-gray-300 rounded-md resize-none focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Short description of this template"><?= htmlspecialchars((string) ($template['description'] ?? '')) ?></textarea>
        <div class="flex items-center space-x-2">
            <input type="checkbox" id="allow-attachments" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?= !empty($template['allowAttachments']) ? 'checked' : '' ?>>
            <label for="allow-attachments" class="text-sm text-gray-700">Allow students to upload attachments (Multiple)</label>
        </div>
        <?php if ($isEdit): ?>
            <div class="flex items-center mt-2 space-x-2">
                <input type="checkbox" id="template-active" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" <?= !array_key_exists('isActive', $template) || $template['isActive'] ? 'checked' : '' ?>>
                <label for="template-active" class="text-sm text-gray-700">Template is active</label>
            </div>
        <?php endif; ?>
    </div>

    <div class="space-y-3">
        <div class="text-sm font-medium text-gray-700">Outpass Timings</div>
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="weekday-college-hours-start" class="block mb-1 text-sm font-medium text-gray-700">Weekday College Hours</label>
                <div class="flex gap-3">
                    <input type="time" id="weekday-college-hours-start" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Start" value="<?= htmlspecialchars((string) ($template['weekdayCollegeHoursStart'] ?? '')) ?>">
                    <input type="time" id="weekday-college-hours-end" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="End" value="<?= htmlspecialchars((string) ($template['weekdayCollegeHoursEnd'] ?? '')) ?>">
                </div>
            </div>
            <div>
                <label for="weekday-overnight-start" class="block mb-1 text-sm font-medium text-gray-700">Weekday Overnight</label>
                <div class="flex gap-3">
                    <input type="time" id="weekday-overnight-start" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Start" value="<?= htmlspecialchars((string) ($template['weekdayOvernightStart'] ?? '')) ?>">
                    <input type="time" id="weekday-overnight-end" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="End" value="<?= htmlspecialchars((string) ($template['weekdayOvernightEnd'] ?? '')) ?>">
                </div>
            </div>
            <div>
                <label for="weekend-start-time" class="block mb-1 text-sm font-medium text-gray-700">Weekend Window</label>
                <div class="flex gap-3">
                    <input type="time" id="weekend-start-time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="Start" value="<?= htmlspecialchars((string) ($template['weekendStartTime'] ?? '')) ?>">
                    <input type="time" id="weekend-end-time" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none" placeholder="End" value="<?= htmlspecialchars((string) ($template['weekendEndTime'] ?? '')) ?>">
                </div>
            </div>
        </div>
        <p class="text-xs text-gray-500">Leave any field blank to skip that restriction for this template.</p>
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

        <?php foreach ($fields as $field): ?>
            <?php if (!empty($field['system'])) continue; ?>
            <div class="p-4 space-y-4 transition bg-white border border-gray-200 rounded-md shadow-sm group">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-10 md:col-span-5">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Field Name</label>
                        <input type="text" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 field-name" value="<?= htmlspecialchars((string) ($field['name'] ?? '')) ?>">
                    </div>
                    <div class="col-span-10 md:col-span-4">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Type</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 field-type">
                            <option value="text" <?= ($field['type'] ?? '') === 'text' ? 'selected' : '' ?>>Text</option>
                            <option value="number" <?= ($field['type'] ?? '') === 'number' ? 'selected' : '' ?>>Number</option>
                            <option value="date" <?= ($field['type'] ?? '') === 'date' ? 'selected' : '' ?>>Date</option>
                            <option value="time" <?= ($field['type'] ?? '') === 'time' ? 'selected' : '' ?>>Time</option>
                        </select>
                    </div>
                    <div class="flex items-end col-span-10 md:col-span-3">
                        <button type="button" class="w-full px-2 py-2.5 text-sm font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200 remove-field">
                            <i class="mr-1 fa fa-trash"></i> Delete Field
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <input type="checkbox" class="w-4 h-4 text-blue-600 border-gray-300 rounded field-required focus:ring-blue-500" <?= !empty($field['required']) ? 'checked' : '' ?>>
                    <label class="text-sm text-gray-700 select-none">Required Field</label>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div>
        <button type="button" id="add-field" class="text-sm text-blue-600 hover:underline">+ Add Another Field</button>
    </div>
</div>
