
<?php
require 'auth.php';
require_can('siswa.edit');

$nis = trim($_GET['nis'] ?? '');

$q = mysqli_prepare($conn, "SELECT * FROM siswa WHERE nis=?");
mysqli_stmt_bind_param($q, "s", $nis);
mysqli_stmt_execute($q);

$r = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

if (!$r) {
    die("Data siswa tidak ditemukan.");
}

$error = '';

if (isset($_POST['simpan'])) {
    csrf_check();

    $nama = trim($_POST['nama']);
    $kelas = trim($_POST['kelas']);

    if ($nama === '' || $kelas === '') {
        $error = 'Nama dan kelas wajib diisi.';
    } else {
        $s = mysqli_prepare(
            $conn,
            "UPDATE siswa SET nama=?, kelas=? WHERE nis=?"
        );

        mysqli_stmt_bind_param($s, "sss", $nama, $kelas, $nis);

        if (mysqli_stmt_execute($s)) {
            flash('Data siswa berhasil diubah.');
            header('Location: siswa.php');
            exit;
        }

        $error = 'Gagal mengubah data.';
    }

    $r['nama'] = $nama;
    $r['kelas'] = $kelas;
}

$pageTitle = 'Edit Siswa';
include 'includes/header.php';
?>

<style>
body:has(.deep-sea-edit-siswa) {
    background:
        radial-gradient(circle at top left, rgba(8, 145, 178, .16), transparent 35%),
        linear-gradient(135deg, #041526, #062b49, #031322);
    color: #e6f6ff;
    min-height: 100vh;
}

/* WRAPPER */
.deep-sea-edit-siswa {
    max-width: 1000px;
    margin: 0 auto;
    padding: 15px 0 40px;
    color: #e6f6ff;
}

/* PAGE HEADER */
.deep-sea-edit-siswa .page-heading {
    margin-bottom: 22px;
}

.deep-sea-edit-siswa .page-heading h2 {
    font-size: 25px;
    margin: 0 0 7px;
    color: #e0f7ff;
}

.deep-sea-edit-siswa .page-heading p {
    color: #8fb9ce;
    font-size: 13px;
    margin: 0;
}

/* MAIN LAYOUT */
.deep-sea-edit-siswa .edit-layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
}

/* CARDS */
.deep-sea-edit-siswa .form-card,
.deep-sea-edit-siswa .info-card {
    background: rgba(9, 39, 64, .92);
    border: 1px solid rgba(103, 232, 249, .25);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .3);
}

/* CARD HEADER */
.deep-sea-edit-siswa .card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(103, 232, 249, .2);
}

.deep-sea-edit-siswa .heading-icon {
    width: 43px;
    height: 43px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(8, 145, 178, .2);
    border: 1px solid rgba(34, 211, 238, .3);
    border-radius: 12px;
    font-size: 21px;
}

.deep-sea-edit-siswa .card-heading h3 {
    color: #e0f7ff;
    font-size: 17px;
    margin: 0 0 4px;
}

.deep-sea-edit-siswa .card-heading span {
    font-size: 12px;
    color: #8fb9ce;
}

/* FORM GRID */
.deep-sea-edit-siswa .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}

.deep-sea-edit-siswa .form-group {
    margin-bottom: 18px;
}

.deep-sea-edit-siswa .form-group.full {
    grid-column: 1 / -1;
}

/* LABEL */
.deep-sea-edit-siswa label {
    display: block;
    color: #bae6fd;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

/* INPUT */
.deep-sea-edit-siswa input {
    width: 100%;
    height: 45px;
    box-sizing: border-box;
    background: #08263d;
    color: #e0f7ff;
    border: 1px solid #164e63;
    border-radius: 9px;
    padding: 12px 14px;
    font-size: 14px;
    outline: none;
    transition: .2s ease;
}

.deep-sea-edit-siswa input:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, .12);
}

/* DISABLED NIS */
.deep-sea-edit-siswa input:disabled {
    background: #12364d;
    color: #8fb6c9;
    border-color: #164e63;
    cursor: not-allowed;
}

/* BUTTON AREA */
.deep-sea-edit-siswa .actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 18px;
    border-top: 1px solid rgba(103, 232, 249, .2);
}

/* BUTTON */
.deep-sea-edit-siswa .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    padding: 11px 19px;
    border-radius: 9px;
    font-size: 13px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: .2s ease;
    border: 1px solid transparent;
}

.deep-sea-edit-siswa .btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.15);
}

/* PRIMARY */
.deep-sea-edit-siswa .btn.primary {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    color: #ffffff;
    border-color: #22d3ee;
}

/* SECONDARY */
.deep-sea-edit-siswa .btn.secondary {
    background: #164e63;
    color: #e0f7ff;
    border-color: #0e7490;
}

/* INFORMATION PANEL */
.deep-sea-edit-siswa .info-card {
    padding: 22px;
}

.deep-sea-edit-siswa .info-card h3 {
    font-size: 16px;
    color: #e0f7ff;
    margin: 0 0 18px;
}

.deep-sea-edit-siswa .info-item {
    padding: 13px 0;
    border-bottom: 1px solid rgba(103, 232, 249, .15);
}

.deep-sea-edit-siswa .info-item:last-child {
    border-bottom: none;
}

.deep-sea-edit-siswa .info-item strong {
    display: block;
    color: #67e8f9;
    font-size: 12px;
    margin-bottom: 5px;
}

.deep-sea-edit-siswa .info-item p {
    color: #bae6fd;
    font-size: 13px;
    line-height: 1.6;
    margin: 0;
    overflow-wrap: anywhere;
}

/* INFO NOTE */
.deep-sea-edit-siswa .info-note {
    margin-top: 18px;
    padding: 13px;
    border-radius: 9px;
    background: rgba(8, 145, 178, .12);
    border: 1px solid rgba(34, 211, 238, .2);
    color: #a5f3fc;
    font-size: 12px;
    line-height: 1.7;
}

/* ERROR */
.deep-sea-edit-siswa .alert.danger {
    background: rgba(220, 38, 38, .15);
    color: #fecaca;
    border: 1px solid rgba(248, 113, 113, .4);
    border-radius: 9px;
    padding: 12px 15px;
    margin-bottom: 18px;
    font-size: 14px;
}

/* RESPONSIVE */
@media (max-width: 800px) {
    .deep-sea-edit-siswa .edit-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 550px) {
    .deep-sea-edit-siswa .form-grid {
        grid-template-columns: 1fr;
    }

    .deep-sea-edit-siswa .form-group.full {
        grid-column: auto;
    }

    .deep-sea-edit-siswa .form-card,
    .deep-sea-edit-siswa .info-card {
        padding: 18px;
    }

    .deep-sea-edit-siswa .actions {
        flex-direction: column;
    }

    .deep-sea-edit-siswa .actions .btn {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

<div class="deep-sea-edit-siswa">

    <!-- PAGE HEADER -->
    <div class="page-heading">
        <h2>🌊 Edit Siswa</h2>
        <p>Perbarui informasi data siswa yang tersimpan.</p>
    </div>

    <div class="edit-layout">

        <!-- FORM UTAMA -->
        <div class="form-card">

            <div class="card-heading">
                <div class="heading-icon">🎓</div>
                <div>
                    <h3>Informasi Siswa</h3>
                    <span>Silakan ubah data siswa di bawah ini</span>
                </div>
            </div>

            <?php if ($error): ?>
                <div class="alert danger">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <form method="post">
                <?= csrf_field() ?>

                <div class="form-grid">

                    <div class="form-group full">
                        <label>NIS</label>
                        <input
                            type="text"
                            value="<?= e($r['nis']) ?>"
                            disabled
                        >
                    </div>

                    <div class="form-group">
                        <label>Nama Siswa</label>
                        <input
                            type="text"
                            name="nama"
                            value="<?= e($r['nama']) ?>"
                            placeholder="Masukkan nama siswa"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Kelas</label>
                        <input
                            type="text"
                            name="kelas"
                            value="<?= e($r['kelas']) ?>"
                            placeholder="Masukkan kelas siswa"
                            required
                        >
                    </div>

                </div>

                <div class="actions">

                    <a class="btn secondary" href="siswa.php">
                        ← Batal
                    </a>

                    <button type="submit" class="btn primary" name="simpan">
                        💾 Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

        <!-- PANEL INFORMASI -->
        <div class="info-card">

            <h3>🎓 Informasi Siswa</h3>

            <div class="info-item">
                <strong>NOMOR INDUK SISWA</strong>
                <p><?= e($r['nis']) ?></p>
            </div>

            <div class="info-item">
                <strong>NAMA SISWA</strong>
                <p><?= e($r['nama']) ?></p>
            </div>

            <div class="info-item">
                <strong>KELAS</strong>
                <p><?= e($r['kelas']) ?></p>
            </div>

            <div class="info-note">
                💡 Pastikan NIS, nama, dan kelas sudah sesuai sebelum menyimpan perubahan data siswa.
            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
