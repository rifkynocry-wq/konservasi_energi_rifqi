<?php
require 'auth.php';
require_can('checklist.view');

$id = (int)($_GET['id'] ?? 0);

$q = mysqli_prepare($conn, "
    SELECT c.*, s.nama, s.kelas
    FROM checklist c
    LEFT JOIN siswa s ON s.nis = c.nis
    WHERE c.id_checklist = ?
");

mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);
$head = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

if (!$head) {
    die("Checklist tidak ditemukan.");
}

$q2 = mysqli_prepare($conn, "
    SELECT d.*, p.nama_perangkat, p.jenis
    FROM detail_checklist d
    LEFT JOIN perangkat p ON p.id_perangkat = d.id_perangkat
    WHERE d.id_checklist = ?
");

mysqli_stmt_bind_param($q2, "i", $id);
mysqli_stmt_execute($q2);
$d = mysqli_stmt_get_result($q2);

$pageTitle = 'Detail Checklist';
include 'includes/header.php';
?>

<style>
/* DEEP SEA THEME */
body:has(.deep-sea-detail) {
    background:
        radial-gradient(circle at top left, rgba(8, 145, 178, .16), transparent 35%),
        linear-gradient(135deg, #041526, #062b49, #031322);
    color: #e6f6ff;
    min-height: 100vh;
}

.deep-sea-detail {
    color: #e6f6ff;
    padding-bottom: 30px;
}

.deep-sea-detail h2 {
    color: #e0f7ff;
    font-size: 25px;
    font-weight: 700;
    margin: 0;
}

.deep-sea-detail .header-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 22px;
}

.deep-sea-detail .toolbar-btn {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.deep-sea-detail .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 16px;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    transition: .2s ease;
    border: 1px solid transparent;
}

.deep-sea-detail .btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.15);
}

.deep-sea-detail .btn.outline {
    background: rgba(8, 145, 178, .12);
    color: #67e8f9;
    border-color: #0e7490;
}

.deep-sea-detail .btn.secondary {
    background: #164e63;
    color: #e0f7ff;
    border-color: #0e7490;
}

/* INFO CARD */
.deep-sea-detail .card {
    background: rgba(9, 39, 64, .88);
    border: 1px solid rgba(103, 232, 249, .2);
    border-radius: 15px;
    padding: 20px 24px;
    margin-bottom: 22px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
}

.deep-sea-detail .card p {
    color: #cceeff;
    margin: 8px 0;
    line-height: 1.7;
}

.deep-sea-detail .card b {
    color: #67e8f9;
}

/* TABLE */
.deep-sea-detail .table-container {
    width: 100%;
    overflow-x: auto;
    background: rgba(9, 39, 64, .88);
    border: 1px solid rgba(103, 232, 249, .2);
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, .2);
}

.deep-sea-detail table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    color: #e6f6ff;
}

.deep-sea-detail th {
    background: linear-gradient(135deg, #075985, #0e7490);
    color: #e0f7ff;
    padding: 14px 12px;
    text-align: left;
    font-size: 13px;
    white-space: nowrap;
}

.deep-sea-detail td {
    padding: 13px 12px;
    border-bottom: 1px solid rgba(103, 232, 249, .12);
    font-size: 13px;
    color: #d7efff;
}

.deep-sea-detail tbody tr {
    background: rgba(8, 47, 73, .45);
    transition: .2s ease;
}

.deep-sea-detail tbody tr:nth-child(even) {
    background: rgba(12, 57, 82, .55);
}

.deep-sea-detail tbody tr:hover {
    background: rgba(8, 145, 178, .16);
}

.deep-sea-detail tbody tr:last-child td {
    border-bottom: none;
}

/* STATUS BADGE */
.deep-sea-detail .badge {
    display: inline-block;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
}

.deep-sea-detail .badge.yes {
    background: rgba(34, 197, 94, .15);
    color: #86efac;
    border: 1px solid rgba(34, 197, 94, .4);
}

.deep-sea-detail .badge.no {
    background: rgba(239, 68, 68, .15);
    color: #fca5a5;
    border: 1px solid rgba(239, 68, 68, .4);
}

.deep-sea-detail .muted {
    color: #94b8cc;
    text-align: center;
    padding: 20px;
}

/* RESPONSIVE */
@media (max-width: 700px) {
    .deep-sea-detail .header-row {
        align-items: flex-start;
        flex-direction: column;
    }

    .deep-sea-detail .toolbar-btn {
        width: 100%;
    }

    .deep-sea-detail .btn {
        flex: 1;
    }

    .deep-sea-detail .card {
        padding: 16px;
    }
}
/* TABEL DEEP SEA */
.deep-sea-detail .table-container {
    width: 100%;
    overflow-x: auto;
    background: #08263d;
    border: 1px solid #0e7490;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .3);
}

.deep-sea-detail table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    background: #08263d;
    color: #e0f2fe;
}

.deep-sea-detail th {
    background: linear-gradient(135deg, #075985, #0e7490, #164e63);
    color: #ecfeff;
    padding: 15px 12px;
    text-align: left;
    font-size: 13px;
    border-bottom: 2px solid #22d3ee;
}

.deep-sea-detail td {
    background: #0b3049;
    color: #d8f3ff;
    padding: 13px 12px;
    font-size: 13px;
    border-bottom: 1px solid #164e63;
}

.deep-sea-detail tbody tr:nth-child(even) td {
    background: #0d3b57;
}

.deep-sea-detail tbody tr:hover td {
    background: #155e75;
    color: #ffffff;
}

.deep-sea-detail tbody tr:last-child td {
    border-bottom: none;
}

.deep-sea-detail .muted {
    background: #0b3049;
    color: #bae6fd;
    text-align: center;
    padding: 20px;
}
</style>

<div class="deep-sea-detail">

    <div class="header-row">
        <h2>Detail Checklist</h2>

        <div class="toolbar-btn">
            <?php if (can('checklist.export')): ?>
                <a class="btn outline"
                   href="checklist_export.php?id=<?= (int)$id ?>">
                    📊 Export Excel
                </a>

                <a class="btn outline"
                   href="checklist_cetak.php?id=<?= (int)$id ?>&auto=1"
                   target="_blank">
                    📄 Export PDF
                </a>
            <?php endif; ?>

            <a class="btn secondary" href="checklist.php">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- INFORMASI CHECKLIST -->
    <div class="card">
        <p>
            <b>Siswa:</b>
            <?= e($head['nama'] ?? $head['nis']) ?>
            (NIS <?= e($head['nis']) ?>)
        </p>

        <p>
            <b>Kelas:</b> <?= e($head['kelas'] ?? '-') ?>
            |
            <b>Tanggal:</b> <?= e($head['tanggal']) ?>
            |
            <b>Jam:</b> <?= e(substr($head['jam'], 0, 5)) ?>
        </p>
    </div>

    <!-- TABEL DETAIL -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Perangkat</th>
                    <th>Jenis</th>
                    <th>Tahapan</th>
                    <th>Kondisi</th>
                    <th>Status</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; ?>

                <?php while ($r = mysqli_fetch_assoc($d)): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= e($r['nama_perangkat'] ?? '-') ?>
                        </td>

                        <td>
                            <?= e($r['jenis'] ?? '-') ?>
                        </td>

                        <td>
                            <?= e($r['tahapan']) ?>
                        </td>

                        <td>
                            <?= e($r['kondisi']) ?>
                        </td>

                        <td>
                            <span class="badge <?= $r['status'] === 'Ya' ? 'yes' : 'no' ?>">
                                <?= e($r['status']) ?>
                            </span>
                        </td>

                        <td>
                            <?= e($r['keterangan']) ?>
                        </td>
                    </tr>
                <?php endwhile; ?>

                <?php if ($no === 1): ?>
                    <tr>
                        <td colspan="7" class="muted">
                            Checklist ini tidak memiliki detail perangkat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>