<?php
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laundryku - Dashboard Transaksi Laundry Modern</title>
    <meta name="description" content="Sistem pengelolaan transaksi laundry modern berbasis Firebase Realtime Database">

    <!-- Google Fonts & FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Modular CSS (per fitur) -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/stats.css">
    <link rel="stylesheet" href="css/controls.css">
    <link rel="stylesheet" href="css/table.css">
    <link rel="stylesheet" href="css/modal.css">
    <link rel="stylesheet" href="css/services.css">
    <link rel="stylesheet" href="css/toast.css">
    <link rel="stylesheet" href="css/responsive.css">
</head>
<body>
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}
?>

    <!-- Ambient Glowing Blobs -->
    <div class="ambient-glow blob-1"></div>
    <div class="ambient-glow blob-2"></div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="toast-container"></div>

    <div class="app-container">

        <!-- Header Component -->
        <?php include __DIR__ . '/components/header.php'; ?>

        <!-- Stats Component -->
        <?php include __DIR__ . '/components/stats.php'; ?>

        <!-- Controls (Search & Filter) Component -->
        <?php include __DIR__ . '/components/controls.php'; ?>

        <!-- Table Component -->
        <?php include __DIR__ . '/components/table.php'; ?>

    </div>

    <!-- Modal Component -->
    <?php include __DIR__ . '/components/modal.php'; ?>

    <!-- Inject PHP services config to JS -->
    <script>
        const SERVICES = <?php echo json_encode($services); ?>;
    </script>

    <!-- Modular JS (per fitur, urutan penting) -->
    <script src="js/utils.js"></script>
    <script src="js/toast.js"></script>
    <script src="js/modal.js"></script>
    <script src="js/table.js"></script>
    <script src="js/filter.js"></script>
    <script src="js/form.js"></script>
    <script src="js/app.js"></script>
</body>
</html>
