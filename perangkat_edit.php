
<?php
require 'auth.php';
require_can('perangkat.edit');

$id = (int)($_GET['id'] ?? 0);

$q = mysqli_prepare($conn, "SELECT * FROM perangkat WHERE id_perangkat=?");
mysqli_stmt_bind_param($q, "i", $id);
mysqli_stmt_execute($q);

$r = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

if (!$r) {
    die("Perangkat tidak ditemukan.");
}

$error = '';

if (isset($_POST['simpan'])) {
    csrf_check();

    $n = trim($_POST['nama_perangkat']);
    $j = trim($_POST['jenis']);
    $k = trim($_POST['keterangan']);

    if ($n === '' || $j === '' || $k === '') {
        $error = 'Semua kolom wajib diisi.';
    } else {
        $s = mysqli_prepare(
            $conn,
            "UPDATE perangkat
             SET nama_perangkat=?, jenis=?, keterangan=?
             WHERE id_perangkat=?"
        );

        mysqli_stmt_bind_param($s, "sssi", $n, $j, $k, $id);

        if (mysqli_stmt_execute($s)) {
            flash('Perangkat berhasil diubah.');
            header('Location: perangkat.php');
            exit;
        }

        $error = 'Gagal mengubah data.';
    }

    $r['nama_perangkat'] = $n;
    $r['jenis'] = $j;
    $r['keterangan'] = $k;
}

$pageTitle = 'Edit Perangkat';
include 'includes/header.php';
?>

<style>
body:has(.deep-sea-edit) {
    background:
        radial-gradient(circle at top left, rgba(8, 145, 178, .16), transparent 35%),
        linear-gradient(135deg, #041526, #062b49, #031322);
    color: #e6f6ff;
    min-height: 100vh;
}

.deep-sea-edit {
    max-width: 1000px;
    margin: 0 auto;
    padding: 15px 0 40px;
    color: #e6f6ff;
}

/* HEADER */
.deep-sea-edit .page-heading {
    margin-bottom: 22px;
}

.deep-sea-edit .page-heading h2 {
    font-size: 25px;
    margin: 0 0 7px;
    color: #e0f7ff;
}

.deep-sea-edit .page-heading p {
    color: #8fb9ce;
    font-size: 13px;
    margin: 0;
}

/* MAIN LAYOUT */
.deep-sea-edit .edit-layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 20px;
    align-items: start;
}

/* FORM CARD */
.deep-sea-edit .form-card,
.deep-sea-edit .info-card {
    background: rgba(9, 39, 64, .92);
    border: 1px solid rgba(103, 232, 249, .25);
    border-radius: 16px;
    padding: 25px;
    box-shadow: 0 12px 35px rgba(0, 0, 0, .3);
}

/* CARD HEADER */
.deep-sea-edit .card-heading {
    display: flex;
    align-items: center;
    gap: 12px;
    padding-bottom: 18px;
    margin-bottom: 20px;
    border-bottom: 1px solid rgba(103, 232, 249, .2);
}

.deep-sea-edit .heading-icon {
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

.deep-sea-edit .card-heading h3 {
    color: #e0f7ff;
    font-size: 17px;
    margin: 0 0 4px;
}

.deep-sea-edit .card-heading span {
    font-size: 12px;
    color: #8fb9ce;
}

/* FORM */
.deep-sea-edit .form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0 16px;
}

.deep-sea-edit .form-group {
    margin-bottom: 18px;
}

.deep-sea-edit .form-group.full {
    grid-column: 1 / -1;
}

.deep-sea-edit label {
    display: block;
    color: #bae6fd;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.deep-sea-edit input,
.deep-sea-edit textarea {
    width: 100%;
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

.deep-sea-edit input {
    height: 45px;
}

.deep-sea-edit textarea {
    min-height: 145px;
    resize: vertical;
    font-family: inherit;
}

.deep-sea-edit input:focus,
.deep-sea-edit textarea:focus {
    border-color: #22d3ee;
    box-shadow: 0 0 0 3px rgba(34, 211, 238, .12);
}

.deep-sea-edit input::placeholder,
.deep-sea-edit textarea::placeholder {
    color: #7fa9bd;
}

/* BUTTONS */
.deep-sea-edit .actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    padding-top: 18px;
    border-top: 1px solid rgba(103, 232, 249, .2);
}

.deep-sea-edit .btn {
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

.deep-sea-edit .btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.15);
}

.deep-sea-edit .btn.primary {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    color: white;
    border-color: #22d3ee;
}

.deep-sea-edit .btn.secondary {
    background: #164e63;
    color: #e0f7ff;
    border-color: #0e7490;
}

/* INFORMATION PANEL */
.deep-sea-edit .info-card {
    padding: 22px;
}

.deep-sea-edit .info-card h3 {
    font-size: 16px;
    color: #e0f7ff;
    margin: 0 0 18px;
}

.deep-sea-edit .info-item {
    padding: 13px 0;
    border-bottom: 1px solid rgba(103, 232, 249, .15);
}

.deep-sea-edit .info-item:last-child {
    border-bottom: none;
}

.deep-sea-edit .info-item strong {
    display: block;
    color: #67e8f9;
    font-size: 12px;
    margin-bottom: 5px;
}

.deep-sea-edit .info-item p {
    color: #bae6fd;
    font-size: 13px;
    line-height: 1.6;
    margin: 0;
}

.deep-sea-edit .info-note {
    margin-top: 18px;
    padding: 13px;
    border-radius: 9px;
    background: rgba(8, 145, 178, .12);
    border: 1px solid rgba(34, 211, 238, .2);
    color: #a5f3fc;
    font-size: 12px;
    line-height: 1.7;
}

/* ALERT */
.deep-sea-edit .alert.danger {
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
    .deep-sea-edit .edit-layout {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 550px) {
    .deep-sea-edit .form-grid {
        grid-template-columns: 1fr;
    }

    .deep-sea-edit .form-group.full {
        grid-column: auto;
    }

    .deep-sea-edit .form-card,
    .deep-sea-edit .info-card {
        padding: 18px;
    }

    .deep-sea-edit .actions {
        flex-direction: column;
    }

    .deep-sea-edit .actions .btn {
        width: 100%;
        box-sizing: border-box;
    }
}
</style>

<div class="deep-sea-edit">

    <div class="page-heading">
        <h2>🌊 Edit Perangkat</h2>
        <p>Perbarui informasi dan data perangkat yang tersimpan.</p>
    </div>

    <div class="edit-layout">

        <!-- FORM UTAMA -->
        <div class="form-card">

            <div class="card-heading">
                <div class="heading-icon">📝</div>
                <div>
                    <h3>Informasi Perangkat</h3>
                    <span>Silakan ubah data di bawah ini</span>
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

                    <div class="form-group">
                        <label>Nama Perangkat</label>
                        <input
                            type="text"
                            name="nama_perangkat"
                            value="<?= e($r['nama_perangkat']) ?>"
                            placeholder="Masukkan nama perangkat"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label>Jenis Perangkat</label>
                        <input
                            type="text"
                            name="jenis"
                            value="<?= e($r['jenis']) ?>"
                            placeholder="Masukkan jenis perangkat"
                            required
                        >
                    </div>

                    <div class="form-group full">
                        <label>Keterangan</label>
                        <textarea
                            name="keterangan"
                            placeholder="Masukkan keterangan perangkat"
                            required
                        ><?= e($r['keterangan']) ?></textarea>
                    </div>

                </div>

                <div class="actions">
                    <a class="btn secondary" href="perangkat.php">
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

            <h3>🌊 Informasi</h3>

            <div class="info-item">
                <strong>ID PERANGKAT</strong>
                <p>#<?= e($id) ?></p>
            </div>

            <div class="info-item">
                <strong>NAMA PERANGKAT</strong>
                <p><?= e($r['nama_perangkat']) ?></p>
            </div>

            <div class="info-item">
                <strong>JENIS PERANGKAT</strong>
                <p><?= e($r['jenis']) ?></p>
            </div>

            <div class="info-note">
                💡 Pastikan semua informasi perangkat sudah benar sebelum menyimpan perubahan.
            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>
