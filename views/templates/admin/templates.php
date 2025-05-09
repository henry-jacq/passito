<main class="flex-1 p-6 mt-20 overflow-y-auto">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="space-y-1">
            <h2 class="mb-4 text-2xl font-semibold text-gray-800">Manage Templates</h2>
            <p class="max-w-3xl mb-10 text-gray-600 text-md">
                Create outpass templates for students, templates shown based on visibility.
            </p>
        </div>
        <button id="add-outpass-template" class="inline-flex items-center px-5 py-2 text-sm font-medium text-white transition-all duration-200 ease-in-out bg-blue-600 rounded-lg shadow-md hover:bg-blue-700 focus:ring focus:ring-blue-400">
            <i class="mr-2 fa-solid fa-plus"></i> Add Template
        </button>
    </div>

    <div class="p-4 mb-8 border-l-4 rounded-md shadow-sm bg-blue-500/20 border-blue-800/80">
        <h3 class="mb-1 font-semibold text-blue-800">How Templates Work</h3>
        <ul class="pl-5 space-y-1 text-sm text-blue-800 list-disc">
            <li>Create a <strong>customized templates</strong> rather than system templates for special cases</li>
            <li>There will be fields are that common for all type of outpass like from and return timings, places and all</li>
            <li>Set required fields like location, guardian contact, or documents</li>
            <li>Each template is visible only to allowed <strong>institutions</strong> and <strong>student categories</strong></li>
        </ul>
    </div>

    <?php
    if (!empty($templates)): ?>



        <div class="overflow-x-auto bg-white rounded-lg shadow-md ring-1 ring-gray-100">
            <table class="w-full text-sm table-auto">
                <thead class="text-xs tracking-wide text-gray-600 uppercase bg-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left">Template</th>
                        <th class="px-5 py-3 text-left">Visibility</th>
                        <th class="px-5 py-3 text-left">Required Fields</th>
                        <th class="px-5 py-3 text-left">Status</th>
                        <th class="px-5 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php foreach ($templates as $template): ?>

                        <tr class="transition hover:bg-gray-50">
                            <td class="px-5 py-4">
                                <div class="inline-flex items-center justify-between mb-1">
                                    <div class="font-medium text-gray-800"><?= ucwords($template->getName()) ?></div>
                                    <?php if ($template->isSystemTemplate()) {
                                        $templateType = "System Template";
                                        $templateTypeClass = "bg-gray-200 text-gray-700";
                                    } else {
                                        $templateType = "Custom Template";
                                        $templateTypeClass = "bg-blue-200 text-blue-700";
                                    } ?>

                                    <span class="ms-4 px-2 py-0.5 text-xs <?= $templateTypeClass ?> rounded-full">
                                        <?= $templateType ?>
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500"><?= ucfirst($template->getDescription()) ?></p>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium text-purple-800 bg-purple-100 rounded-md">
                                    All Years • B.Tech
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-wrap gap-2">
                                    <?php
                                    // display non-system fields
                                    foreach ($template->getFields() as $field) {
                                        if (!$field->isSystemField()) {
                                            echo '<span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded">' . ucwords($field->getFieldName()) . '</span>';
                                        }
                                    }
                                    if ($template->isAllowAttachments()) {
                                        echo '<span class="px-2 py-0.5 text-xs bg-gray-100 text-gray-800 rounded">Attachment</span>';
                                    } ?>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <?php if ($template->isActive()) {
                                    $statusClass = "bg-green-100 text-green-700";
                                    $statusText = "Active";
                                } else {
                                    $statusClass = "bg-red-100 text-red-700";
                                    $statusText = "Inactive";
                                } ?>
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full <?= $statusClass ?>">
                                    <?= $statusText ?>
                                </span>
                            </td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button class="px-3 py-1 text-sm font-normal text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
