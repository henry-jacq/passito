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

        <section class="p-8 bg-white rounded-lg shadow">
            <?php if (is_array($templates) && count($templates) > 0): ?>
                <div class="mb-4 space-x-1 text-sm text-gray-500">
                    <i class="fas fa-info-circle"></i>
                    <span>Select an outpass type to proceed.</span>
                </div>
                <div class="space-y-4">
                    <?php foreach ($templates as $template):
                        $template_name = strtolower(str_replace(' ', '_', $template->getName())); ?>

                        <label for="<?= $template_name ?>_pass" class="flex items-start p-4 border border-gray-200 rounded-md hover:bg-gray-50 transition-all duration-200 cursor-pointer bg-white gap-4 <?= $passType == $template->getId() ? 'ring-2 ring-blue-500' : '' ?>">
                            <input
                                type="radio"
                                name="outpass_type"
                                id="<?= $template_name ?>_pass"
                                value="<?= $template->getId() ?>"
                                data-name="<?= $template_name ?>"
                                class="w-5 h-5 mt-1 text-blue-600 border-gray-300 focus:ring-blue-500 outpass-radio"
                                <?= $passType == strtolower(str_replace(' ', '_', $template->getName())) ? 'checked' : '' ?>>

                            <div class="flex flex-col">
                                <span class="font-normal text-gray-800 text-md">
                                    <?= $template->getName() ?>
                                    <?php if ($template->isSystemTemplate()): ?>
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

                <script>
                    document.querySelectorAll('.outpass-radio').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const type = this.dataset.name;
                            const url = new URL(window.location.href);
                            url.searchParams.set('type', type);
                            window.location.href = url.toString();
                        });
                    });
                </script>
            <?php else: ?>
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
            <?php endif; ?>

            <?php if (!empty($passType)): ?>
                <form id="outpassRequestForm" action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <?php
                        $systemFields = array_filter($templates->getFields()->toArray(), fn($f) => $f->isSystemField());
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
                                    <?= $field->isRequired() ? 'required' : '' ?>
                                    class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <?php
                    // Loop and render custom fields
                    $customFields = array_filter($templates->getFields()->toArray(), fn($f) => !$f->isSystemField());
                    $fieldCount = count($customFields);

                    foreach (array_values($customFields) as $index => $field):
                        $isLastAndOdd = $index === $fieldCount - 1 && $fieldCount % 2 !== 0;
                        $inputName = strtolower(explode(" ", $field->getFieldName())[0]) . '_' . strtolower($field->getFieldType()); ?>
                        <div class="<?= $isLastAndOdd ? 'md:col-span-2' : '' ?>">
                            <label for="<?= $inputName ?>" class="block font-medium text-gray-700">
                                <?= $field->getFieldName() ?>
                                <?php if ($field->isRequired()): ?>
                                    <span class="text-red-500">*</span>
                                <?php endif; ?>
                            </label>
                            <?php if ($field->getFieldType() === 'textarea'): ?>
                                <textarea
                                    name="<?= $inputName ?>"
                                    id="<?= $inputName ?>"
                                    <?= $field->isRequired() ? 'required' : '' ?>
                                    class="w-full mt-1 transition border-gray-300 rounded-md resize-none focus:border-blue-500 focus:ring focus:ring-blue-200"
                                    rows="4"></textarea>
                            <?php else: ?>
                                <input
                                    type="<?= $field->getFieldType() ?>"
                                    name="<?= $inputName ?>"
                                    id="<?= $inputName ?>"
                                    <?= $field->isRequired() ? 'required' : '' ?>
                                    class="w-full mt-1 transition border-gray-300 rounded-md focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>

                    <?php if ($templates->isAllowAttachments()): ?>
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