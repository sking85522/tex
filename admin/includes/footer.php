        </div> <!-- End Content Wrapper -->
    </div> <!-- End Main Content -->

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Simple Sidebar Toggle Logic for Mobile
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.querySelector('.main-content');
        const toggleBtn = document.querySelector('.btn-toggle');

        toggleBtn.addEventListener('click', function() {
            if (window.innerWidth > 768) {
                if (sidebar.style.left === '-250px') {
                    sidebar.style.left = '0';
                    mainContent.style.marginLeft = '250px';
                } else {
                    sidebar.style.left = '-250px';
                    mainContent.style.marginLeft = '0';
                }
            } else {
                sidebar.classList.toggle('show');
            }
        });
    </script>
</body>
</html>
