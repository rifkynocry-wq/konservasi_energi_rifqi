
<?php
require 'auth.php';
require_can('checklist.add');

$error = '';
$tahapan_ok = ['SEBELUM', 'SELAMA', 'SESUDAH', '5R'];
$status_ok = ['Ya', 'Tidak'];

$siswa = mysqli_query($conn, "SELECT nis,nama,kelas FROM siswa ORDER BY nama");

$perangkat = [];
$res = mysqli_query($conn, "SELECT id_perangkat,nama_perangkat,jenis FROM perangkat ORDER BY nama_perangkat");

while ($p = mysqli_fetch_assoc($res)) {
    $perangkat[] = $p;
}

// Ambil baris dari POST untuk isi ulang saat error
$rows = [];

foreach (($_POST['id_perangkat'] ?? []) as $i => $pid) {
    $rows[] = [
        'id_perangkat' => $pid,
        'tahapan' => $_POST['tahapan'][$i] ?? 'SEBELUM',
        'kondisi' => $_POST['kondisi'][$i] ?? '',
        'status' => $_POST['status'][$i] ?? 'Ya',
        'keterangan' => $_POST['keterangan'][$i] ?? '',
    ];
}

if (isset($_POST['simpan'])) {
    csrf_check();

    $nis = trim($_POST['nis'] ?? '');
    $tanggal = $_POST['tanggal'] ?? '';
    $jam = $_POST['jam'] ?? '';

    $valid = array_filter($rows, fn($r) => (int)$r['id_perangkat'] > 0);

    $id_nol = array_filter(
        $rows,
        fn($r) => $r['id_perangkat'] !== '' && (int)$r['id_perangkat'] === 0
    );

    if ($nis === '' || $tanggal === '' || $jam === '') {
        $error = 'Siswa, tanggal, dan jam wajib diisi.';
    } elseif ($id_nol) {
        $error = 'ID perangkat di database bernilai 0 (tabel belum memakai AUTO_INCREMENT). Jalankan database/perbaiki_database_lama.sql di phpMyAdmin.';
    } elseif (!$valid) {
        $error = 'Tambahkan minimal satu perangkat.';
    } else {
        mysqli_begin_transaction($conn);

        try {
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO checklist(nis,tanggal,jam) VALUES(?,?,?)"
            );

            mysqli_stmt_bind_param($stmt, "sss", $nis, $tanggal, $jam);

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception(mysqli_error($conn));
            }

            $id = mysqli_insert_id($conn);

            $d = mysqli_prepare(
                $conn,
                "INSERT INTO detail_checklist
                (id_checklist,id_perangkat,tahapan,kondisi,status,keterangan)
                VALUES(?,?,?,?,?,?)"
            );

            foreach ($valid as $r) {
                $pid = (int)$r['id_perangkat'];

                $t = in_array($r['tahapan'], $tahapan_ok, true)
                    ? $r['tahapan'] : 'SEBELUM';

                $k = trim($r['kondisi']);

                $st = in_array($r['status'], $status_ok, true)
                    ? $r['status'] : 'Tidak';

                $ket = trim($r['keterangan']);

                mysqli_stmt_bind_param(
                    $d,
                    "iissss",
                    $id,
                    $pid,
                    $t,
                    $k,
                    $st,
                    $ket
                );

                if (!mysqli_stmt_execute($d)) {
                    throw new Exception(mysqli_error($conn));
                }
            }

            mysqli_commit($conn);

            flash('Checklist berhasil ditambahkan.');
            header('Location: checklist.php');
            exit;

        } catch (Exception $ex) {
            mysqli_rollback($conn);
            $error = 'Gagal menyimpan checklist: ' . $ex->getMessage();
        }
    }
}

if (!$rows) {
    $rows[] = [
        'id_perangkat' => '',
        'tahapan' => 'SEBELUM',
        'kondisi' => '',
        'status' => 'Ya',
        'keterangan' => ''
    ];
}

function row_html($r, $perangkat, $tahapan_ok) {
    ob_start();
?>

<div class="detail-row">

    <div class="field">
        <span class="lbl">Perangkat</span>

        <select name="id_perangkat[]" required>
            <option value="">-- Pilih perangkat --</option>

            <?php foreach ($perangkat as $p): ?>
                <option
                    value="<?= (int)$p['id_perangkat'] ?>"
                    <?= (string)$r['id_perangkat'] === (string)$p['id_perangkat'] ? 'selected' : '' ?>
                >
                    <?= e($p['nama_perangkat'] . ' (' . $p['jenis'] . ')') ?>
                </option>
            <?php endforeach; ?>

        </select>
    </div>

    <div class="field">
        <span class="lbl">Tahapan</span>

        <select name="tahapan[]">
            <?php foreach ($tahapan_ok as $t): ?>
                <option <?= $r['tahapan'] === $t ? 'selected' : '' ?>>
                    <?= $t ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <span class="lbl">Kondisi</span>

        <input
            name="kondisi[]"
            value="<?= e($r['kondisi']) ?>"
            placeholder="Contoh: menyala, mati"
            required
        >
    </div>

    <div class="field">
        <span class="lbl">Dilakukan?</span>

        <select name="status[]">
            <option value="Ya" <?= $r['status'] === 'Ya' ? 'selected' : '' ?>>
                Ya
            </option>

            <option value="Tidak" <?= $r['status'] === 'Tidak' ? 'selected' : '' ?>>
                Tidak
            </option>
        </select>
    </div>

    <div class="field">
        <span class="lbl">Keterangan</span>

        <input
            name="keterangan[]"
            value="<?= e($r['keterangan']) ?>"
            placeholder="Catatan (opsional)"
        >
    </div>

    <div class="field field-delete">
        <span class="lbl">Aksi</span>

        <button type="button" class="btn danger btn-hapus-baris">
            Hapus
        </button>
    </div>

</div>

<?php
    return ob_get_clean();
}

$pageTitle = 'Isi Checklist';
include 'includes/header.php';
?>

<style>

/* =================================
   DEEP SEA - CHECKLIST TAMBAH
================================= */

body:has(.deep-sea-form) {
    background:
        radial-gradient(circle at 15% 10%, rgba(0,183,255,.15), transparent 35%),
        radial-gradient(circle at 85% 80%, rgba(0,92,180,.18), transparent 40%),
        linear-gradient(135deg, #041526, #062b49 55%, #031322);

    color: #e0f5ff;
    min-height: 100vh;
}

.deep-sea-form {
    max-width: 1250px;
    margin: auto;
    padding: 25px;
    color: #e0f5ff;
}

/* HEADER */

.deep-sea-form .page-header {
    padding: 25px;
    margin-bottom: 22px;
    border-radius: 18px;

    background:
        radial-gradient(circle at 90% 10%, rgba(0,225,255,.2), transparent 35%),
        linear-gradient(120deg, #06365b, #075b83, #087e9d);

    border: 1px solid rgba(93,220,255,.25);
    box-shadow: 0 12px 30px rgba(0,0,0,.2);
}

.deep-sea-form .page-header h2 {
    color: #ffffff;
    margin: 0;
    font-size: 25px;
}

.deep-sea-form .page-header p {
    color: #c7eaff;
    margin: 8px 0 0;
}

/* FORM CARD */

.deep-sea-form .form-card {
    background: #0c263a !important;
    border: 1px solid #164e63;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 10px 28px rgba(0,0,0,.25);
}

/* LABEL */

.deep-sea-form label,
.deep-sea-form .lbl {
    display: block;
    color: #a5eaff;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

/* INPUT DAN SELECT */

.deep-sea-form input,
.deep-sea-form select {
    width: 100%;
    box-sizing: border-box;
    padding: 11px 13px;

    background: #061e32 !important;
    color: #e0f5ff !important;

    border: 1px solid #24536b !important;
    border-radius: 9px;

    font-size: 14px;
    outline: none;
    transition: .2s;
}

.deep-sea-form input::placeholder {
    color: #789db2;
}

.deep-sea-form input:focus,
.deep-sea-form select:focus {
    border-color: #22c5e5 !important;
    box-shadow: 0 0 0 3px rgba(34,197,229,.13);
}

/* GRID FORM UTAMA */

.deep-sea-form .grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 18px;
    margin-bottom: 28px;
}

/* JUDUL DETAIL */

.deep-sea-form .section-title {
    color: #8beaff;
    font-size: 19px;
    margin: 25px 0 15px;
    padding-bottom: 12px;
    border-bottom: 1px solid #24536b;
}

/* DETAIL HEADER */

.deep-sea-form .detail-head {
    display: grid;
    grid-template-columns: 1.3fr .8fr 1fr .8fr 1.2fr .5fr;
    gap: 12px;

    padding: 13px 15px;
    margin-bottom: 10px;

    background: #164e63;
    border-radius: 10px;

    color: #a5eaff;
    font-size: 12px;
    font-weight: 700;
}

/* DETAIL ROW */

.deep-sea-form .detail-row {
    display: grid;
    grid-template-columns: 1.3fr .8fr 1fr .8fr 1.2fr .5fr;
    gap: 12px;
    align-items: end;

    padding: 15px;
    margin-bottom: 12px;

    background: #103149;
    border: 1px solid #24536b;
    border-radius: 12px;

    transition: .2s;
}

.deep-sea-form .detail-row:hover {
    border-color: #22a9ca;
    background: #123a52;
}

.deep-sea-form .detail-row .field {
    min-width: 0;
}

/* TOMBOL */

.deep-sea-form .btn {
    display: inline-block;
    padding: 10px 16px;

    border: none;
    border-radius: 9px;

    text-decoration: none;
    color: #ffffff;

    font-size: 13px;
    font-weight: 600;

    cursor: pointer;
    transition: .2s;
}

.deep-sea-form .btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
}

/* SIMPAN */

.deep-sea-form .btn.primary {
    background: linear-gradient(135deg, #0789c5, #00b9d8);
    color: #ffffff;
}

/* OUTLINE */

.deep-sea-form .btn.outline {
    background: transparent;
    border: 1px solid #22a9ca;
    color: #7de6ff;
}

.deep-sea-form .btn.outline:hover {
    background: #164e63;
}

/* HAPUS */

.deep-sea-form .btn.danger {
    background: rgba(220,38,38,.2);
    border: 1px solid rgba(248,113,113,.4);
    color: #ffaaaa;
}

/* ACTIONS */

.deep-sea-form .actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 1px solid #24536b;
}

/* ALERT */

.deep-sea-form .alert.danger {
    background: rgba(220,38,38,.15);
    border: 1px solid rgba(248,113,113,.4);
    color: #ffaaaa;
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* MUTED */

.deep-sea-form .muted {
    color: #91b9ce;
    padding: 12px 0;
}

.deep-sea-form .muted a {
    color: #67e8f9;
}

/* RESPONSIVE */

@media (max-width: 950px) {
    .deep-sea-form .detail-head {
        display: none;
    }

    .deep-sea-form .detail-row {
        grid-template-columns: repeat(2, 1fr);
    }

    .deep-sea-form .field-delete {
        grid-column: 1 / -1;
    }
}

@media (max-width: 650px) {
    .deep-sea-form {
        padding: 12px;
    }

    .deep-sea-form .form-card {
        padding: 17px;
    }

    .deep-sea-form .page-header {
        padding: 20px;
    }

    .deep-sea-form .grid-3,
    .deep-sea-form .detail-row {
        grid-template-columns: 1fr;
    }

    .deep-sea-form .field-delete {
        grid-column: auto;
    }

    .deep-sea-form .actions {
        flex-direction: column;
    }

    .deep-sea-form .actions .btn {
        width: 100%;
        box-sizing: border-box;
        text-align: center;
    }
}

</style>

<div class="deep-sea-form">

    <!-- HEADER -->

    <div class="page-header">
        <h2>⚡ Isi Checklist</h2>
        <p>Catat penggunaan perangkat listrik untuk konservasi energi sekolah.</p>
    </div>

    <!-- FORM CARD -->

    <div class="form-card">

        <?php if ($error): ?>
            <div class="alert danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <?= csrf_field() ?>

            <!-- INFORMASI CHECKLIST -->

            <h3 class="section-title">Informasi Checklist</h3>

            <div class="grid-3">

                <div>
                    <label>Siswa</label>

                    <select name="nis" required>
                        <option value="">-- Pilih Siswa --</option>

                        <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                            <option
                                value="<?= e($s['nis']) ?>"
                                <?= (($_POST['nis'] ?? '') === $s['nis']) ? 'selected' : '' ?>
                            >
                                <?= e($s['nama'] . ' (' . $s['kelas'] . ', NIS ' . $s['nis'] . ')') ?>
                            </option>
                        <?php endwhile; ?>

                    </select>
                </div>

                <div>
                    <label>Tanggal</label>

                    <input
                        type="date"
                        name="tanggal"
                        value="<?= e($_POST['tanggal'] ?? date('Y-m-d')) ?>"
                        required
                    >
                </div>

                <div>
                    <label>Jam</label>

                    <input
                        type="time"
                        name="jam"
                        value="<?= e($_POST['jam'] ?? date('H:i')) ?>"
                        required
                    >
                </div>

            </div>

            <!-- DETAIL PERANGKAT -->

            <h3 class="section-title">💡 Detail Perangkat</h3>

            <div class="detail-head">
                <span>Perangkat</span>
                <span>Tahapan</span>
                <span>Kondisi</span>
                <span>Dilakukan?</span>
                <span>Keterangan</span>
                <span>Aksi</span>
            </div>

            <div id="baris">

                <?php foreach ($rows as $r): ?>
                    <?= row_html($r, $perangkat, $tahapan_ok) ?>
                <?php endforeach; ?>

            </div>

            <template id="tpl">
                <?= row_html(
                    [
                        'id_perangkat' => '',
                        'tahapan' => 'SEBELUM',
                        'kondisi' => '',
                        'status' => 'Ya',
                        'keterangan' => ''
                    ],
                    $perangkat,
                    $tahapan_ok
                ) ?>
            </template>

            <?php if (!$perangkat): ?>
                <p class="muted">
                    Belum ada data perangkat.
                    <a href="perangkat_tambah.php"><u>Tambah perangkat</u></a>
                    terlebih dahulu.
                </p>
            <?php endif; ?>

            <button type="button" id="tambah" class="btn outline">
                + Tambah Baris
            </button>

            <!-- TOMBOL AKSI -->

            <div class="actions">

                <button type="submit" class="btn primary" name="simpan">
                    💾 Simpan Checklist
                </button>

                <a class="btn outline" href="checklist.php">
                    ← Batal
                </a>

            </div>

        </form>

    </div>

</div>

<script>
const baris = document.getElementById('baris');

document.getElementById('tambah').addEventListener('click', () => {
    baris.appendChild(
        document.getElementById('tpl').content.cloneNode(true)
    );
});

baris.addEventListener('click', e => {
    if (!e.target.classList.contains('btn-hapus-baris')) return;

    if (baris.querySelectorAll('.detail-row').length > 1) {
        e.target.closest('.detail-row').remove();
    } else {
        alert('Minimal satu baris perangkat.');
    }
});
</script>

<?php include 'includes/footer.php'; ?>
