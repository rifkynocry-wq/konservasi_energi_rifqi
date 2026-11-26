
<?php
require 'auth.php';

$pageTitle = 'Dashboard';
include 'includes/header.php';

// Fungsi menghitung data
function hitung($conn, $tabel) {
    $query = mysqli_query($conn, "SELECT COUNT(*) AS total FROM `$tabel`");

    if (!$query) return 0;

    return mysqli_fetch_assoc($query)['total'];
}

// Tanggal Indonesia
$hari = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
$bulan = ['', 'Januari','Februari','Maret','April','Mei','Juni',
          'Juli','Agustus','September','Oktober','November','Desember'];

$tanggal = $hari[date('w')] . ', ' . date('j') . ' ' .
           $bulan[(int)date('n')] . ' ' . date('Y');

// Checklist terbaru
$terbaru = mysqli_query($conn, "
    SELECT c.id_checklist, c.tanggal, c.jam,
           s.nama, s.kelas,
           (SELECT COUNT(*) FROM detail_checklist d
            WHERE d.id_checklist = c.id_checklist) AS jml
    FROM checklist c
    LEFT JOIN siswa s ON s.nis = c.nis
    ORDER BY c.tanggal DESC, c.jam DESC
    LIMIT 5
");
?>

<style>
/* =====================================
   DEEP SEA DASHBOARD
===================================== */

body:has(.sea-dashboard) {
    background:
        radial-gradient(circle at 10% 10%, rgba(0,190,210,.12), transparent 30%),
        radial-gradient(circle at 90% 90%, rgba(0,100,180,.13), transparent 35%),
        linear-gradient(140deg,#031522,#06283e,#041827);
    color: #e5f8ff;
}

/* MAIN */
.sea-dashboard {
    max-width: 1500px;
    margin: auto;
    padding: 25px;
    color: #e5f8ff;
}

/* HEADER */
.sea-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
    margin-bottom: 25px;
}

.sea-brand {
    display: flex;
    align-items: center;
    gap: 13px;
}

.sea-brand-icon {
    width: 48px;
    height: 48px;
    display: grid;
    place-items: center;
    border-radius: 14px;
    background: linear-gradient(145deg,#087e91,#07516b);
    border: 1px solid #23869a;
    font-size: 24px;
}

.sea-brand h2 {
    margin: 0;
    color: #e7faff;
    font-size: 20px;
}

.sea-brand small {
    display: block;
    color: #79b8cb;
    font-size: 10px;
    letter-spacing: 1.5px;
}

.sea-date {
    padding: 11px 16px;
    background: #08283d;
    border: 1px solid #18536a;
    border-radius: 11px;
    color: #9bdbea;
    font-size: 12px;
}

/* HERO SECTION */
.sea-hero {
    display: grid;
    grid-template-columns: 1.2fr .8fr;
    align-items: center;
    gap: 20px;
    padding: 32px;
    border-radius: 23px;
    background:
        radial-gradient(circle at 85% 20%,rgba(103,232,249,.2),transparent 30%),
        linear-gradient(120deg,#07334b,#075b72 60%,#087e91);
    border: 1px solid rgba(103,232,249,.25);
    box-shadow: 0 15px 40px rgba(0,0,0,.22);
    overflow: hidden;
    position: relative;
}

.sea-hero-content {
    position: relative;
    z-index: 2;
}

.sea-subtitle {
    color: #7ce6f1;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 2px;
}

.sea-hero h1 {
    color: white;
    font-size: clamp(25px,4vw,36px);
    margin: 12px 0;
}

.sea-hero p {
    color: #c4e8f1;
    line-height: 1.8;
    max-width: 550px;
    font-size: 14px;
}

.sea-user-role {
    display: inline-block;
    padding: 5px 12px;
    background: rgba(103,232,249,.15);
    border: 1px solid rgba(103,232,249,.4);
    color: #a5f3fc;
    border-radius: 30px;
    font-size: 11px;
    vertical-align: middle;
}

/* HERO IMAGE */
.sea-hero-image {
    width: 100%;
    max-width: 390px;
    justify-self: center;
    position: relative;
    z-index: 1;
}

.sea-hero-image svg {
    display: block;
    width: 100%;
    height: auto;
    filter: drop-shadow(0 15px 20px rgba(0,0,0,.2));
}

/* BUTTONS */
.sea-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 20px;
}

.sea-dashboard .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 10px;
    font-size: 12px;
    font-weight: 700;
    text-decoration: none;
    cursor: pointer;
    transition: .2s;
}

.sea-dashboard .btn:hover {
    transform: translateY(-3px);
    filter: brightness(1.15);
}

.sea-dashboard .btn.primary {
    background: linear-gradient(135deg,#16b8cf,#087e9b);
    border: 1px solid #4bd8e8;
    color: white;
}

.sea-dashboard .btn.ghost {
    background: rgba(3,34,50,.3);
    border: 1px solid rgba(165,243,252,.35);
    color: #e0faff;
}

.sea-dashboard .btn.outline {
    background: transparent;
    border: 1px solid #25849b;
    color: #67e8f9;
}

/* SECTION TITLE */
.sea-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin: 30px 0 15px;
}

.sea-section h3 {
    margin: 0;
    color: #e5faff;
    font-size: 17px;
}

.sea-section small {
    color: #78aabd;
    font-size: 11px;
}

/* STATISTIC CARDS */
.sea-stats {
    display: grid;
    grid-template-columns: repeat(3,minmax(0,1fr));
    gap: 16px;
}

.sea-stat {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 21px;
    background: linear-gradient(145deg,#0d3b53,#08283e);
    border: 1px solid #19536b;
    border-radius: 17px;
    transition: .25s;
}

.sea-stat:hover {
    border-color: #45d6e7;
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,180,210,.1);
}

.sea-stat h4 {
    color: #a4cbd8;
    font-size: 12px;
    margin: 0 0 8px;
}

.sea-stat-number {
    color: #67e8f9;
    font-size: 34px;
    font-weight: 850;
}

.sea-stat small {
    display: block;
    color: #7faabd;
    margin-top: 5px;
    font-size: 11px;
}

.sea-stat-icon {
    width: 55px;
    height: 55px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    background: #10485b;
    border: 1px solid #20657a;
    border-radius: 16px;
    font-size: 26px;
}

/* CONTENT COLUMNS */
.sea-content-grid {
    display: grid;
    grid-template-columns: minmax(0,1.7fr) minmax(260px,.8fr);
    gap: 20px;
    margin-top: 25px;
    align-items: start;
}

/* PANEL */
.sea-panel {
    min-width: 0;
    overflow: hidden;
    background: #08283e;
    border: 1px solid #174b63;
    border-radius: 17px;
    box-shadow: 0 10px 28px rgba(0,0,0,.17);
}

.sea-panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    padding: 18px 20px;
    background: linear-gradient(100deg,#0b3a52,#0a3047);
    border-bottom: 1px solid #174b63;
}

.sea-panel-header h3 {
    color: #e5faff;
    font-size: 15px;
    margin: 0;
}

.sea-panel-header a {
    color: #67e8f9;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none;
}

/* TABLE */
.sea-table-wrap {
    overflow-x: auto;
}

.sea-dashboard table {
    width: 100%;
    min-width: 600px;
    border-collapse: collapse;
    margin: 0;
    color: #d9f3fa;
    background: #08283e;
    box-shadow: none;
    border-radius: 0;
}

.sea-dashboard table th {
    padding: 14px;
    background: #10516a;
    color: #a5f3fc;
    border-bottom: 1px solid #26718a;
    font-size: 11px;
    text-align: left;
    white-space: nowrap;
}

.sea-dashboard table td {
    padding: 14px;
    color: #d6edf5;
    background: #08283e;
    border-bottom: 1px solid rgba(103,232,249,.1);
    font-size: 12px;
}

.sea-dashboard table tbody tr:nth-child(even) td {
    background: #0c354d;
}

.sea-dashboard table tbody tr:hover td {
    background: #124c63;
}

.sea-dashboard table tr:last-child td {
    border-bottom: none;
}

.sea-badge {
    display: inline-block;
    padding: 5px 10px;
    background: rgba(16,125,111,.2);
    color: #80f0d5;
    border: 1px solid rgba(56,217,190,.3);
    border-radius: 30px;
    font-size: 10px;
}

/* QUICK MENU */
.sea-quick-menu {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
    margin-top: 25px;
}

.sea-menu-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 15px;
    background: #092c42;
    border: 1px solid #174d63;
    border-radius: 14px;
    text-decoration: none;
    transition: .2s;
}

.sea-menu-item:hover {
    background: #10445b;
    border-color: #35c6d9;
    transform: translateY(-3px);
}

.sea-menu-icon {
    width: 43px;
    height: 43px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    background: #10485b;
    border-radius: 12px;
    font-size: 21px;
}

.sea-menu-text strong {
    display: block;
    color: #e2f8ff;
    font-size: 13px;
}

.sea-menu-text small {
    display: block;
    color: #83b4c6;
    font-size: 10px;
    margin-top: 4px;
}

/* RIGHT COLUMN */
.sea-side-panel {
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* IMAGE CARD */
.sea-image-card {
    padding: 18px;
    background: linear-gradient(145deg,#0d3b53,#08283e);
    border: 1px solid #19536b;
    border-radius: 17px;
}

.sea-image-card h3 {
    margin: 0 0 12px;
    color: #e5faff;
    font-size: 15px;
}

.sea-image-card svg {
    display: block;
    width: 100%;
    height: auto;
    border-radius: 12px;
}

.sea-image-card p {
    color: #9bc7d5;
    font-size: 12px;
    line-height: 1.7;
    margin: 13px 0 0;
}

/* TIPS */
.sea-tips {
    list-style: none;
    padding: 8px 18px;
    margin: 0;
}

.sea-tips li {
    display: flex;
    gap: 12px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(103,232,249,.12);
    color: #c7e5ef;
    font-size: 12px;
    line-height: 1.7;
}

.sea-tips li:last-child {
    border: none;
}

.sea-tip-icon {
    width: 36px;
    height: 36px;
    flex-shrink: 0;
    display: grid;
    place-items: center;
    background: #10485b;
    border: 1px solid #20657a;
    border-radius: 11px;
    font-size: 17px;
}

.sea-tips strong {
    color: #e1f8ff;
}

.sea-note {
    margin: 0 18px 18px;
    padding: 13px;
    background: rgba(8,126,145,.14);
    border: 1px solid rgba(56,217,232,.2);
    border-radius: 11px;
    color: #a5dce8;
    font-size: 11px;
    line-height: 1.7;
}

/* RESPONSIVE */
@media(max-width:1000px) {
    .sea-content-grid {
        grid-template-columns: 1fr;
    }
}

@media(max-width:750px) {
    .sea-dashboard {
        padding: 15px;
    }

    .sea-hero {
        grid-template-columns: 1fr;
        padding: 23px;
    }

    .sea-hero-image {
        max-width: 280px;
    }

    .sea-stats {
        grid-template-columns: 1fr;
    }

    .sea-header {
        align-items: flex-start;
        flex-direction: column;
    }
}

@media(max-width:500px) {
    .sea-quick-menu {
        grid-template-columns: 1fr;
    }

    .sea-section {
        align-items: flex-start;
        flex-direction: column;
    }

    .sea-buttons {
        flex-direction: column;
    }

    .sea-buttons .btn {
        width: 100%;
        box-sizing: border-box;
    }

    .sea-hero h1 {
        font-size: 25px;
    }
}
/* =====================================
   FIX SCROLL DASHBOARD
===================================== */

html {
    height: auto !important;
    min-height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
}

body:has(.sea-dashboard) {
    height: auto !important;
    min-height: 100vh !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
}

.wrapper {
    display: block !important;
    height: auto !important;
    min-height: 100vh !important;
    overflow: visible !important;
}

.main-content {
    height: auto !important;
    min-height: 100vh !important;
    overflow: visible !important;
    overflow-y: visible !important;
}

.container {
    height: auto !important;
    min-height: 0 !important;
    overflow: visible !important;
}

.sea-dashboard {
    height: auto !important;
    min-height: 0 !important;
    overflow: visible !important;
}

/* Tampilan HP */
@media (max-width: 750px) {
    .main-content {
        padding: 15px !important;
    }
}
</style>

<div class="sea-dashboard">

    <!-- HEADER -->
    <header class="sea-header">

        <div class="sea-brand">
            <div class="sea-brand-icon">🌊</div>

            <div>
                <small>ENERGY MANAGEMENT SYSTEM</small>
                <h2>Konservasi Energi</h2>
            </div>
        </div>

        <div class="sea-date">
            📅 <?= e($tanggal) ?>
        </div>

    </header>

    <!-- HERO -->
    <section class="sea-hero">

        <div class="sea-hero-content">

            <div class="sea-subtitle">
                DASHBOARD OVERVIEW
            </div>

            <h1>
                Selamat datang,
                <?= e($_SESSION['nama'] ?? 'Pengguna') ?>!
            </h1>

            <span class="sea-user-role">
                <?= e($_SESSION['level'] ?? '-') ?>
            </span>

            <p>
                Kelola dan pantau penggunaan energi listrik
                di lingkungan sekolah melalui satu dashboard.
                Mulai dari pencatatan hingga evaluasi perangkat.
            </p>

            <div class="sea-buttons">

                <?php if (can('checklist.add')): ?>
                    <a class="btn primary" href="checklist_tambah.php">
                        + Buat Checklist
                    </a>
                <?php endif; ?>

                <a class="btn ghost" href="checklist.php">
                    Lihat Checklist →
                </a>

            </div>

        </div>

        <!-- ILUSTRASI ENERGI -->
        <div class="sea-hero-image">

            <svg viewBox="0 0 420 300" xmlns="http://www.w3.org/2000/svg">

                <circle cx="320" cy="85" r="48"
                    fill="#67e8f9" opacity=".17"/>

                <circle cx="320" cy="85" r="29"
                    fill="#a5f3fc"/>

                <path d="M0 245 Q100 205 210 240 T420 220 V300 H0Z"
                    fill="#06445c"/>

                <path d="M0 270 Q120 225 240 265 T420 250 V300 H0Z"
                    fill="#07516b"/>

                <!-- Gedung -->
                <rect x="115" y="105" width="185" height="140"
                    rx="10" fill="#0c4058"
                    stroke="#67e8f9" stroke-width="2"/>

                <path d="M100 108 L207 45 L315 108Z"
                    fill="#087e91"
                    stroke="#67e8f9" stroke-width="2"/>

                <!-- Jendela -->
                <g fill="#67e8f9">
                    <rect x="140" y="128" width="35" height="35" rx="5"/>
                    <rect x="190" y="128" width="35" height="35" rx="5"/>
                    <rect x="245" y="128" width="35" height="35" rx="5"/>
                    <rect x="140" y="180" width="35" height="35" rx="5"/>
                    <rect x="245" y="180" width="35" height="35" rx="5"/>
                </g>

                <!-- Pintu -->
                <rect x="190" y="180" width="35" height="65"
                    rx="5" fill="#0b7890"/>

                <!-- Panel Surya -->
                <g transform="translate(25 180)">
                    <path d="M0 0 L95 0 L82 48 L-12 48Z"
                        fill="#07516b"
                        stroke="#67e8f9" stroke-width="2"/>
                    <path d="M25 0 L13 48 M55 0 L43 48 M-4 24 H89"
                        stroke="#67e8f9" stroke-width="1.5"/>
                    <path d="M35 48 L30 75 M55 48 L60 75"
                        stroke="#a5f3fc" stroke-width="4"/>
                </g>

                <!-- Petir -->
                <path d="M355 145 L335 180 H355 L345 207 L382 165 H360 L375 145Z"
                    fill="#a5f3fc"/>

                <!-- Daun -->
                <path d="M75 135 Q35 95 48 65 Q90 72 75 135Z"
                    fill="#22c9b2"/>
                <path d="M75 135 Q80 95 48 65"
                    stroke="#a5f3fc" stroke-width="2"
                    fill="none"/>

                <path d="M345 245 Q365 205 395 220 Q390 252 345 245Z"
                    fill="#22c9b2"/>

                <path d="M345 245 L395 220"
                    stroke="#a5f3fc" stroke-width="2"/>

                <circle cx="80" cy="45" r="5" fill="#a5f3fc"/>
                <circle cx="110" cy="65" r="3" fill="#67e8f9"/>
                <circle cx="375" cy="70" r="5" fill="#a5f3fc"/>

            </svg>

        </div>

    </section>

    <!-- STATISTIK -->
    <div class="sea-section">
        <h3>Ringkasan Statistik</h3>
        <small>Data keseluruhan sistem</small>
    </div>

    <div class="sea-stats">

        <?php if (can('siswa.view')): ?>
        <div class="sea-stat">

            <div>
                <h4>Total Siswa</h4>

                <div class="sea-stat-number">
                    <?= hitung($conn, 'siswa') ?>
                </div>

                <small>Data siswa terdaftar</small>
            </div>

            <div class="sea-stat-icon">🎓</div>

        </div>
        <?php endif; ?>

        <?php if (can('perangkat.view')): ?>
        <div class="sea-stat">

            <div>
                <h4>Total Perangkat</h4>

                <div class="sea-stat-number">
                    <?= hitung($conn, 'perangkat') ?>
                </div>

                <small>Perangkat listrik sekolah</small>
            </div>

            <div class="sea-stat-icon">💡</div>

        </div>
        <?php endif; ?>

        <div class="sea-stat">

            <div>
                <h4>Total Checklist</h4>

                <div class="sea-stat-number">
                    <?= hitung($conn, 'checklist') ?>
                </div>

                <small>Catatan penggunaan perangkat</small>
            </div>

            <div class="sea-stat-icon">✅</div>

        </div>

    </div>

    <!-- MENU -->
    <div class="sea-section">
        <h3>Menu Pintasan</h3>
        <small>Akses fitur dengan cepat</small>
    </div>

    <div class="sea-quick-menu">

        <?php if (can('siswa.view')): ?>
        <a href="siswa.php" class="sea-menu-item">

            <div class="sea-menu-icon">🎓</div>

            <div class="sea-menu-text">
                <strong>Data Siswa</strong>
                <small>Kelola data siswa</small>
            </div>

        </a>
        <?php endif; ?>

        <?php if (can('perangkat.view')): ?>
        <a href="perangkat.php" class="sea-menu-item">

            <div class="sea-menu-icon">💡</div>

            <div class="sea-menu-text">
                <strong>Perangkat</strong>
                <small>Data perangkat listrik</small>
            </div>

        </a>
        <?php endif; ?>

        <a href="checklist.php" class="sea-menu-item">

            <div class="sea-menu-icon">📋</div>

            <div class="sea-menu-text">
                <strong>Checklist</strong>
                <small>Catatan aktivitas</small>
            </div>

        </a>

        <?php if (can('checklist.add')): ?>
        <a href="checklist_tambah.php" class="sea-menu-item">

            <div class="sea-menu-icon">➕</div>

            <div class="sea-menu-text">
                <strong>Tambah Checklist</strong>
                <small>Buat catatan baru</small>
            </div>

        </a>
        <?php endif; ?>

    </div>

    <!-- AKTIVITAS DAN PANEL KANAN -->
    <div class="sea-section">
        <h3>Aktivitas Terbaru</h3>
        <small>Informasi checklist dan edukasi</small>
    </div>

    <div class="sea-content-grid">

        <!-- KOLOM KIRI -->
        <div>

            <section class="sea-panel">

                <div class="sea-panel-header">
                    <h3>📋 Riwayat Checklist</h3>
                    <a href="checklist.php">Lihat semua →</a>
                </div>

                <div class="sea-table-wrap">

                    <table>

                        <thead>
                            <tr>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php
                        $ada = false;

                        if ($terbaru):
                            while ($r = mysqli_fetch_assoc($terbaru)):
                                $ada = true;
                        ?>

                            <tr>

                                <td>
                                    <strong>
                                        <?= e($r['nama'] ?? '-') ?>
                                    </strong>
                                </td>

                                <td><?= e($r['kelas'] ?? '-') ?></td>

                                <td><?= e($r['tanggal']) ?></td>

                                <td><?= e(substr($r['jam'],0,5)) ?></td>

                                <td>
                                    <span class="sea-badge">
                                        <?= (int)$r['jml'] ?> item
                                    </span>
                                </td>

                                <td>
                                    <a class="btn outline"
                                       href="checklist_detail.php?id=<?= (int)$r['id_checklist'] ?>">
                                        Detail
                                    </a>
                                </td>

                            </tr>

                        <?php
                            endwhile;
                        endif;
                        ?>

                        <?php if (!$ada): ?>
                            <tr>
                                <td colspan="6" style="text-align:center;padding:30px;color:#83b4c6;">
                                    Belum ada data checklist.
                                </td>
                            </tr>
                        <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>

        </div>

        <!-- KOLOM KANAN -->
        <aside class="sea-side-panel">

            <!-- GAMBAR EDUKASI -->
            <div class="sea-image-card">

                <h3>🌱 Energi Ramah Lingkungan</h3>

                <svg viewBox="0 0 320 190"
                     xmlns="http://www.w3.org/2000/svg">

                    <rect width="320" height="190" rx="12"
                        fill="#07334b"/>

                    <circle cx="255" cy="45" r="25"
                        fill="#a5f3fc" opacity=".9"/>

                    <path d="M0 145 Q80 100 160 145 T320 130 V190 H0Z"
                        fill="#07516b"/>

                    <path d="M0 165 Q100 125 180 165 T320 155 V190 H0Z"
                        fill="#087e91"/>

                    <!-- Turbin angin -->
                    <path d="M70 85 L65 165"
                        stroke="#a5f3fc" stroke-width="4"/>

                    <circle cx="70" cy="85" r="5"
                        fill="#67e8f9"/>

                    <path d="M70 85 L68 20 Q75 12 78 82Z"
                        fill="#67e8f9"/>

                    <path d="M70 85 L125 105 L120 112Z"
                        fill="#67e8f9"/>

                    <path d="M70 85 L25 120 L21 114Z"
                        fill="#67e8f9"/>

                    <!-- Panel Surya -->
                    <path d="M150 115 L270 115 L255 155 L140 155Z"
                        fill="#06445c"
                        stroke="#67e8f9" stroke-width="2"/>

                    <path d="M180 115 L170 155 M220 115 L210 155 M145 135 H262"
                        stroke="#67e8f9" stroke-width="1.5"/>

                    <path d="M195 155 L195 175"
                        stroke="#a5f3fc" stroke-width="3"/>

                    <circle cx="35" cy="35" r="3" fill="#67e8f9"/>
                    <circle cx="115" cy="45" r="4" fill="#a5f3fc"/>
                    <circle cx="290" cy="90" r="3" fill="#67e8f9"/>

                </svg>

                <p>
                    Manfaatkan energi secara bijak untuk menjaga
                    lingkungan dan mengurangi pemborosan listrik.
                </p>

            </div>

            <!-- TIPS -->
            <section class="sea-panel">

                <div class="sea-panel-header">
                    <h3>💡 Tips Hemat Energi</h3>
                </div>

                <ul class="sea-tips">

                    <li>
                        <span class="sea-tip-icon">💡</span>
                        <div>
                            <strong>Matikan lampu</strong><br>
                            Gunakan lampu hanya saat dibutuhkan.
                        </div>
                    </li>

                    <li>
                        <span class="sea-tip-icon">🔌</span>
                        <div>
                            <strong>Cabut charger</strong><br>
                            Lepaskan kabel yang tidak digunakan.
                        </div>
                    </li>

                    <li>
                        <span class="sea-tip-icon">🖥️</span>
                        <div>
                            <strong>Atur komputer</strong><br>
                            Gunakan mode hemat daya.
                        </div>
                    </li>

                    <li>
                        <span class="sea-tip-icon">☀️</span>
                        <div>
                            <strong>Gunakan cahaya alami</strong><br>
                            Manfaatkan sinar matahari di siang hari.
                        </div>
                    </li>

                </ul>

                <div class="sea-note">
                    🌊 <strong>Eco Reminder</strong><br>
                    Hemat energi hari ini untuk lingkungan
                    yang lebih baik di masa depan.
                </div>

            </section>

        </aside>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
