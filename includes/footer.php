        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

    <!-- Custom JS -->
    <script src="<?php echo BASE_URL; ?>assets/js/main.js"></script>

    <?php if(isset($success_message)): ?>
    <script>
        showSuccess('<?php echo $success_message; ?>');
    </script>
    <?php endif; ?>

    <?php if(isset($error_message)): ?>
    <script>
        showError('<?php echo $error_message; ?>');
    </script>
    <?php endif; ?>
</body>
</html>