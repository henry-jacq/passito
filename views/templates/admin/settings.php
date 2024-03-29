<div class="row justify-content-center">
    <div class="col-md-10">
        <form>
            <h5 class="fw-normal">Notification Settings</h5>
            <hr class="mt-1">
            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="emailNotifications">
                    <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                </div>
            </div>
            <div class="form-group mb-3">
                <label for="notificationPreferences" class="form-label">Notification Preferences</label>
                <textarea class="form-control" id="notificationPreferences" rows="3"></textarea>
            </div>

            <!-- Display Preferences -->
            <h5 class="fw-normal">Display Preferences</h5>
            <hr class="mt-1">
            <div class="form-group mb-3">
                <label for="themeSelection" class="form-label">Theme Selection</label>
                <select class="form-control" id="themeSelection">
                    <option value="light">Light</option>
                    <option value="dark" selected>Dark</option>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="displayPreferences" class="form-label">Display Preferences</label>
                <textarea class="form-control" id="displayPreferences" rows="3"></textarea>
            </div>

            <!-- Backup and Restore -->
            <h5 class="fw-normal">Backup and Restore</h5>
            <hr class="mt-1">
            <div class="form-group mb-3">
                <label for="backupSettings" class="form-label">Backup Settings</label>
                <textarea class="form-control" id="backupSettings" rows="3"></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="restoreOptions" class="form-label">Restore Options</label>
                <textarea class="form-control" id="restoreOptions" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-prime btn-block">Save Changes</button>

        </form>
    </div>
</div>