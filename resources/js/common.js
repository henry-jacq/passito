// common.js
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const notificationButton = document.getElementById('notificationButton');
    const notificationDropdown = document.getElementById('notificationDropdown');
    const header = document.querySelector('header');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            adjustHeaderWidth();
        });
    }

    function adjustHeaderWidth() {
        if (window.innerWidth >= 1024) {
            header.style.marginLeft = '16rem';
            header.style.width = `calc(100% - 16rem)`;
        } else {
            header.style.marginLeft = '0';
            header.style.width = '100%';
        }
    }

    window.addEventListener('resize', adjustHeaderWidth);
    adjustHeaderWidth();

    if (notificationButton) {
        notificationButton.addEventListener('click', (event) => {
            event.stopPropagation();
            notificationDropdown.classList.toggle('hidden');
            notificationDropdown.classList.toggle('scale-95');
            notificationDropdown.classList.toggle('opacity-0');
        });
    }

    window.addEventListener('click', (event) => {
        if (!notificationDropdown.classList.contains('hidden') &&
            !notificationDropdown.contains(event.target) &&
            !notificationButton.contains(event.target)) {
            notificationDropdown.classList.add('hidden');
            notificationDropdown.classList.add('scale-95');
            notificationDropdown.classList.add('opacity-0');
        }
    });
});
