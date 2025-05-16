<div class="px-2 space-y-6">
    <h3 class="text-2xl font-bold text-gray-900">Outpass Files</h3>

    <div class="space-y-4">
        <p class="text-gray-700 text-md">These are the documents submitted along with the outpass request #<?= $outpass_id ?></p>

        <ul class="space-y-3 divide-y divide-gray-200">
            <?php foreach ($attachments as $index => $attachment): ?>
                <li class="pt-3">
                    <a href="<?= $attachment ?>" target="_blank"
                        class="flex items-center justify-between px-4 py-2 transition duration-200 bg-white border border-gray-300 rounded-lg hover:bg-blue-50">
                        <span class="font-medium text-blue-600 truncate">
                            <i class="mr-2 fa-solid fa-link"></i>
                            Attachment <?= $index + 1 . ' (' . basename($attachment) . ')' ?>
                        </span>
                        <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>