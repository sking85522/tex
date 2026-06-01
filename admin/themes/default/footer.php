            </div>
        </main>

        <?php if (Auth::check()): ?>
            <footer class="bg-transparent border-t border-gray-200 dark:border-gray-800 text-center text-sm p-4 mt-auto text-gray-500">
                &copy; <?= date('Y') ?> <?= h(APP_NAME) ?>. Powered by Intelligence.
            </footer>
        <?php endif; ?>
    </div>

    <!-- Alpine.js for some simple interactions if needed -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
