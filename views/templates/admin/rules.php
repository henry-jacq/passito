<?php

use App\Enum\Gender;
?>
<!-- Outpass Settings Page -->
<main class="flex-1 p-6 mt-20 overflow-y-auto bg-gray-50">
    <h2 class="mb-4 text-2xl font-semibold text-gray-700">System Settings</h2>
    <p class="mb-8 text-base text-gray-700">
        Set rules and preferences to manage outpass requests, enforce guidelines, and enhance security.
    </p>

    <?php if (isset($reportSettings) && !empty($reportSettings)): ?>
        <!-- Automated Email Reports Section -->
        <section class="mb-8 bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <div class="mb-6 space-y-2">
                    <h3 class="text-lg font-semibold text-gray-800">Automated Email Reports</h3>
                    <p class="text-sm text-gray-500">Manage automated email delivery for daily and weekly reports.</p>
                    <p class="text-xs text-gray-400">These settings are saved individually and do not require the Save Settings button below.</p>
                </div>

                <div class="space-y-4">
                    <?php foreach ($reportSettings as $setting): ?>
                        <div class="flex flex-col p-4 border border-gray-200 rounded-lg md:flex-row md:items-center md:justify-between">
                            <div class="flex-1 mb-3 md:mb-0">
                                <div class="flex items-center mb-2 space-x-3">
                                    <h4 class="text-base font-semibold text-gray-700">
                                        <?= $setting->getReportKey()->display() ?>
                                    </h4>

                                    <?php if ($setting->isEnabled()): ?>
                                        <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full">
                                            Active
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-1 text-xs font-medium text-red-800 bg-red-100 rounded-full">
                                            Disabled
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="space-y-1 text-sm text-gray-500">
                                    <span><?= $setting->getReportKey()->description() ?></span>

                                    <div class="flex items-center space-x-4">
                                        <span>
                                            <strong>Schedule:</strong>
                                            <?= ucfirst(strtolower($setting->getFrequency()->value)) ?>
                                            <?php if ($setting->getTime()): ?>
                                                at <?= $setting->getTime()->format('h:i A') ?>
                                            <?php endif; ?>
                                        </span>
                                    </div>

                                    <div class="flex items-center space-x-4">
                                        <span>
                                            <strong>Next Email:</strong>
                                            <?= $setting->getNextSend() ? $setting->getNextSend()->format('M d, Y h:i A') : '—' ?>
                                        </span>
                                        <span>
                                            <strong>Last Sent:</strong>
                                            <?= $setting->getLastSent() ? $setting->getLastSent()->format('M d, Y h:i A') : '—' ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center space-x-2">
                                <?php if ($setting->isEnabled()): ?>
                                    <button
                                        type="button"
                                        class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 toggle-report-status"
                                        data-id="<?= $setting->getId() ?>" data-action="disable">
                                        Disable
                                    </button>
                                <?php else: ?>
                                    <button
                                        type="button"
                                        class="px-3 py-1 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 toggle-report-status"
                                        data-id="<?= $setting->getId() ?>" data-action="enable">
                                        Enable
                                    </button>
                                <?php endif; ?>

                                <button
                                    type="button"
                                    class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 report-settings-button"
                                    data-id="<?= $setting->getId() ?>">
                                    Settings
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <form action="<?= $this->urlFor('admin.outpass.settings') ?>" method="POST">
        <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">

        <!-- Outing Time Restrictions Section -->
        <section class="mb-8 overflow-hidden bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Time Restrictions - Outing</h3>

                <div class="space-y-6">
                    <div>
                        <h4 class="mb-2 font-semibold text-gray-700 text-md">Weekdays</h4>
                        <p class="mb-4 text-sm text-gray-500">
                            Restrict outings during college hours and overnight to maintain discipline.
                        </p>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- College Hours -->
                            <div>
                                <label for="weekday-college-hours-start" class="block text-sm font-medium text-gray-700">
                                    College Hours Restriction
                                </label>
                                <div class="flex mt-1 space-x-4">
                                    <input type="time" id="weekday-college-hours-start"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="Start Time" name="weekday_college_hours_start" value="<?= $settings->getWeekdayCollegeHoursStart() ? $settings->getWeekdayCollegeHoursStart()->format('H:i') : '' ?>">
                                    <input type="time" id="weekday-college-hours-end" name="weekday_college_hours_end"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="End Time" value="<?= $settings->getWeekdayCollegeHoursEnd() ? $settings->getWeekdayCollegeHoursEnd()->format('H:i') : '' ?>">
                                </div>
                            </div>
                            <!-- Overnight Restriction -->
                            <div>
                                <label for="weekday-overnight-start" class="block text-sm font-medium text-gray-700">
                                    Overnight Restriction
                                </label>
                                <div class="flex mt-1 space-x-4">
                                    <input type="time" id="weekday-overnight-start" name="weekday_overnight_start"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="Start Time" value="<?= $settings->getWeekdayOvernightStart() ? $settings->getWeekdayOvernightStart()->format('H:i') : '' ?>">
                                    <input type="time" id="weekday-overnight-end" name="weekday_overnight_end"
                                        class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                        placeholder="End Time" value="<?= $settings->getWeekdayOvernightEnd() ? $settings->getWeekdayOvernightEnd()->format('H:i') : '' ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="mb-2 font-semibold text-gray-700 text-md">Weekends</h4>
                        <p class="mb-4 text-sm text-gray-500">
                            Set a more flexible time frame for outing passes on weekends.
                        </p>
                        <div class="flex space-x-6">
                            <div class="w-1/2">
                                <label for="weekend-start-time" class="block text-sm font-medium text-gray-700">
                                    Start Time
                                </label>
                                <input type="time" id="weekend-start-time" name="weekend_start_time"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Start Time" value="<?= $settings->getWeekendStartTime() ? $settings->getWeekendStartTime()->format('H:i') : '' ?>">
                            </div>
                            <div class="w-1/2">
                                <label for="weekend-end-time" class="block text-sm font-medium text-gray-700">
                                    End Time
                                </label>
                                <input type="time" id="weekend-end-time" name="weekend_end_time"
                                    class="block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="End Time" value="<?= $settings->getWeekendEndTime() ? $settings->getWeekendEndTime()->format('H:i') : '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php if (Gender::isFemale($settings->getType()->value)): ?>
            <!-- Outpass Policies -->
            <section class="mb-8 bg-white rounded-lg shadow-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Outpass Policies</h3>
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                        <!-- Parent Approval -->
                        <div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="parent-approval" name="parent_approval"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= $settings->getParentApproval() ? 'checked' : '' ?>>
                                <label for="parent-approval" class="text-sm font-medium text-gray-700">Enable Parent Approval</label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Allows parents to approve requests via SMS.</p>
                        </div>

                        <!-- Companion Verification -->
                        <div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="companion-verification" name="companion_verification"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= $settings->getCompanionVerification() ? 'checked' : '' ?>>
                                <label for="companion-verification" class="text-sm font-medium text-gray-700">Companion Verification</label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Requires users to specify companions for safety.</p>
                        </div>

                        <!-- Emergency Contact Notification -->
                        <div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="emergency-contact-notification" name="emergency_contact_notification"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    <?= $settings->getEmergencyContactNotification() ? 'checked' : '' ?>>
                                <label for="emergency-contact-notification" class="text-sm font-medium text-gray-700">Emergency Contact Notification</label>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Notifies emergency contacts during outings.</p>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>

        <!-- Notification Preferences -->
        <section class="mb-8 bg-white rounded-lg shadow-lg">
            <div class="p-6">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Notification Preferences</h3>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" id="app-notification" name="app_notification"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            <?= $settings->getAppNotification() ? 'checked' : '' ?>>
                        <label for="app-notification" class="ml-3 text-sm font-medium text-gray-700">App Notifications</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="email-notification" name="email_notification"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            <?= $settings->getEmailNotification() ? 'checked' : '' ?>>
                        <label for="email-notification" class="ml-3 text-sm font-medium text-gray-700">Email Notifications</label>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="sms-notification" name="sms_notification"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            <?= $settings->getSmsNotification() ? 'checked' : '' ?>>
                        <label for="sms-notification" class="ml-3 text-sm font-medium text-gray-700">SMS Notifications</label>
                    </div>
                </div>
            </div>
        </section>

        <!-- Save and Reset Buttons -->
        <div class="flex justify-end mt-6 space-x-4">
            <button type="submit" name="reset_defaults" value="true" class="px-6 py-2 text-sm font-medium text-gray-800 bg-gray-200 rounded-md shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500">
                Reset to Defaults
            </button>
            <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-blue-600 rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                Save Settings
            </button>
        </div>
    </form>

</main>
