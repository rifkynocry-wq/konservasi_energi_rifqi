
<?php
$pageTitle = $pageTitle ?? 'Konservasi Energi';
$cur = basename($_SERVER['PHP_SELF']);

function nav_active($files) {
    global $cur;
    return in_array($cur, (array)$files) ? 'active' : '';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title><?= e($pageTitle) ?> - Konservasi Energi</title>

<link rel="stylesheet" href="assets/style.css">

<style>
/* =================================
   LAYOUT UTAMA
================================= */

.wrapper {
    display: block !important;
    min-height: 100vh;
    width: 100%;
}

.main-content {
    width: 100% !important;
    margin: 0 !important;
    min-height: 100vh;
    box-sizing: border-box;
    padding: 25px;
}

/* =================================
   TOMBOL TITIK TIGA
================================= */

.topbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    margin-bottom: 25px;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.menu-toggle {
    width: 45px;
    height: 45px;
    border: 1px solid rgba(34, 211, 238, 0.25);
    border-radius: 12px;
    background: linear-gradient(135deg, #0e7490, #155e75);
    color: white;
    font-size: 27px;
    font-weight: bold;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: 0.25s;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.menu-toggle:hover {
    background: #0891b2;
    transform: translateY(-2px);
}

.topbar-title {
    color: #e2e8f0;
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

/* =================================
   SIDEBAR / MENU POPUP
================================= */

.sidebar {
    position: fixed !important;
    top: 0;
    left: 0;
    width: 270px !important;
    height: 100vh;
    box-sizing: border-box;
    padding: 25px 20px;
    background: linear-gradient(180deg, #0f172a, #102c46) !important;
    border-right: 1px solid rgba(34, 211, 238, 0.15);
    z-index: 1002;
    transform: translateX(-105%);
    transition: transform 0.3s ease;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    box-shadow: 10px 0 35px rgba(0,0,0,0.3);
}

.sidebar.active {
    transform: translateX(0);
}

/* Judul sidebar */

.sidebar .title {
    color: #f1f5f9;
    font-size: 19px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 25px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.15);
}

.sidebar .logo {
    font-size: 25px;
}

/* Tombol tutup */

.sidebar-close {
    position: absolute;
    top: 19px;
    right: 17px;
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 9px;
    background: rgba(255,255,255,0.08);
    color: white;
    font-size: 24px;
    cursor: pointer;
}

.sidebar-close:hover {
    background: #0e7490;
}

/* Link menu */

.menu-links {
    display: flex;
    flex-direction: column;
    gap: 9px;
    margin-top: 25px;
}

.menu-links a {
    display: block;
    color: #cbd5e1;
    text-decoration: none;
    padding: 13px 15px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.2s;
}

.menu-links a:hover {
    color: white;
    background: rgba(14, 116, 144, 0.35);
}

.menu-links a.active {
    color: white;
    background: linear-gradient(135deg, #0e7490, #0891b2);
    box-shadow: 0 5px 15px rgba(8, 145, 178, 0.2);
}

/* =================================
   FOOTER SIDEBAR
================================= */

.sidebar-footer {
    margin-top: auto;
    padding-top: 20px;
    border-top: 1px solid rgba(148, 163, 184, 0.15);
}

.sidebar-footer .user {
    display: block;
    color: #e2e8f0;
    font-size: 14px;
    margin-bottom: 15px;
}

.sidebar-footer .user small {
    display: block;
    color: #94a3b8;
    margin-top: 5px;
}

.sidebar-footer .btn-logout {
    display: block;
    text-align: center;
    text-decoration: none;
    color: white;
    background: rgba(220, 38, 38, 0.18);
    border: 1px solid rgba(248, 113, 113, 0.25);
    padding: 11px;
    border-radius: 9px;
    transition: 0.2s;
}

.sidebar-footer .btn-logout:hover {
    background: #dc2626;
}

/* =================================
   OVERLAY
================================= */

.menu-overlay {
    position: fixed;
    inset: 0;
    background: rgba(2, 6, 23, 0.65);
    backdrop-filter: blur(3px);
    z-index: 1001;
    opacity: 0;
    visibility: hidden;
    transition: 0.3s;
}

.menu-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* =================================
   ALERT
================================= */

.alert {
    margin-bottom: 20px;
}

/* =================================
   RESPONSIVE
================================= */

@media (max-width: 600px) {
    .main-content {
        padding: 15px;
    }

    .topbar-title {
        font-size: 17px;
    }

    .sidebar {
        width: 260px !important;
    }
}
</style>

</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR MENU -->
    <div class="sidebar" id="sideMenu">

        <div class="title">
            <span class="logo">⚡</span>
            Konservasi Energi
        </div>

        <button
            type="button"
            class="sidebar-close"
            id="closeMenu"
            aria-label="Tutup menu">
            ×
        </button>

        <div class="menu-links">

            <a href="index.php"
               class="<?= nav_active('index.php') ?>">
                <span>▦</span> Dashboard
            </a>

            <?php if (can('siswa.view')): ?>
            <a href="siswa.php"
               class="<?= nav_active([
                   'siswa.php',
                   'siswa_tambah.php',
                   'siswa_edit.php'
               ]) ?>">
                <span>♙</span> Siswa
            </a>
            <?php endif; ?>

            <?php if (can('perangkat.view')): ?>
            <a href="perangkat.php"
               class="<?= nav_active([
                   'perangkat.php',
                   'perangkat_tambah.php',
                   'perangkat_edit.php'
               ]) ?>">
                <span>⚙</span> Perangkat
            </a>
            <?php endif; ?>

            <a href="checklist.php"
               class="<?= nav_active([
                   'checklist.php',
                   'checklist_tambah.php',
                   'checklist_detail.php'
               ]) ?>">
                <span>☑</span> Checklist
            </a>

        </div>

        <div class="sidebar-footer">

            <span class="user">
                <?= e($_SESSION['nama'] ?? 'Pengguna') ?>
                <small>
                    (<?= e($_SESSION['level'] ?? '-') ?>)
                </small>
            </span>

            <a href="logout.php" class="btn-logout">
                ↪ Logout
            </a>

        </div>

    </div>

    <!-- OVERLAY -->
    <div class="menu-overlay" id="menuOverlay"></div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <!-- HEADER ATAS -->
        <div class="topbar">

            <div class="topbar-left">

                <button
                    type="button"
                    class="menu-toggle"
                    id="menuToggle"
                    aria-label="Buka menu"
                    aria-expanded="false">
                    ⋮
                </button>

                <h2 class="topbar-title">
                    <?= e($pageTitle) ?>
                </h2>

            </div>

        </div>

        <!-- NOTIFIKASI -->
        <?php if ($m = get_flash()): ?>
            <div class="alert success">
                <?= e($m) ?>
            </div>
        <?php endif; ?>

        <!-- KONTEN HALAMAN -->
        <div class="container">

<script>
document.addEventListener('DOMContentLoaded', function () {

    const menuToggle = document.getElementById('menuToggle');
    const sideMenu = document.getElementById('sideMenu');
    const closeMenu = document.getElementById('closeMenu');
    const menuOverlay = document.getElementById('menuOverlay');

    function bukaMenu() {
        sideMenu.classList.add('active');
        menuOverlay.classList.add('active');
        menuToggle.setAttribute('aria-expanded', 'true');
    }

    function tutupMenu() {
        sideMenu.classList.remove('active');
        menuOverlay.classList.remove('active');
        menuToggle.setAttribute('aria-expanded', 'false');
    }

    menuToggle.addEventListener('click', function () {
        if (sideMenu.classList.contains('active')) {
            tutupMenu();
        } else {
            bukaMenu();
        }
    });

    closeMenu.addEventListener('click', tutupMenu);
    menuOverlay.addEventListener('click', tutupMenu);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            tutupMenu();
        }
    });

    sideMenu.querySelectorAll('.menu-links a').forEach(function (link) {
        link.addEventListener('click', tutupMenu);
    });

});
</script>
