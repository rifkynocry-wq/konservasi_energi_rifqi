
<?php
require 'auth.php';
require_can('siswa.add');

$error = '';
$nis = $nama = $kelas = '';

if (isset($_POST['simpan'])) {
    csrf_check();

    $nis = trim($_POST['nis']);
    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);

    if (!preg_match('/^\d{1,20}$/', $nis)) {
        $error = 'NIS harus berupa angka (maksimal 20 digit).';
    } elseif ($nama === '' || $kelas === '') {
        $error = 'Nama dan kelas wajib diisi.';
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO siswa(nis,nama,kelas) VALUES(?,?,?)"
        );

        mysqli_stmt_bind_param($stmt, "sss", $nis, $nama, $kelas);

        if (mysqli_stmt_execute($stmt)) {
            flash('Siswa berhasil ditambahkan.');
            header('Location: siswa.php');
            exit;
        }

        $error = 'Gagal menyimpan. Pastikan NIS belum digunakan.';
    }
}

$pageTitle = 'Tambah Siswa';
include 'includes/header.php';
?>

<style>
/* =================================
   DEEP SEA - TAMBAH SISWA
================================= */

body:has(.deep-sea-tambah-siswa) {
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

.deep-sea-tambah-siswa {
    max-width: 750px;
    margin: 0 auto;
    padding: 25px;
    color: #e0f5ff;
}

/* FORM CARD */
.deep-sea-tambah-siswa .form-card {
    background: #0c263a;
    border: 1px solid #164e63;
    border-radius: 20px;
    padding: 30px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .25);
}

/* HEADER FORM */
.deep-sea-tambah-siswa .form-header {
    padding: 22px;
    margin: -30px -30px 25px;
    border-radius: 19px 19px 0 0;

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
}

.deep-sea-tambah-siswa .form-header h2 {
    color: #ffffff;
    margin: 0;
    font-size: 24px;
}

.deep-sea-tambah-siswa .form-header p {
    color: #c7eaff;
    margin: 8px 0 0;
    font-size: 14px;
}

/* LABEL */
.deep-sea-tambah-siswa label {
    display: block;
    margin: 18px 0 8px;
    color: #a5eaff;
    font-weight: 600;
    font-size: 14px;
}

/* INPUT */
.deep-sea-tambah-siswa input {
    width: 100%;
    box-sizing: border-box;
    padding: 13px 15px;

    background: #061e32;
    color: #e0f5ff;

    border: 1px solid #24536b;
    border-radius: 10px;

    outline: none;
    font-size: 14px;
    transition: .2s;
}

.deep-sea-tambah-siswa input::placeholder {
    color: #789db2;
}

.deep-sea-tambah-siswa input:focus {
    border-color: #22c5e5;
    box-shadow: 0 0 0 3px rgba(34, 197, 229, .13);
}

/* ALERT */
.deep-sea-tambah-siswa .alert.danger {
    background: rgba(220, 38, 38, .15);
    border: 1px solid rgba(248, 113, 113, .4);
    color: #ffaaaa;

    padding: 13px 15px;
    border-radius: 10px;
    margin-bottom: 18px;
}

/* ACTIONS */
.deep-sea-tambah-siswa .actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-top: 28px;
}

/* BUTTON */
.deep-sea-tambah-siswa .btn {
    display: inline-block;
    padding: 11px 20px;
    border-radius: 9px;
    border: none;

    text-decoration: none;
    color: #ffffff;

    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: .2s;
}

.deep-sea-tambah-siswa .btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
}

/* SIMPAN */
.deep-sea-tambah-siswa .btn.primary {
    background: linear-gradient(135deg, #0789c5, #00b9d8);
    color: #ffffff;
}

/* BATAL */
.deep-sea-tambah-siswa .btn.secondary {
    background: #164e63;
    border: 1px solid #26718a;
    color: #a5eaff;
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .deep-sea-tambah-siswa {
        padding: 12px;
    }

    .deep-sea-tambah-siswa .form-card {
        padding: 20px;
    }

    .deep-sea-tambah-siswa .form-header {
        margin: -20px -20px 20px;
    }

    .deep-sea-tambah-siswa .actions {
        flex-direction: column;
    }

    .deep-sea-tambah-siswa .actions .btn {
        width: 100%;
        box-sizing: border-box;
        text-align: center;
    }
}
</style>

<div class="deep-sea-tambah-siswa">

    <div class="form-card">

        <div class="form-header">
            <h2>🎓 Tambah Siswa</h2>
            <p>Masukkan informasi siswa baru ke dalam database.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert danger">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="post">

            <?= csrf_field() ?>

            <label>NIS</label>
            <input
                type="text"
                name="nis"
                inputmode="numeric"
                placeholder="Masukkan NIS siswa"
                value="<?= e($nis) ?>"
                required
            >

            <label>Nama Siswa</label>
            <input
                type="text"
                name="nama"
                placeholder="Masukkan nama siswa"
                value="<?= e($nama) ?>"
                required
            >

            <label>Kelas</label>
            <input
                type="text"
                name="kelas"
                placeholder="Contoh: X RPL 2"
                value="<?= e($kelas) ?>"
                required
            >

            <div class="actions">

                <button
                    type="submit"
                    class="btn primary"
                    name="simpan"
                >
                    💾 Simpan
                </button>

                <a class="btn secondary" href="siswa.php">
                    ← Batal
                </a>

            </div>

        </form>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
