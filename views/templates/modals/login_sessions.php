<div class="space-y-4">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Login Sessions</h3>
        <p class="text-sm text-gray-600">Active and recent sessions for this admin account.</p>
    </div>

    <?php if (empty($sessions)): ?>
        <p class="text-sm text-gray-500">No sessions found.</p>
    <?php else: ?>
        <div class="max-h-[360px] overflow-y-auto border border-gray-200 rounded-lg">
            <table class="min-w-full text-sm">
                <thead class="sticky top-0 bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-gray-700">Device</th>
                        <th class="px-3 py-2 text-left text-gray-700">IP</th>
                        <th class="px-3 py-2 text-left text-gray-700">Status</th>
                        <th class="px-3 py-2 text-left text-gray-700">Login</th>
                        <th class="px-3 py-2 text-right text-gray-700">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    <?php foreach ($sessions as $session): ?>
                        <tr>
                            <td class="px-3 py-2 max-w-[220px] truncate" title="<?= htmlspecialchars($session['user_agent'] ?? 'Unknown') ?>">
                                <?= htmlspecialchars($session['user_agent'] ?? 'Unknown Device') ?>
                            </td>
                            <td class="px-3 py-2"><?= htmlspecialchars($session['ip_address'] ?? 'N/A') ?></td>
                            <td class="px-3 py-2">
                                <?php if (!empty($session['is_current'])): ?>
                                    <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded">Current</span>
                                <?php elseif (!empty($session['is_active'])): ?>
                                    <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded">Active</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 text-xs text-gray-700 bg-gray-100 rounded">Revoked</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-3 py-2 text-gray-600"><?= htmlspecialchars($session['created_at'] ?? '-') ?></td>
                            <td class="px-3 py-2 text-right">
                                <?php if (!empty($session['is_active'])): ?>
                                    <button
                                        class="px-2 py-1 text-xs font-medium text-red-700 border border-red-200 rounded revoke-login-session hover:bg-red-50"
                                        data-token-id="<?= htmlspecialchars($session['token_id']) ?>"
                                        <?= !empty($session['is_current']) ? 'data-current="1"' : '' ?>
                                    >
                                        Revoke
                                    </button>
                                <?php else: ?>
                                    <button
                                        class="px-2 py-1 text-xs font-medium text-gray-700 border border-gray-300 rounded delete-login-session hover:bg-gray-50"
                                        data-token-id="<?= htmlspecialchars($session['token_id']) ?>"
                                    >
                                        Delete
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
