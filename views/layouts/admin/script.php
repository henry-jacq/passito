<script src="/js/vendor/popper.min.js"></script>
<script src="/js/vendor/bootstrap.min.js"></script>
<script src="/js/vendor/jquery.min.js"></script>
<script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"></script>
<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://kit.fontawesome.com/cd2caad5e8.js" crossorigin="anonymous"></script>
<script src="/js/app.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var toggler = document.querySelector(".sidebar-toggler");
        var sidebar = document.querySelector(".sidebar");
        var contentContainer = document.querySelector(".content-container");

        // Function to handle sidebar toggle
        function toggleSidebar() {
            if (!sidebar.getAttribute('style')) {
                if (window.innerWidth >= 768) {
                    sidebar.style.left = '-280px';
                } else {
                    sidebar.style.left = '0px';
                }
            } else {
                sidebar.style.left = (sidebar.style.left === '0px' ? '-280px' : '0px');
            }
            if (sidebar.style.left == '-280px') {
                contentContainer.style.left = '0px';
            }
        }

        // Event listener for sidebar toggler
        toggler.addEventListener("click", function() {
            toggleSidebar();
        });

        // Event listener for window resize
        window.addEventListener("resize", function() {
            if (sidebar.getAttribute('style')) {
                sidebar.style = null;
            }
        });
    });
</script>