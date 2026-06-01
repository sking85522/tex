<?php
// admin/modules/messages/index.php
Auth::requireLogin();

$dataFile = BASE_PATH . '/../data/messages.json';
$messages = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : [];
if (!is_array($messages)) $messages = [];

// Reverse to show newest first
$messages = array_reverse($messages);
?>
<div class="bg-white p-6 rounded shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Contact Form Messages</h2>
    </div>

    <?php if (empty($messages)): ?>
        <p class="text-gray-500">No messages have been submitted yet.</p>
    <?php else: ?>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="border-b py-4 px-4 font-semibold text-gray-700">Date</th>
                        <th class="border-b py-4 px-4 font-semibold text-gray-700">Name</th>
                        <th class="border-b py-4 px-4 font-semibold text-gray-700">Email</th>
                        <th class="border-b py-4 px-4 font-semibold text-gray-700">Subject</th>
                        <th class="border-b py-4 px-4 font-semibold text-gray-700">Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($messages as $msg): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="border-b py-4 px-4 text-sm"><?= htmlspecialchars($msg['date'] ?? '') ?></td>
                        <td class="border-b py-4 px-4 text-sm font-medium"><?= htmlspecialchars($msg['name'] ?? '') ?></td>
                        <td class="border-b py-4 px-4 text-sm text-blue-600"><a href="mailto:<?= htmlspecialchars($msg['email'] ?? '') ?>"><?= htmlspecialchars($msg['email'] ?? '') ?></a></td>
                        <td class="border-b py-4 px-4 text-sm font-semibold"><?= htmlspecialchars($msg['subject'] ?? '') ?></td>
                        <td class="border-b py-4 px-4 text-sm text-gray-600 max-w-xs truncate" title="<?= htmlspecialchars($msg['message'] ?? '') ?>"><?= htmlspecialchars($msg['message'] ?? '') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
