
<?php

require 'auth.php';
require_can('siswa.view');

$data = mysqli_query(
    $conn,
    "SELECT * FROM siswa ORDER BY nis"
);

$pageTitle = 'Data Siswa';

include 'includes/header.php';

?>

<style>

/* =================================
   DEEP SEA - DATA SISWA
================================= */

body:has(.deep-sea-siswa) {

    background:
        radial-gradient(
            circle at 15% 10%,
            rgba(0, 183, 255, .15),
            transparent 35%
        ),
        radial-gradient(
            circle at 85% 80%,
            rgba(0, 92, 180, .18),
            transparent 40%
        ),
        linear-gradient(
            135deg,
            #041526,
            #062b49 55%,
            #031322
        );

    color: #e0f5ff;
    min-height: 100vh;

}

.deep-sea-siswa {

    padding: 25px;
    color: #e0f5ff;

}

/* HEADER */

.deep-sea-siswa .header-row {

    display: flex;
    align-items: center;
    justify-content: space-between;

    gap: 15px;
    margin-bottom: 25px;

    padding: 25px;

    background:
        radial-gradient(
            circle at 90% 10%,
            rgba(0, 225, 255, .2),
            transparent 35%
        ),
        linear-gradient(
            120deg,
            #06365b,
            #075b83,
            #087e9d
        );

    border: 1px solid rgba(93, 220, 255, .25);

    border-radius: 18px;

    box-shadow: 0 12px 30px rgba(0, 0, 0, .2);

}

.deep-sea-siswa .header-row h2 {

    color: #ffffff;
    margin: 0;

}

/* TABLE CONTAINER */

.deep-sea-siswa .table-container {

    width: 100%;
    overflow-x: auto;

    background: rgba(9, 39, 64, .88);

    border: 1px solid rgba(80, 190, 230, .2);

    border-radius: 18px;

    padding: 15px;

    box-shadow: 0 10px 28px rgba(0, 0, 0, .2);

    backdrop-filter: blur(12px);

}

/* TABLE */

.deep-sea-siswa table {

    width: 100%;

    border-collapse: collapse;

    color: #d9f2ff;

    min-width: 550px;

}

.deep-sea-siswa table th {

    background: #0b4266;

    color: #8ceaff;

    padding: 16px;

    text-align: left;

    font-size: 14px;

    border-bottom: 1px solid rgba(100, 220, 255, .2);

}

.deep-sea-siswa table td {

    padding: 15px 16px;

    border-bottom: 1px solid rgba(130, 210, 240, .12);

    font-size: 14px;

}

.deep-sea-siswa table tr:hover td {

    background: rgba(0, 190, 255, .07);

    transition: .2s;

}

/* NOMOR */

.deep-sea-siswa table td:first-child {

    color: #65dfff;
    font-weight: 600;

}

/* BUTTON */

.deep-sea-siswa .btn.primary {

    background: linear-gradient(
        135deg,
        #0789c5,
        #00b9d8
    );

    color: #ffffff;

    border: none;

}

.deep-sea-siswa .btn.primary:hover {

    background: linear-gradient(
        135deg,
        #00a9e8,
        #00d3e8
    );

}

/* EDIT */

.deep-sea-siswa .btn.warning {

    background: rgba(255, 183, 77, .15);

    color: #ffd28a;

    border: 1px solid rgba(255, 183, 77, .35);

}

/* HAPUS */

.deep-sea-siswa .btn.danger {

    background: rgba(255, 80, 100, .15);

    color: #ff9eaa;

    border: 1px solid rgba(255, 80, 100, .35);

}

/* FORM HAPUS */

.deep-sea-siswa .aksi form {

    display: inline-block;

    margin: 0;

}

.deep-sea-siswa .aksi {

    display: flex;
    align-items: center;
    gap: 7px;

}

/* MUTED */

.deep-sea-siswa .muted {

    color: #91b9ce;

    text-align: center;

}

/* RESPONSIVE */

@media (max-width: 768px) {

    .deep-sea-siswa {

        padding: 12px;

    }

    .deep-sea-siswa .header-row {

        padding: 18px;

        flex-direction: column;

        align-items: flex-start;

    }

    .deep-sea-siswa .header-row .btn {

        width: 100%;

        text-align: center;

    }

    .deep-sea-siswa .table-container {

        padding: 8px;

    }

}

/* =================================
   TABLE DATA SISWA - DEEP SEA
================================= */

/* Container tabel */
.deep-sea-siswa .table-container {
    width: 100%;
    overflow-x: auto;
    background: #0c263a !important;
    border: 1px solid #164e63;
    border-radius: 18px;
    padding: 15px;
    box-shadow: 0 10px 28px rgba(0, 0, 0, .2);
}

/* Tabel utama */
.deep-sea-siswa .table-container table {
    width: 100%;
    min-width: 550px;
    border-collapse: collapse;
    background: #0c263a !important;
    color: #d9f2ff !important;
}

/* Header tabel */
.deep-sea-siswa .table-container thead th {
    background: #164e63 !important;
    color: #8ceaff !important;
    padding: 16px;
    text-align: left;
    font-size: 14px;
    border-bottom: 1px solid #26718a !important;
}

/* Isi tabel */
.deep-sea-siswa .table-container tbody td {
    background: #0c263a !important;
    color: #d9f2ff !important;
    padding: 15px 16px;
    font-size: 14px;
    border-bottom: 1px solid #1e4055 !important;
}

/* Baris genap */
.deep-sea-siswa .table-container tbody tr:nth-child(even) td {
    background: #103149 !important;
}

/* Efek hover */
.deep-sea-siswa .table-container tbody tr:hover td {
    background: #164e63 !important;
    transition: .2s;
}

/* Nomor urut */
.deep-sea-siswa .table-container tbody td:first-child {
    color: #65dfff !important;
    font-weight: 600;
}

/* Data kosong */
.deep-sea-siswa .table-container .muted {
    background: #0c263a !important;
    color: #91b9ce !important;
    text-align: center;
}

/* Tombol Edit */
.deep-sea-siswa .table-container .btn.warning {
    background: rgba(255, 183, 77, .15) !important;
    color: #ffd28a !important;
    border: 1px solid rgba(255, 183, 77, .35);
}

/* Tombol Hapus */
.deep-sea-siswa .table-container .btn.danger {
    background: rgba(255, 80, 100, .15) !important;
    color: #ff9eaa !important;
    border: 1px solid rgba(255, 80, 100, .35);
}

</style>


<!-- PEMBUKA DEEP SEA -->

<div class="deep-sea-siswa">


    <!-- HEADER DATA SISWA -->

    <div class="header-row">

        <div>

            <h2>🎓 Data Siswa</h2>

            <p style="color:#c7eaff; margin:8px 0 0;">
                Kelola dan pantau data siswa sekolah.
            </p>

        </div>


        <?php if (can('siswa.add')): ?>

            <a class="btn primary" href="siswa_tambah.php">
                + Tambah Siswa
            </a>

        <?php endif; ?>

    </div>


    <!-- TABEL DATA SISWA -->

    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Aksi</th>

                </tr>

            </thead>

            <tbody>

                <?php

                $no = 1;

                if ($data):

                    while ($r = mysqli_fetch_assoc($data)):

                ?>

                <tr>

                    <td>
                        <?= $no++ ?>
                    </td>

                    <td>
                        <?= e($r['nis']) ?>
                    </td>

                    <td>
                        <b><?= e($r['nama']) ?></b>
                    </td>

                    <td>
                        <?= e($r['kelas']) ?>
                    </td>

                    <td class="aksi">

                        <?php if (can('siswa.edit')): ?>

                            <a
                                class="btn warning"
                                href="siswa_edit.php?nis=<?= urlencode($r['nis']) ?>"
                            >
                                Edit
                            </a>

                        <?php endif; ?>


                        <?php if (can('siswa.delete')): ?>

                            <form
                                method="post"
                                action="siswa_hapus.php"
                                onsubmit="return confirm('Hapus siswa ini? Checklist miliknya ikut terhapus.')"
                            >

                                <?= csrf_field() ?>

                                <input
                                    type="hidden"
                                    name="nis"
                                    value="<?= e($r['nis']) ?>"
                                >

                                <button
                                    type="submit"
                                    class="btn danger"
                                >
                                    Hapus
                                </button>

                            </form>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php

                    endwhile;

                endif;

                ?>


                <?php if ($no === 1): ?>

                    <tr>

                        <td colspan="5" class="muted">
                            Belum ada data siswa.
                        </td>

                    </tr>

                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>


<?php include 'includes/footer.php'; ?>
