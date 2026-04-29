        </div> <!-- End Content Wrapper -->
    </div> <!-- End Main Content -->

    <!-- Bootstrap 5 JS Bundle with Popper -->

    <!-- Admin Floating AI Assistant (Stateless Fetch) -->
    <div id="admin-ai-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 1050;">
        <button id="ai-toggle-btn" class="btn btn-primary rounded-circle shadow-lg" style="width: 60px; height: 60px;">
            <i class="bi bi-robot fs-3"></i>
        </button>
        <div id="ai-chat-box" class="card shadow-lg d-none" style="position: absolute; bottom: 70px; right: 0; width: 350px; height: 450px; border-radius: 15px; overflow: hidden;">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-robot"></i> Admin AI Assistant</h6>
                <button id="ai-close-btn" class="btn-close btn-close-white" aria-label="Close"></button>
            </div>
            <div id="ai-chat-history" class="card-body" style="overflow-y: auto; background: #f8f9fa;">
                <div class="mb-2"><strong>HRITIK:</strong> Hello Admin. System is running smoothly. How can I assist you today?</div>
            </div>
            <div class="card-footer bg-white">
                <div class="input-group">
                    <input type="text" id="ai-cmd-input" class="form-control" placeholder="Ask AI to write a blog, check leads...">
                    <button id="ai-send-btn" class="btn btn-primary"><i class="bi bi-send-fill"></i></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const aiToggle = document.getElementById("ai-toggle-btn");
        const aiBox = document.getElementById("ai-chat-box");
        const aiClose = document.getElementById("ai-close-btn");
        const aiInput = document.getElementById("ai-cmd-input");
        const aiSend = document.getElementById("ai-send-btn");
        const aiHistory = document.getElementById("ai-chat-history");

        aiToggle.addEventListener("click", () => aiBox.classList.remove("d-none"));
        aiClose.addEventListener("click", () => aiBox.classList.add("d-none"));

        async function sendAdminCommand() {
            const cmd = aiInput.value.trim();
            if (!cmd) return;

            aiHistory.innerHTML += `<div class="mb-2 text-end text-primary"><strong>You:</strong> ${cmd}</div>`;
            aiInput.value = "";
            aiHistory.innerHTML += `<div class="mb-2 text-muted" id="ai-loading"><em>Thinking...</em></div>`;
            aiHistory.scrollTop = aiHistory.scrollHeight;

            try {
                // We use the same hritik_api but we could pass a flag for admin mode
                const formData = new FormData();
                formData.append("action", "chat");
                formData.append("message", "ADMIN COMMAND: " + cmd);

                const response = await fetch("../hritik_api.php", {
                    method: "POST",
                    body: formData
                });

                const data = await response.json();
                document.getElementById("ai-loading").remove();

                if (data.success) {
                    aiHistory.innerHTML += `<div class="mb-2"><strong>HRITIK:</strong> ${data.message}</div>`;
                } else {
                    aiHistory.innerHTML += `<div class="mb-2 text-danger"><strong>System:</strong> ${data.message}</div>`;
                }
            } catch (err) {
                document.getElementById("ai-loading").remove();
                aiHistory.innerHTML += `<div class="mb-2 text-danger"><strong>Error:</strong> Failed to connect to Brain Core.</div>`;
            }
            aiHistory.scrollTop = aiHistory.scrollHeight;
        }

        aiSend.addEventListener("click", sendAdminCommand);
        aiInput.addEventListener("keypress", (e) => { if (e.key === "Enter") sendAdminCommand(); });
    </script>

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
