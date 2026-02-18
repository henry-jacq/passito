<?php
$selectedCount = max(0, (int) ($selectedCount ?? 0));
$hasSelected = $selectedCount > 0;
$programs = is_array($programs ?? null) ? $programs : [];
$academicYears = is_array($academic_years ?? null) ? $academic_years : [];
?>
<div class="space-y-4">
    <h3 class="pb-3 text-xl font-bold text-gray-800 border-b border-gray-200">
        Export Students
    </h3>

    <p class="text-sm leading-6 text-gray-700">
        Choose which students to include in the CSV export.
    </p>

    <div class="space-y-3">
        <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer bg-gray-50">
            <input
                type="radio"
                name="students-export-scope"
                value="all"
                class="mt-1 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                checked>
            <span class="text-sm text-gray-800">
                All students
            </span>
        </label>
        
        <label class="flex items-start gap-3 p-3 border border-gray-200 rounded-lg cursor-pointer bg-gray-50">
            <input
                type="radio"
                name="students-export-scope"
                value="selected"
                class="mt-1 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                <?= !$hasSelected ? 'disabled' : '' ?>>
            <span class="text-sm text-gray-800">
                Selected students on this page
                <span class="font-semibold">(<?= $selectedCount ?>)</span>
            </span>
        </label>
    </div>

    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <div>
            <label for="export-academic-year-id" class="block mb-1 text-sm font-medium text-gray-700">Academic Year</label>
            <select
                id="export-academic-year-id"
                class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="">All Academic Years</option>
                <?php foreach ($academicYears as $year): ?>
                    <option value="<?= $year['id'] ?>">
                        <?= $year['label'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="export-program-id" class="block mb-1 text-sm font-medium text-gray-700">Program</label>
            <select
                id="export-program-id"
                class="w-full px-3 py-2 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500">
                <option value="">All Programs</option>
                <?php foreach ($programs as $program): ?>
                    <option value="<?= $program['id'] ?>">
                        <?= $program['programName'] . ' ' . $program['courseName'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <?php if (!$hasSelected): ?>
        <p class="text-xs text-amber-700">
            No students are selected yet. Select one or more students to enable the selected export option.
        </p>
    <?php endif; ?>
</div>