<div class="flex flex-col min-h-screen bg-gray-50">
    <?= $this->getComponent('user/header', [
        'routeName' => $routeName
    ]) ?>

    <main class="container px-6 py-8 mx-auto lg:px-12">
        <header class="flex flex-col py-4 mb-6 space-y-2 border-b border-gray-300 sm:flex-row sm:justify-between sm:items-center sm:space-y-0">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Request Outpass</h1>
                <p class="mt-1 text-base text-gray-500">Submit a new outpass request.</p>
            </div>
        </header>

        <section class="p-6 bg-white rounded-lg shadow">
            <?php if ($lockStatus): ?>
                <!-- Simple Elegant Locked Status UI -->
                <div class="flex flex-col items-center justify-center py-16 space-y-8">
                    <!-- Minimalist Lock Icon -->
                    <div class="relative">
                        <div class="flex items-center justify-center w-20 h-20 bg-white border-4 rounded-full shadow-sm border-amber-200">
                            <i class="text-3xl text-amber-500 fas fa-lock"></i>
                        </div>
                        <div class="absolute rounded-full -inset-2 bg-amber-100 -z-10 opacity-20"></div>
                    </div>

                    <!-- Clean Message -->
                    <div class="max-w-md space-y-3 text-center">
                        <h2 class="text-xl font-medium text-gray-900">Requests Temporarily Disabled</h2>
                        <p class="leading-relaxed text-gray-600">
                            New outpass submissions are currently unavailable. Please check back later or contact administration if urgent.
                        </p>
                    </div>

                    <!-- Single Action Button -->
                    <div class="pt-4">
                        <a href="<?= $this->urlFor('student.outpass.history') ?>"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-blue-800 rounded-lg hover:bg-blue-900 transition-colors focus:outline-none focus:ring-4 focus:ring-blue-100">
                            <i class="mr-2 text-sm fas fa-history"></i>
                            View Previous Requests
                        </a>
                    </div>
                </div>
            <?php elseif (is_array($templates) && count($templates) > 0): ?>
                <!-- Rest of the existing template selection code remains unchanged -->
                <div class="mb-4 space-x-1 text-sm text-gray-500">
                    <i class="fas fa-info-circle"></i>
                    <span>Select an outpass type to proceed.</span>
                </div>
                <div class="space-y-4">
                    <?php foreach ($templates as $template):
                        $template_name = strtolower(str_replace(' ', '_', $template->getName())); ?>

                        <label for="<?= $template_name ?>_pass" class="flex items-start p-4 border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer bg-white gap-4 <?= $passType === $template_name ? 'ring-2 ring-blue-500' : '' ?>">
                            <input type="radio"
                                name="outpass_type"
                                id="<?= $template_name ?>_pass"
                                value="<?= $template_name ?>"
                                data-name="<?= $template_name ?>"
                                class="w-5 h-5 mt-1 text-blue-600 border-gray-300 focus:ring-blue-500 outpass-radio"
                                <?= $passType === $template_name ? 'checked' : '' ?>>

                            <div class="flex flex-col">
                                <span class="font-normal text-gray-800 text-md">
                                    <?= $template->getName() ?>
                                    <?php if ($template->getIsSystemTemplate()): ?>
                                        <span class="text-red-500">*</span>
                                    <?php endif; ?>
                                </span>
                                <span class="text-sm text-gray-500">
                                    <?= $template->getDescription() ?>
                                </span>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

            <?php elseif (!empty($templates)): ?>
                <!-- Form display code remains unchanged -->
                <div class="flex flex-col mb-4 md:flex-row md:items-center md:justify-between">
                    <div class="text-lg">
                        <h2 class="text-lg font-bold text-gray-800"><?= $templates->getName() ?></h2>
                        <span class="mt-1 text-sm text-gray-500"><?= $templates->getDescription() ?></span>
                    </div>
                    <div class="mt-4 text-gray-500 md:mt-0 text-md">
                        <a href="<?= $this->urlFor('student.outpass.request') ?>" class="text-blue-600 hover:underline">
                            <i class="fas fa-arrow-left"></i> Back to Change Outpass Type
                        </a>
                    </div>
                </div>
                <hr class="mt-2 mb-6 border-gray-300">
            <?php else: ?>
                <!-- Invalid type display code remains unchanged -->
                <div class="flex items-center justify-center w-full h-64 bg-gray-100 rounded-lg">
                    <p class="text-gray-500 text-md">
                        Invalid Outpass Type selected.
                        <a href="<?= $this->urlFor('student.outpass.request') ?>" class="text-blue-600 hover:underline">
                            Change Outpass Type Here.
                        </a>
                    </p>
                </div>
            <?php endif; ?>

            <?php if (!empty($templates) && !empty($passType)): ?>
                <form id="outpassRequestForm" action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="<?= $this->csrfFieldName() ?>" value="<?= $this->csrfToken() ?>">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <?php
                        $systemFields = array_filter($templates->getFields()->toArray(), fn($f) => $f->getIsSystemField());
                        $fieldCount = count($systemFields);

                        foreach (array_values($systemFields) as $index => $field): ?>
                            <?php
                            $isLastAndOdd = $index === $fieldCount - 1 && $fieldCount % 2 !== 0;
                            $inputName = strtolower(explode(" ", $field->getFieldName())[0]) . '_' . strtolower($field->getFieldType());
                            ?>
                            <div class="<?= $isLastAndOdd ? 'md:col-span-2' : '' ?>">
                                <label for="<?= $inputName ?>" class="block font-medium text-gray-700">
                                    <?= $field->getFieldName() ?>
                                    <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="<?= $field->getFieldType() ?>"
                                    name="<?= $inputName ?>"
                                    id="<?= $inputName ?>"
                                    <?= $field->getIsRequired() ? 'required' : '' ?>
                                    class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php
                    // Loop and render custom fields
                    $customFields = array_filter($templates->getFields()->toArray(), fn($f) => !$f->getIsSystemField());
                    $fieldCount = count($customFields);

                    foreach (array_values($customFields) as $index => $field):
                        $isLastAndOdd = $index === $fieldCount - 1 && $fieldCount % 2 !== 0;
                        $inputName = strtolower(explode(" ", $field->getFieldName())[0]) . '_' . strtolower($field->getFieldType()); ?>
                        <div class="<?= $isLastAndOdd ? 'md:col-span-2' : '' ?>">
                            <label for="<?= $inputName ?>" class="block font-medium text-gray-700">
                                <?= $field->getFieldName() ?>
                                <?php if ($field->getIsRequired()): ?>
                                    <span class="text-red-500">*</span>
                                <?php endif; ?>
                            </label>
                            <input type="<?= $field->getFieldType() ?>"
                                name="<?= strtolower($field->getFieldName()) ?>"
                                id="<?= $inputName ?>"
                                <?= $field->getIsRequired() ? 'required' : '' ?>
                                class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                        </div>
                    <?php endforeach; ?>

                    <?php if ($templates->getAllowAttachments()): ?>
                        <div id="attachmentsField">
                            <label for="attachments" class="block font-medium text-gray-700">Attachments</label>

                            <input type="file" name="attachments[]" id="attachments" multiple accept=".jpg,.jpeg,.png,.pdf"
                                class="block w-full px-0 py-0 mt-1 text-sm text-gray-800 transition duration-200 bg-white border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-500 file:mr-4 file:pr-6 file:border-0 file:bg-gray-50 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-100">

                            <!-- File Label -->
                            <div class="mt-1 text-xs text-gray-500 truncate" id="fileLabel">
                                Upload supporting documents (JPG, PNG, PDF)
                            </div>

                            <!-- File Preview -->
                            <div id="filePreview" class="mt-2 text-sm text-gray-600"></div>
                        </div>
                    <?php endif; ?>

                    <div class="flex justify-end">
                        <button id="outpassSubmitButton" type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-lg shadow text-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Submit Request
                        </button>
                    </div>
                </form>
            <?php endif; ?>
        </section>
    </main>
</div>
