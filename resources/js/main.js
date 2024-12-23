// Example vanilla JS code
document.addEventListener('DOMContentLoaded', () => {
  console.log('Hello Henry, Vite with PHP!');
});

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