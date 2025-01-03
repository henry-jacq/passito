<div class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container mx-auto py-8 px-6 lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-4 border-b space-y-2 sm:space-y-0 mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Student Profile</h1>
                <p class="text-base text-gray-500 mt-1">View and manage your profile details.</p>
            </div>
        </header>

        <!-- Profile Details Section -->
        <section class="bg-white rounded-lg shadow p-8 mb-4">
            <h2 class="text-xl font-medium text-gray-700 mb-6">Profile Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Profile Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getUser()->getName()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Digital ID</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getDigitalId()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getYear()?> Year</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Branch</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getBranch()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Institution</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getHostel()->getInstitution()->getName()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hostel Number</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getHostel()->getName()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Room Number</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getRoomNo()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getUser()->getContactNo()?></div>
                </div>
            </div>
        </section>

        <!-- Additional Details Section -->
        <section class="bg-white rounded-lg shadow p-8 mb-8">
            <h2 class="text-xl font-medium text-gray-700 mb-6">Additional Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent Contact</label>
                    <div class="mt-1 text-gray-800 font-medium"><?= $userData->getParentNo()?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Warden Assigned</label>
                    <div class="mt-1 text-gray-800 font-medium">
                    <?php
                    use App\Enum\Gender;
                     if ($userData->getHostel()->getWarden()->getGender() === Gender::MALE):?>
                        Mr. <?php else: ?> Ms. <?php endif; ?>
                     <?= $userData->getHostel()->getWarden()->getName()?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Note -->
        <footer class="text-center text-sm text-gray-500">
            This profile page is read-only. For updates, please contact the administration.
        </footer>
    </main>
</div>
