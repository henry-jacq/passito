import Ajax from './libs/ajax.js';

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

    try {
		const response = await Ajax.post('/api/web/student/outpass', formData);
		
		if (response.status) {
			alert(response.message || 'Outpass Request Submitted Successfully');
			outpassForm.reset();
		} else {
			alert(response.message || 'Failed to Submit Outpass Request');
		}
		
		outpassSubmitButton.disabled = false
		outpassSubmitButton.innerHTML = 'Submit'

    } catch (error) {
        console.error('Error during submission:', error);
        alert('Failed to Submit Outpass Request. Please try again.');
    }
});
