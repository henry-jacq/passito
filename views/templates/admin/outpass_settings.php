<?php

use App\Enum\Gender;
?>
<!-- Outpass Settings Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto bg-gray-50">
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Outpass Settings</h2>
    <p class="text-gray-700 text-base mb-8">
        Set rules and preferences to manage outpass requests, enforce guidelines, and enhance security.
    </p>

    <!-- General Settings Section -->
    <section class="bg-white shadow-lg rounded-lg mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">General Settings</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Daily Limit -->
                <div>
                    <label for="daily-limit" class="block text-sm font-medium text-gray-700">Daily Request Limit</label>
                    <input type="number" id="daily-limit" placeholder="e.g., 1"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        value="<?= $settings->getDailyLimit() ?>">
                </div>

                <!-- Weekly Limit -->
                <div>
                    <label for="weekly-limit" class="block text-sm font-medium text-gray-700">Weekly Request Limit</label>
                    <input type="number" id="weekly-limit" placeholder="e.g., 3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        value="<?= $settings->getWeeklyLimit() ?>">
                </div>
            </div>
        </div>
    </section>

    <!-- Outing Time Restrictions Section -->
    <section class="bg-white shadow-lg rounded-lg overflow-hidden mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Outing Time Restrictions</h3>

            <div class="space-y-6">
                <div>
                    <h4 class="text-md font-semibold text-gray-700 mb-2">Weekdays</h4>
                    <p class="text-gray-500 text-sm mb-4">
                        Restrict outings during college hours and overnight to maintain discipline.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- College Hours -->
                        <div>
                            <label for="weekday-college-hours-start" class="block text-sm font-medium text-gray-700">
                                College Hours Restriction
                            </label>
                            <div class="flex space-x-4 mt-1">
                                <input type="time" id="weekday-college-hours-start"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Start Time" value="<?= $settings->getWeekdayCollegeHoursStart() ? $settings->getWeekdayCollegeHoursStart()->format('H:i') : '' ?>">
                                <input type="time" id="weekday-college-hours-end"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="End Time" value="<?= $settings->getWeekdayCollegeHoursEnd() ? $settings->getWeekdayCollegeHoursEnd()->format('H:i') : '' ?>">
                            </div>
                        </div>
                        <!-- Overnight Restriction -->
                        <div>
                            <label for="weekday-overnight-start" class="block text-sm font-medium text-gray-700">
                                Overnight Restriction
                            </label>
                            <div class="flex space-x-4 mt-1">
                                <input type="time" id="weekday-overnight-start"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="Start Time" value="<?= $settings->getWeekdayOvernightStart() ? $settings->getWeekdayOvernightStart()->format('H:i') : '' ?>">
                                <input type="time" id="weekday-overnight-end"
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                    placeholder="End Time" value="<?= $settings->getWeekdayOvernightEnd() ? $settings->getWeekdayOvernightEnd()->format('H:i') : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-700 mb-2">Weekends</h4>
                    <p class="text-gray-500 text-sm mb-4">
                        Set a more flexible time frame for outing passes on weekends.
                    </p>
                    <div class="flex space-x-6">
                        <div class="w-1/2">
                            <label for="weekend-start-time" class="block text-sm font-medium text-gray-700">
                                Start Time
                            </label>
                            <input type="time" id="weekend-start-time"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="Start Time" value="<?= $settings->getWeekendStartTime() ? $settings->getWeekendStartTime()->format('H:i') : '' ?>">
                        </div>
                        <div class="w-1/2">
                            <label for="weekend-end-time" class="block text-sm font-medium text-gray-700">
                                End Time
                            </label>
                            <input type="time" id="weekend-end-time"
                                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                placeholder="End Time" value="<?= $settings->getWeekendEndTime() ? $settings->getWeekendEndTime()->format('H:i') : '' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php if (Gender::isFemale($settings->getType()->value)): ?>
        <!-- Outpass Policies -->
        <section class="bg-white shadow-lg rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Outpass Policies</h3>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Parent Approval -->
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="parent-approval" name="parent-approval"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                <?= $settings->isParentApproval() ? 'checked' : '' ?>>
                            <label for="parent-approval" class="text-sm font-medium text-gray-700">Enable Parent Approval</label>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Allows parents to approve requests via WhatsApp or email.</p>
                    </div>

                    <!-- Companion Verification -->
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="companion-verification" name="companion-verification"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                <?= $settings->isCompanionVerification() ? 'checked' : '' ?>>
                            <label for="companion-verification" class="text-sm font-medium text-gray-700">Companion Verification</label>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Requires users to specify companions for safety.</p>
                    </div>

                    <!-- Emergency Contact Notification -->
                    <div>
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" id="emergency-contact-notification" name="emergency-contact-notification"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                <?= $settings->isEmergencyContactNotification() ? 'checked' : '' ?>>
                            <label for="emergency-contact-notification" class="text-sm font-medium text-gray-700">Emergency Contact Notification</label>
                        </div>
                        <p class="text-gray-500 text-sm mt-2">Notifies emergency contacts during outings.</p>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <!-- Notification Preferences -->
    <section class="bg-white shadow-lg rounded-lg mb-8">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Notification Preferences</h3>
            <div class="space-y-4">
                <div class="flex items-center">
                    <input type="checkbox" id="app-notification" name="app-notification"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        <?= $settings->isAppNotification() ? 'checked' : '' ?>>
                    <label for="app-notification" class="ml-3 text-sm font-medium text-gray-700">App Notifications</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="email-notification" name="email-notification"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        <?= $settings->isEmailNotification() ? 'checked' : '' ?>>
                    <label for="email-notification" class="ml-3 text-sm font-medium text-gray-700">Email Notifications</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="sms-notification" name="sms-notification"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                        <?= $settings->isSmsNotification() ? 'checked' : '' ?>>
                    <label for="sms-notification" class="ml-3 text-sm font-medium text-gray-700">SMS Notifications</label>
                </div>
            </div>
        </div>
    </section>

    <!-- Save and Reset Buttons -->
    <div class="flex justify-end space-x-4 mt-6">
        <button
            class="px-6 py-2 bg-gray-200 text-gray-800 text-sm font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
            Reset to Defaults
        </button>
        <button
            class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            Save Settings
        </button>
    </div>
</main>