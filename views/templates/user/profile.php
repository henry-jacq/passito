<div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Header Section -->
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <!-- Main Content -->
    <main class="container px-6 py-8 mx-auto lg:px-12">
        <!-- Page Title -->
        <header class="flex flex-col py-4 mb-6 space-y-2 border-b sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Student Profile</h1>
                <p class="mt-1 text-base text-gray-500">View and manage your profile details.</p>
            </div>
        </header>

        <!-- Profile Details Section -->
        <section class="p-8 mb-4 bg-white rounded-lg shadow">
            <h2 class="mb-6 text-xl font-medium text-gray-700">Profile Information</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Profile Fields -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getUser()->getName() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Digital ID</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getDigitalId() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Year</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getYear() ?> Year</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Branch</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getBranch() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Institution</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getInstitution()->getName() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Hostel Number</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getHostel()->getName() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Room Number</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getRoomNo() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Contact Number</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getUser()->getContactNo() ?></div>
                </div>
            </div>
        </section>

        <!-- Additional Details Section -->
        <section class="p-8 mb-8 bg-white rounded-lg shadow">
            <h2 class="mb-6 text-xl font-medium text-gray-700">Additional Details</h2>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Parent Contact</label>
                    <div class="mt-1 font-medium text-gray-800"><?= $userData->getParentNo() ?></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Warden Assigned</label>
                    <div class="mt-1 font-medium text-gray-800">
                        <?php

                        use App\Enum\Gender;

                        if ($userData->getHostel()->getWarden()->getGender() === Gender::MALE): ?>
                            Mr. <?php else: ?> Ms. <?php endif; ?>
                        <?= $userData->getHostel()->getWarden()->getName() ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer Note -->
        <footer class="text-sm text-center text-gray-500">
            This profile page is read-only. For updates, please contact the administration.
        </footer>
    </main>
</div>