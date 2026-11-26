<?php
require 'auth.php';
require_can('perangkat.view');

$data = mysqli_query($conn, "SELECT * FROM perangkat ORDER BY id_perangkat");

$pageTitle = 'Data Perangkat';
include 'includes/header.php';
?>

<style>
    body:has(.deep-sea-perangkat) {
        background: #061a2b;
    }

    .deep-sea-perangkat {
        color: #e6f6ff;
        padding: 10px 0 30px;
    }

    .deep-sea-perangkat .header-row {
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

    .deep-sea-perangkat .header-row h2 {
        color: #ffffff;
        font-size: 25px;
        margin: 0;
    }

    .deep-sea-perangkat .table-container {
        overflow-x: auto;
        background: #0c263a;
        border: 1px solid #164e63;
        border-radius: 16px;
        padding: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
    }

    .deep-sea-perangkat table {
        width: 100%;
        border-collapse: collapse;
        color: #e6f6ff;
        min-width: 650px;
    }

    .deep-sea-perangkat th {
        background: #164e63;
        color: #bae6fd;
        padding: 15px;
        text-align: left;
        font-size: 14px;
    }

    .deep-sea-perangkat td {
        padding: 14px 15px;
        border-bottom: 1px solid #1e4055;
        font-size: 14px;
    }

    .deep-sea-perangkat tbody tr:hover {
        background: #12354b;
    }

    .deep-sea-perangkat .aksi {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .deep-sea-perangkat .aksi form {
        margin: 0;
    }

    .deep-sea-perangkat .btn {
        display: inline-block;
        padding: 8px 13px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        color: white;
        font-size: 13px;
        cursor: pointer;
        transition: .2s;
    }

    .deep-sea-perangkat .btn:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
    }

    .deep-sea-perangkat .btn.primary {
        background: #0891b2;
        color: white;
    }

    .deep-sea-perangkat .btn.warning {
        background: #d97706;
        color: white;
    }

    .deep-sea-perangkat .btn.danger {
        background: #dc2626;
        color: white;
    }

    .deep-sea-perangkat .muted {
        color: #94a3b8;
        text-align: center;
        padding: 25px;
    }

    @media (max-width: 600px) {
        .deep-sea-perangkat .header-row {
            padding: 20px;
        }

        .deep-sea-perangkat .header-row h2 {
            font-size: 21px;
        }
    }
    
/* =================================
   TABEL DATA PERANGKAT - DEEP SEA
================================= */

/* Container tabel */
.deep-sea-perangkat .table-container {
    overflow-x: auto;
    background: #0c263a !important;
    border: 1px solid #164e63;
    border-radius: 16px;
    padding: 15px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, .2);
}

/* Tabel utama */
.deep-sea-perangkat .table-container table {
    width: 100%;
    min-width: 650px;
    border-collapse: collapse;
    background: #0c263a !important;
    color: #e6f6ff !important;
}

/* Header tabel */
.deep-sea-perangkat .table-container thead th {
    background: #164e63 !important;
    color: #a5eaff !important;
    padding: 15px;
    text-align: left;
    font-size: 14px;
    border-bottom: 1px solid #26718a;
}

/* Isi tabel */
.deep-sea-perangkat .table-container tbody td {
    background: #0c263a !important;
    color: #d9f2ff !important;
    padding: 14px 15px;
    font-size: 14px;
    border-bottom: 1px solid #1e4055 !important;
}

/* Baris genap */
.deep-sea-perangkat .table-container tbody tr:nth-child(even) td {
    background: #103149 !important;
}

/* Efek hover */
.deep-sea-perangkat .table-container tbody tr:hover td {
    background: #164e63 !important;
}

/* Data kosong */
.deep-sea-perangkat .table-container .muted {
    background: #0c263a !important;
    color: #94a3b8 !important;
    text-align: center;
    padding: 25px;
}

/* Tombol Edit */
.deep-sea-perangkat .table-container .btn.warning {
    background: #d97706 !important;
    color: #ffffff !important;
}

/* Tombol Hapus */
.deep-sea-perangkat .table-container .btn.danger {
    background: #dc2626 !important;
    color: #ffffff !important;
}

</style>

<div class="deep-sea-perangkat">

    <div class="header-row">
        <div>
            <h2>Data Perangkat</h2>
            <p style="color:#bae6fd; margin:7px 0 0;">
                Kelola data perangkat konservasi energi
            </p>
        </div>

        <?php if (can('perangkat.add')): ?>
            <a class="btn primary" href="perangkat_tambah.php">
                + Tambah Perangkat
            </a>
        <?php endif; ?>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Perangkat</th>
                    <th>Jenis</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php $no = 1; while ($r = mysqli_fetch_assoc($data)): ?>
                    <tr>
                        <td><?= $no++ ?></td>

                        <td>
                            <?= e($r['nama_perangkat']) ?>
                        </td>

                        <td>
                            <?= e($r['jenis']) ?>
                        </td>

                        <td>
                            <?= e($r['keterangan']) ?>
                        </td>

                        <td>
                            <div class="aksi">

                                <?php if (can('perangkat.edit')): ?>
                                    <a class="btn warning"
                                       href="perangkat_edit.php?id=<?= (int)$r['id_perangkat'] ?>">
                                        Edit
                                    </a>
                                <?php endif; ?>

                                <?php if (can('perangkat.delete')): ?>
                                    <form method="post"
                                          action="perangkat_hapus.php"
                                          onsubmit="return confirm('Hapus perangkat ini? Detail checklist terkait ikut terhapus.')">

                                        <?= csrf_field() ?>

                                        <input type="hidden"
                                               name="id"
                                               value="<?= (int)$r['id_perangkat'] ?>">

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
                        <td colspan="5" class="muted">
                            Belum ada data perangkat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'includes/footer.php'; ?>