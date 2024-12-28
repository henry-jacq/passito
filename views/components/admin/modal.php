<div id="modal-wrapper" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity opacity-0" aria-hidden="true" data-slot="backdrop"></div>
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
    <div
      id="modal-panel"
      class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg opacity-0 scale-95"
      data-slot="panel"
    >
      <!-- Modal Content Slot -->
      <div id="modal-content" class="p-6"></div>
      
      <!-- Modal Footer Slot -->
      <div id="modal-footer" class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6"></div>
    </div>
  </div>
</div>
