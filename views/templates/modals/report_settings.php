<?php

use App\Enum\CronFrequency;
use App\Enum\ReportKey;
?>
<div class="px-2 space-y-6">
    <h3 class="mb-6 text-lg font-semibold text-gray-900 border-b border-gray-100">
        <?= htmlspecialchars(ReportKey::from($reportConfig['reportKey'])->display()) ?> Summary
    </h3>

    <div class="space-y-5">
        <!-- Report Frequency -->
        <div class="space-y-2">
            <label for="frequency" class="block text-sm font-medium text-gray-700">Report Frequency</label>
            <select id="frequency" name="frequency"
                class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none">
                <?php foreach (CronFrequency::cases() as $freq): ?>
                    <option value="<?= $freq->value ?>" <?= ($reportConfig['frequency'] === $freq->value) ? 'selected' : '' ?>>
                        <?= ucfirst($freq->value) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Weekly: Day of Week -->
        <div id="weekly-options" class="<?= ($reportConfig['frequency'] === 'weekly') ? '' : 'hidden' ?> space-y-2">
            <label for="day-of-week" class="block text-sm font-medium text-gray-700">Day of Week</label>
            <select id="day-of-week" name="day-of-week"
                class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none">
                <?php for ($i = 1; $i <= 7; $i++): ?>
                    <option value="<?= $i ?>" <?= ($reportConfig['dayOfWeek'] == $i) ? 'selected' : '' ?>>
                        <?= jddayofweek($i - 1, 1) ?>
                    </option>
                <?php endfor; ?>
            </select>
        </div>

        <!-- Monthly: Day of Month -->
        <div id="monthly-options" class="<?= ($reportConfig['frequency'] === 'monthly') ? '' : 'hidden' ?> space-y-2">
            <label for="day-of-month" class="block text-sm font-medium text-gray-700">Day of Month</label>
            <input type="number" id="day-of-month" min="1" max="31"
                class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
                value="<?= htmlspecialchars($reportConfig['dayOfMonth'] ?: '') ?>">
        </div>

        <!-- Yearly: Month + Day -->
        <div id="yearly-options"
            class="<?= ($reportConfig['frequency'] == 'yearly') ? 'md:flex space-x-4' : 'hidden space-y-4 md:space-y-0' ?>">
            <div class="space-y-2 md:flex-1">
                <label for="month" class="block text-sm font-medium text-gray-700">Month</label>
                <select id="month" name="month"
                    class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= ($reportConfig['month'] == $m) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="space-y-2 md:flex-1">
                <label for="yearly-day-of-month" class="block text-sm font-medium text-gray-700">Day of Month</label>
                <input type="number" id="yearly-day-of-month" min="1" max="31"
                    class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
                    value="<?= htmlspecialchars($reportConfig['dayOfMonth'] ?: '') ?>">
            </div>
        </div>

        <!-- Time -->
        <div class="space-y-2">
            <label for="time" class="block text-sm font-medium text-gray-700">Time to Send</label>
            <input type="time" id="time" name="time"
                class="block w-full px-3 py-2 text-sm transition-colors bg-white border border-gray-200 rounded-md focus:border-blue-400 focus:ring-1 focus:ring-blue-400 focus:outline-none"
                value="<?= htmlspecialchars($reportConfig['time']) ?>">
        </div>

        <!-- Recipients -->
        <div class="space-y-3">
            <label class="block text-sm font-medium text-gray-700">Recipients</label>
            <div class="space-y-1">
                <p class="text-xs leading-relaxed text-gray-500">
                    The super admin will always receive the report. Select additional wardens as needed.
                </p>

                <div class="pt-2 space-y-2">
                    <?php foreach ($wardens as $warden): ?>
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" name="recipients[]" value="<?= $warden['id']; ?>"
                                class="w-4 h-4 text-blue-500 border-gray-200 rounded focus:ring-1 focus:ring-blue-400 focus:ring-offset-0"
                                <?= in_array($warden['id'], array_column($reportConfig['recipients'], 'id')) ? 'checked' : '' ?>>
                            <span class="text-sm text-gray-700 group-hover:text-gray-900">
                                <?= htmlspecialchars($warden['name'] . ' (' . $warden['email'] . ')'); ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>