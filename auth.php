<?php
// Dipakai di semua halaman: wajib login + pengecekan hak akses per role (lihat fungsi can()).
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['level'])) { header('Location: login.php'); exit; }
require_once __DIR__ . '/koneksi.php';

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function csrf_token() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) die('Token tidak valid. Silakan muat ulang halaman.');
}
function flash($m) { $_SESSION['flash'] = $m; }
function get_flash() { $m = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']); return $m; }


/* ---------- Hak akses per role ----------
   Admin : tambah / ubah / hapus siswa & perangkat, kelola semua checklist
   Guru  : lihat & ubah siswa/perangkat (TIDAK bisa tambah/hapus), lihat & export checklist
   Siswa : hanya checklist (isi baru & lihat), TANPA export Excel/PDF */
function can($perm) {
    static $map = [
        'siswa.view'       => ['Admin'],
        'siswa.add'        => ['Admin'],
        'siswa.edit'       => ['Admin'],
        'siswa.delete'     => ['Admin'],
        'perangkat.view'   => ['Admin'],
        'perangkat.add'    => ['Admin'],
        'perangkat.edit'   => ['Admin'],
        'perangkat.delete' => ['Admin'],
        'checklist.view'   => ['Admin', 'Guru', 'Siswa'],
        'checklist.add'    => ['Admin', 'Siswa', 'Guru'],
        'checklist.export' => ['Admin', 'Guru'],
        'checklist.delete' => ['Admin'],
        ];
    return in_array($_SESSION['level'] ?? '', $map[$perm] ?? [], true);
}
function require_can($perm) {
    if (can($perm)) return;
    http_response_code(403);
    $pageTitle = 'Akses ditolak';
    include __DIR__ . '/includes/header.php';
    echo '<div class="card"><h2>Akses ditolak</h2><p>Role <b>' . e($_SESSION['level']) . '</b> tidak diizinkan membuka halaman ini.</p>'
       . '<a class="btn primary" href="index.php">Kembali ke Dashboard</a></div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa login
if (!isset($_SESSION['status']) || $_SESSION['status'] != 'sukses') {
    header("Location: login.php");
    exit;
}