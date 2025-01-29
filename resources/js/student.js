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
		const response = await Ajax.post('/api/web/student/outpass', formData);
		
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


// Toggle Purpose Field
const outpassType = document.getElementById('outpass_type');

outpassType.addEventListener('change', () => {
	const selectedValue = outpassType.value;
	const purposeInput = document.getElementById('purpose');
	const purposeField = document.getElementById('purposeField');
	
	if (selectedValue == 'home') {
		purposeField.classList.add("hidden");
		purposeField.classList.remove("block");
		purposeInput.removeAttribute("required");
	} else {
		purposeField.classList.add("block");
		purposeField.classList.remove("hidden");
		purposeInput.setAttribute("required", "required");
	}
});
