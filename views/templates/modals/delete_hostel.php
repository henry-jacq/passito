<div class="px-2 space-y-6">
    <h3 class="text-xl font-bold text-gray-900">Delete Hostel</h3>

    <div class="space-y-5">
        <p class="text-gray-700">Are you sure you want to delete the hostel <span class="font-semibold"><?= $hostelName ?? 'this hostel' ?></span>?</p>
        <p class="text-sm text-red-600">All warden assignments linked to this hostel will be deleted.</p>
        <p class="text-sm text-gray-500">If students are assigned to this hostel, deletion will be blocked.</p>
    </div>
</div>
