import Ajax from './libs/ajax.js';
import Toast from './libs/toast.js';

// Mobile Menu Toggle Logic
const mobileMenuToggle = document.getElementById('mobileMenuToggle');
const mobileMenu = document.getElementById('mobileMenu');
mobileMenuToggle.addEventListener('click', () => {
	mobileMenu.classList.toggle('hidden');
});

// Profile Dropdown Logic
const profileMenuButton = document.getElementById('profileMenuButton');
const profileMenu = document.getElementById('profileMenu');
profileMenuButton.addEventListener('click', () => {
	profileMenu.classList.toggle('hidden');
});


// Outpass Request Form
const outpassForm = document.getElementById('outpassRequestForm');
const outpassSubmitButton = document.getElementById('outpassSubmitButton');

outpassForm.addEventListener('submit', async (event) => {
    event.preventDefault();
	outpassSubmitButton.disabled = true
	outpassSubmitButton.innerHTML = 'Submitting...'
    const formData = new FormData(outpassForm);
	const toast = new Toast();

    try {
		// Capture the query params
		const queryParams = window.location.search;
		const endpoint = `/api/web/student/outpass${queryParams}`;

		// Send the form data to the server
		const response = await Ajax.post(endpoint, formData);
		
		if (response.status) {
			toast.create({ message: response.data.message, position: "bottom-right", type: response.data.type, duration: 4000 });
			outpassForm.reset();
		} else {
			toast.create({ message: response.data.message, position: "bottom-right", type: response.data.type, duration: 4000 });
		}
		
		outpassSubmitButton.disabled = false
		outpassSubmitButton.innerHTML = 'Submit Request'

    } catch (error) {
        console.error('Error during submission:', error);
        alert('Failed to Submit Outpass Request. Please try again.');
    }
});


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
	outpassType.addEventListener("change", toggleFields);

	attachments.addEventListener("change", function () {
		const files = this.files;

		if (files.length > 0) {
			fileLabel.textContent = `${files.length} file(s) selected`;
			filePreview.innerHTML = Array.from(files)
				.map(file => `<div class="text-gray-700 text-sm mt-1">• ${file.name}</div>`)
				.join("");
		} else {
			fileLabel.textContent = "Upload supporting documents (JPG, PNG, PDF)";
			filePreview.innerHTML = "";
		}
	});
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
