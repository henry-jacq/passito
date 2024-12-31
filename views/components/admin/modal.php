<div id="modal-wrapper" class="hidden fixed inset-0 z-50 overflow-y-auto hs-overlay" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Backdrop -->
  <div class="fixed inset-0 bg-gray-500/75 transition-opacity opacity-0 hs-overlay-open:opacity-100" aria-hidden="true" data-slot="backdrop"></div>
  
  <!-- Modal Container -->
  <div class="flex min-h-full items-center justify-center p-4 text-center sm:items-center sm:p-0">
    <!-- Modal Panel -->
    <div
      id="modal-panel"
      tabindex="-1"
      class="relative transform overflow-hidden bg-white text-left shadow-xl transition-all sm:w-full sm:max-w-lg opacity-0 scale-95 hs-overlay-open:scale-100 hs-overlay-open:opacity-100"
      data-slot="panel"
    >
      <!-- Modal Content -->
      <div id="modal-content" class="bg-gray-100 p-6">
        <!-- Modal Title -->
        <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900">
          Modal Title
        </h3>
        <p id="modal-description" class="mt-2 text-sm text-gray-600">
          Detailed description of the modal's purpose and content.
        </p>
      </div>
      
      <!-- Modal Footer -->
      <div id="modal-footer" class="bg-gray-200 p-4 sm:flex sm:flex-row-reverse sm:px-6">
        <button
          type="button"
          class="py-2 px-4 rounded-lg text-sm font-medium border border-transparent bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          data-hs-overlay="#modal-wrapper"
        >
          Save Changes
        </button>
        <button
          type="button"
          class="mt-3 sm:mt-0 sm:ml-3 py-2 px-4 rounded-lg text-sm font-medium border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
          data-hs-overlay="#modal-wrapper"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</div>
