<div id="modal-wrapper" class="fixed inset-0 z-50 hidden overflow-y-auto hs-overlay" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  <!-- Backdrop -->
  <div class="fixed inset-0 transition-opacity opacity-0 bg-gray-500/75 hs-overlay-open:opacity-100" aria-hidden="true" data-slot="backdrop"></div>
  
  <!-- Modal Container -->
  <div class="flex items-center justify-center min-h-full p-4 text-center sm:items-center sm:p-0">
    <!-- Modal Panel -->
    <div
      id="modal-panel"
      tabindex="-1"
      class="relative overflow-hidden text-left transition-all transform scale-95 bg-white shadow-xl opacity-0 sm:w-full sm:max-w-lg hs-overlay-open:scale-100 hs-overlay-open:opacity-100"
      data-slot="panel"
    >
      <!-- Modal Content -->
      <div id="modal-content" class="p-6 bg-gray-100">
        <!-- Modal Title -->
        <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900">
          Modal Title
        </h3>
        <p id="modal-description" class="mt-2 text-sm text-gray-600">
          Detailed description of the modal's purpose and content.
        </p>
      </div>
      
      <!-- Modal Footer -->
      <div id="modal-footer" class="p-4 bg-gray-200 sm:flex sm:flex-row-reverse">
        <button
          type="button"
          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          data-hs-overlay="#modal-wrapper"
        >
          Save Changes
        </button>
        <button
          type="button"
          class="px-4 py-2 mt-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg sm:mt-0 sm:ml-3 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
          data-hs-overlay="#modal-wrapper"
        >
          Close
        </button>
      </div>
    </div>
  </div>
</div>
