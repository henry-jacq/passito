import Ajax from './libs/ajax.js';
import Toast from './libs/toast.js';
import Modal from './libs/modal';

// Mobile Menu Toggle Logic
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const mobileMenu = document.getElementById('mobileMenu');
if (mobileMenuToggle && mobileMenu) {
	mobileMenuToggle.addEventListener('click', () => {
		mobileMenu.classList.toggle('hidden');
	});
}

// Profile Dropdown Logic
const profileMenuButton = document.getElementById('profileMenuButton');
const profileMenu = document.getElementById('profileMenu');
if (profileMenuButton && profileMenu) {
	profileMenuButton.addEventListener('click', () => {
		profileMenu.classList.toggle('hidden');
	});
}


// Outpass Request Form
const outpassForm = document.getElementById('outpassRequestForm');
const outpassSubmitButton = document.getElementById('outpassSubmitButton');

if (outpassForm && outpassSubmitButton) {
	outpassForm.addEventListener('submit', async (event) => {
		event.preventDefault();
		outpassSubmitButton.disabled = true;
		outpassSubmitButton.innerHTML = 'Submitting...';
		const formData = new FormData(outpassForm);
		const toast = new Toast();
		const attachmentsInput = document.getElementById('attachments');
		const fileLabel = document.getElementById('fileLabel');
		const filePreview = document.getElementById('filePreview');
		const resetAttachmentsUi = () => {
			if (attachmentsInput) {
				attachmentsInput.value = '';
			}
			if (fileLabel) {
				fileLabel.textContent = 'Upload supporting documents (JPG, PNG, PDF)';
			}
			if (filePreview) {
				filePreview.innerHTML = '';
			}
		};

		try {
			// Capture the query params
			const queryParams = window.location.search;
			const endpoint = `/api/web/student/outpass${queryParams}`;

			// Send the form data to the server
			const response = await Ajax.post(endpoint, formData);
			
			if (response.status) {
				toast.create({ message: response.data.message, position: "bottom-right", type: response.data.type, duration: 4000 });
				outpassForm.reset();
				resetAttachmentsUi();
			} else {
				toast.create({ message: response.data.message, position: "bottom-right", type: response.data.type, duration: 4000 });
			}
		} catch (error) {
			console.error('Error during submission:', error);
			alert('Failed to Submit Outpass Request. Please try again.');
		} finally {
			outpassSubmitButton.disabled = false;
			outpassSubmitButton.innerHTML = 'Submit Request';
		}
	});
}


document.addEventListener("DOMContentLoaded", () => {
	const outpassType = document.getElementById("outpass_type");
	const purposeInput = document.getElementById("purpose");
	const purposeField = document.getElementById("purposeField");
	const attachmentsField = document.getElementById("attachmentsField");
	const fileLabel = document.getElementById("fileLabel");
	const filePreview = document.getElementById("filePreview");
	const attachments = document.getElementById("attachments");

	// Function to toggle purpose and attachments fields
	const toggleFields = () => {
		if (!outpassType || !purposeField || !purposeInput || !attachmentsField) return;
		const selectedValue = outpassType.value;

		if (selectedValue === "home") {
			purposeField.classList.add("hidden");
			purposeField.classList.remove("block");
			purposeInput.removeAttribute("required");
		} else {
			purposeField.classList.add("block");
			purposeField.classList.remove("hidden");
			purposeInput.setAttribute("required", "required");
		}

		if (selectedValue !== "outing") {
			attachmentsField.classList.remove("hidden");
		} else {
			attachmentsField.classList.add("hidden");
		}
	};

	// Event listeners
	if (outpassType) {
		outpassType.addEventListener("change", toggleFields);
	}

	if (attachments) {
		attachments.addEventListener("change", function () {
		const files = this.files;

		if (files.length > 0) {
			if (fileLabel) {
				fileLabel.textContent = `${files.length} file(s) selected`;
			}
			if (filePreview) {
				filePreview.innerHTML = Array.from(files)
					.map(file => `<div class="text-gray-700 text-sm mt-1">• ${file.name}</div>`)
					.join("");
			}
		} else {
			if (fileLabel) {
				fileLabel.textContent = "Upload supporting documents (JPG, PNG, PDF)";
			}
			if (filePreview) {
				filePreview.innerHTML = "";
			}
		}
		});
	}
});

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('.outpass-radio').forEach((radio) => {
		radio.addEventListener('change', function () {
			const type = this.dataset.name;
			if (!type) return;
			const url = new URL(window.location.href);
			url.searchParams.set('type', type);
			window.location.href = url.toString();
		});
	});
});

document.addEventListener('DOMContentLoaded', () => {
	const cancelButton = document.getElementById('cancel-outpass');
	if (!cancelButton) return;

	cancelButton.addEventListener('click', async () => {
		const outpassId = cancelButton.dataset.id;
		if (!outpassId) return;
		const toast = new Toast();

		Modal.open({
			content: `
				<div class="px-2 space-y-6">
					<h3 class="text-xl font-bold text-gray-900">Cancel Outpass</h3>
					<div class="space-y-5">
						<p class="text-gray-700">Are you sure you want to cancel this outpass request?</p>
						<p class="text-sm text-red-600">This action cannot be undone.</p>
					</div>
				</div>
			`,
			actions: [
				{
					label: 'Cancel Outpass',
					class: 'inline-flex justify-center rounded-lg bg-red-600 px-6 py-2 text-sm font-medium text-white shadow-md hover:bg-red-500 transition duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2',
					onClick: async () => {
						try {
							const response = await Ajax.post('/api/web/student/cancel', {
								outpass_id: outpassId
							});

							if (response.ok && response.data?.status) {
								toast.create({ message: response.data.message || 'Outpass cancelled.', position: "bottom-right", type: "success", duration: 4000 });
								window.location.reload();
							} else {
								toast.create({ message: response.data?.message || 'Unable to cancel outpass.', position: "bottom-right", type: "error", duration: 4000 });
							}
						} catch (error) {
							toast.create({ message: 'Unable to cancel outpass.', position: "bottom-right", type: "error", duration: 4000 });
						} finally {
							Modal.close();
						}
					},
				},
				{
					label: 'Keep Outpass',
					class: 'inline-flex justify-center rounded-lg bg-gray-100 px-6 py-2 text-sm font-medium text-gray-700 shadow-md hover:bg-gray-200 transition duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 mx-4 mt-3 sm:mt-0',
					onClick: Modal.close,
				},
			],
			size: 'sm:max-w-lg',
			classes: 'custom-modal-class',
			closeOnBackdropClick: true,
		});
	});
});
