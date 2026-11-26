<?php
require 'auth.php';
require_can('checklist.view');

$data = mysqli_query($conn, "SELECT
    c.id_checklist,
    c.nis,
    c.tanggal,
    c.jam,
    s.nama,
    s.kelas,
    (SELECT COUNT(*)
     FROM detail_checklist d
     WHERE d.id_checklist = c.id_checklist) AS jml_detail
    FROM checklist c
    LEFT JOIN siswa s ON s.nis = c.nis
    ORDER BY c.tanggal DESC, c.jam DESC");

$pageTitle = 'Data Checklist';
include 'includes/header.php';
?>

<style>
    body:has(.deep-sea-checklist) {
        background: #061a2b;
    }

    .deep-sea-checklist {
        color: #e6f6ff;
        padding: 10px 0 30px;
    }

    /* Header */
    .deep-sea-checklist .header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        padding: 25px;
        margin-bottom: 25px;
        border-radius: 18px;
        background: linear-gradient(135deg, #075985, #0e7490, #164e63);
        box-shadow: 0 8px 25px rgba(0, 0, 0, .25);
    }

    .deep-sea-checklist .header-row h2 {
        color: #ffffff;
        font-size: 25px;
        margin: 0;
    }

    .deep-sea-checklist .header-row p {
        color: #bae6fd;
        margin: 7px 0 0;
        font-size: 14px;
    }

    /* Tombol toolbar */
    .deep-sea-checklist .toolbar-btn {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    /* Container tabel */
    .deep-sea-checklist .table-container {
        overflow-x: auto;
        background: #0c263a;
        border: 1px solid #164e63;
        border-radius: 16px;
        padding: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
    }

    .deep-sea-checklist table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
        color: #e6f6ff;
    }

    .deep-sea-checklist th {
        background: #164e63;
        color: #bae6fd;
        padding: 15px;
        text-align: left;
        font-size: 14px;
        white-space: nowrap;
    }

    .deep-sea-checklist td {
        padding: 14px 15px;
        border-bottom: 1px solid #1e4055;
        font-size: 14px;
    }

    .deep-sea-checklist tbody tr:hover {
        background: #12354b;
    }

    /* Tombol */
    .deep-sea-checklist .btn {
        display: inline-block;
        padding: 8px 13px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-size: 13px;
        cursor: pointer;
        transition: .2s;
        white-space: nowrap;
    }

    .deep-sea-checklist .btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    .deep-sea-checklist .btn.primary {
        background: #0891b2;
        color: white;
    }

    .deep-sea-checklist .btn.outline {
        background: transparent;
        border: 1px solid #67e8f9;
        color: #a5f3fc;
    }

    .deep-sea-checklist .btn.outline:hover {
        background: #164e63;
    }

    .deep-sea-checklist .btn.danger {
        background: #dc2626;
        color: white;
    }

    /* Kolom aksi */
    .deep-sea-checklist .aksi {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .deep-sea-checklist .aksi form {
        margin: 0;
    }

    /* Jumlah perangkat */
    .deep-sea-checklist .jumlah-perangkat {
        display: inline-block;
        min-width: 28px;
        padding: 5px 9px;
        text-align: center;
        border-radius: 7px;
        background: #164e63;
        color: #a5f3fc;
        font-weight: 600;
    }

    /* Data kosong */
    .deep-sea-checklist .muted {
        color: #94a3b8;
        text-align: center;
        padding: 25px;
    }

    @media (max-width: 700px) {
        .deep-sea-checklist .header-row {
            padding: 20px;
        }

        .deep-sea-checklist .header-row h2 {
            font-size: 21px;
        }

        .deep-sea-checklist .toolbar-btn {
            width: 100%;
        }
    }
    
/* =================================
   TABEL DEEP SEA
================================= */

/* Container tabel */
.deep-sea-checklist .table-container {
    overflow-x: auto;
    background: #0c263a !important;
    border: 1px solid #164e63;
    border-radius: 16px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
}

/* Tabel utama */
.deep-sea-checklist .table-container table {
    width: 100%;
    min-width: 850px;
    border-collapse: collapse;
    background: #0c263a !important;
    color: #e6f6ff !important;
}

/* Header tabel */
.deep-sea-checklist .table-container thead th {
    background: #164e63 !important;
    color: #a5eaff !important;
    padding: 15px;
    text-align: left;
    font-size: 14px;
    white-space: nowrap;
    border-bottom: 1px solid #26718a;
}

/* Seluruh isi tabel */
.deep-sea-checklist .table-container tbody td {
    background: #0c263a !important;
    color: #d9f2ff !important;
    padding: 14px 15px;
    font-size: 14px;
    border-bottom: 1px solid #1e4055 !important;
}

/* Baris genap */
.deep-sea-checklist .table-container tbody tr:nth-child(even) td {
    background: #103149 !important;
}

/* Efek hover */
.deep-sea-checklist .table-container tbody tr:hover td {
    background: #164e63 !important;
}

/* Jumlah perangkat */
.deep-sea-checklist .jumlah-perangkat {
    display: inline-block;
    min-width: 28px;
    padding: 5px 9px;
    text-align: center;
    border-radius: 7px;
    background: #164e63 !important;
    color: #67e8f9 !important;
    font-weight: 600;
}

/* Tombol Detail */
.deep-sea-checklist .table-container .btn.primary {
    background: #0891b2 !important;
    color: #ffffff !important;
}

/* Tombol Hapus */
.deep-sea-checklist .table-container .btn.danger {
    background: #dc2626 !important;
    color: #ffffff !important;
}

/* Data kosong */
.deep-sea-checklist .table-container .muted {
    background: #0c263a !important;
    color: #94a3b8 !important;
    text-align: center;
    padding: 25px;
}

</style>

<div class="deep-sea-checklist">

    <!-- Header -->
    <div class="header-row">

        <div>
            <h2>Data Checklist</h2>
            <p>Kelola dan pantau aktivitas konservasi energi</p>
        </div>

        <div class="toolbar-btn">

            <?php if (can('checklist.export')): ?>

                <a class="btn outline" href="checklist_export.php">
                    📊 Export Excel
                </a>

                <a class="btn outline"
                   href="checklist_cetak.php?auto=1"
                   target="_blank">
                    📄 Export PDF
                </a>

            <?php endif; ?>

            <?php if (can('checklist.add')): ?>

                <a class="btn primary" href="checklist_tambah.php">
                    + Tambah Checklist
                </a>

            <?php endif; ?>

        </div>

    </div>

    <!-- Tabel Checklist -->
    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Perangkat</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                <?php $no = 1; while ($r = mysqli_fetch_assoc($data)): ?>

                    <tr>

                        <td><?= $no++ ?></td>

                        <td>
                            <?= e($r['nis']) ?>
                        </td>

                        <td>
                            <?= e($r['nama'] ?? '-') ?>
                        </td>

                        <td>
                            <?= e($r['kelas'] ?? '-') ?>
                        </td>

                        <td>
                            <?= e($r['tanggal']) ?>
                        </td>

                        <td>
                            <?= e(substr($r['jam'], 0, 5)) ?>
                        </td>

                        <td>
                            <span class="jumlah-perangkat">
                                <?= (int)$r['jml_detail'] ?>
                            </span>
                        </td>

                        <td>
                            <div class="aksi">

                                <a class="btn primary"
                                   href="checklist_detail.php?id=<?= (int)$r['id_checklist'] ?>">
                                    Detail
                                </a>

                                <?php if (can('checklist.delete')): ?>

                                    <form method="post"
                                          action="checklist_hapus.php"
                                          onsubmit="return confirm('Hapus checklist ini?')">

                                        <?= csrf_field() ?>

                                        <input type="hidden"
                                               name="id"
                                               value="<?= (int)$r['id_checklist'] ?>">

                                        <button type="submit" class="btn danger">
                                            Hapus
                                        </button>

                                    </form>

                                <?php endif; ?>

                            </div>
                        </td>

                    </tr>

                <?php endwhile; ?>

                <?php if ($no === 1): ?>

                    <tr>
                        <td colspan="8" class="muted">
                            Belum ada checklist.
                        </td>
                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include 'includes/footer.php'; ?>