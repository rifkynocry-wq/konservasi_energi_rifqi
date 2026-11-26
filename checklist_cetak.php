<?php
// Halaman cetak -> Simpan sebagai PDF lewat browser.
// Pemakaian: checklist_cetak.php (semua) | checklist_cetak.php?id=5 (satu checklist) | tambahkan &auto=1 agar dialog cetak langsung muncul
require 'auth.php';
require_can('checklist.export');
$id = (int)($_GET['id'] ?? 0);
$sql = "SELECT c.id_checklist, c.nis, s.nama, s.kelas, c.tanggal, c.jam,
               p.nama_perangkat, p.jenis, d.tahapan, d.kondisi, d.status, d.keterangan
        FROM checklist c
        LEFT JOIN siswa s ON s.nis = c.nis
        LEFT JOIN detail_checklist d ON d.id_checklist = c.id_checklist
        LEFT JOIN perangkat p ON p.id_perangkat = d.id_perangkat";
if ($id) $sql .= " WHERE c.id_checklist = ?";
$sql .= " ORDER BY c.tanggal DESC, c.jam DESC, c.id_checklist DESC, d.id_detail";
if ($id) {
    $st = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($st, 'i', $id);
    mysqli_stmt_execute($st);
    $res = mysqli_stmt_get_result($st);
} else {
    $res = mysqli_query($conn, $sql);
}
$groups = [];
while ($d = mysqli_fetch_assoc($res)) {
    $k = $d['id_checklist'];
    if (!isset($groups[$k])) $groups[$k] = ['head' => $d, 'items' => []];
    if ($d['nama_perangkat'] !== null || $d['tahapan'] !== null) $groups[$k]['items'][] = $d;
}
$auto = isset($_GET['auto']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Checklist Konservasi Energi</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:"Segoe UI",Arial,sans-serif;font-size:12px;color:#16251d;background:#e9efeb}
.toolbar{position:sticky;top:0;background:#0f3b2a;color:#fff;padding:12px 24px;display:flex;gap:10px;align-items:center;flex-wrap:wrap}
.toolbar button,.toolbar a{border:0;border-radius:8px;padding:9px 16px;font:600 14px "Segoe UI",Arial,sans-serif;cursor:pointer;text-decoration:none}
.toolbar button{background:#fbbf24;color:#3a2700}
.toolbar a{background:rgba(255,255,255,.14);color:#fff}
.toolbar span{font-size:13px;color:#b8d3c5}
.page{max-width:210mm;margin:20px auto;background:#fff;padding:16mm 14mm;box-shadow:0 2px 14px rgba(0,0,0,.15)}
h1{margin:0;font-size:20px;color:#0f3b2a}
.meta{color:#66766d;margin:4px 0 16px;padding-bottom:10px;border-bottom:2px solid #166a47}
.blok{margin:0 0 16px;break-inside:avoid;page-break-inside:avoid}
.blok h2{margin:0 0 5px;font-size:13.5px}
.blok h2 small{font-weight:400;color:#66766d;font-size:12px}
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #c9d6ce;padding:5px 7px;text-align:left;vertical-align:top}
th{background:#e8f3ec;color:#134e34;font-size:11.5px}
.ya{color:#0f5132;font-weight:700}.tidak{color:#8a1c28;font-weight:700}
.kosong{color:#66766d;font-style:italic}
@page{size:A4;margin:10mm}
@media print{
  body{background:#fff}
  .toolbar{display:none}
  .page{margin:0;max-width:none;padding:0;box-shadow:none}
  th{-webkit-print-color-adjust:exact;print-color-adjust:exact}
}
</style>
</head>
<body>
<div class="toolbar">
    <button onclick="window.print()">🖨 Cetak / Simpan sebagai PDF</button>
    <a href="checklist.php">← Kembali</a>
    <span>Di jendela cetak, pilih tujuan (Destination) <b>Save as PDF</b>.</span>
</div>
<div class="page">
    <h1>⚡ Laporan Checklist Konservasi Energi</h1>
    <div class="meta">Dicetak oleh <?= e($_SESSION['nama']) ?> (<?= e($_SESSION['level']) ?>) pada <?= date('d/m/Y H:i') ?> &nbsp;|&nbsp; Jumlah checklist: <?= count($groups) ?></div>

    <?php if (!$groups): ?><p class="kosong">Belum ada data checklist.</p><?php endif; ?>
    <?php foreach ($groups as $g): $h = $g['head']; ?>
    <div class="blok">
        <h2><?= e($h['nama'] ?? '-') ?> <small>— NIS <?= e($h['nis']) ?> · Kelas <?= e($h['kelas'] ?? '-') ?> · <?= e($h['tanggal']) ?> <?= e(substr($h['jam'], 0, 5)) ?></small></h2>
        <table>
            <tr><th style="width:28px">No</th><th>Perangkat</th><th>Jenis</th><th>Tahapan</th><th>Kondisi</th><th>Dilakukan?</th><th>Keterangan</th></tr>
            <?php $n = 1; foreach ($g['items'] as $it): ?>
            <tr>
                <td><?= $n++ ?></td><td><?= e($it['nama_perangkat'] ?? '-') ?></td><td><?= e($it['jenis'] ?? '-') ?></td>
                <td><?= e($it['tahapan']) ?></td><td><?= e($it['kondisi']) ?></td>
                <td class="<?= $it['status'] === 'Ya' ? 'ya' : 'tidak' ?>"><?= e($it['status']) ?></td><td><?= e($it['keterangan']) ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if (!$g['items']): ?><tr><td colspan="7" class="kosong">Tidak ada detail perangkat.</td></tr><?php endif; ?>
        </table>
    </div>
    <?php endforeach; ?>
</div>
<?php if ($auto): ?><script>window.addEventListener('load', function(){ window.print(); });</script><?php endif; ?>
</body>
</html>
