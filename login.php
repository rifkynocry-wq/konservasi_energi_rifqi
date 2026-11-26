<?php
session_start();
require_once 'koneksi.php';

// Jika sudah login, langsung ke dashboard
if (isset($_SESSION['id_user']) && isset($_SESSION['level'])) {
    header("Location: index.php");
    exit;
}

$error = "";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Mencari akun berdasarkan username
    $stmt = mysqli_prepare(
        $conn,
        "SELECT id_user, username, password, nama, level
         FROM users
         WHERE username = ?
         LIMIT 1"
    );

    if (!$stmt) {
        die("Query gagal: " . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row) {
        $stored = $row['password'];

        // Memeriksa password hash
        $ok = password_verify($password, $stored);

        // Mendukung password lama yang belum di-hash
        if (!$ok && hash_equals($stored, $password)) {
            $ok = true;

            // Upgrade password menjadi hash
            $new_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $update = mysqli_prepare(
                $conn,
                "UPDATE users SET password = ? WHERE id_user = ?"
            );

            mysqli_stmt_bind_param(
                $update,
                "si",
                $new_password,
                $row['id_user']
            );

            mysqli_stmt_execute($update);
            mysqli_stmt_close($update);
        }

        if ($ok) {
            // Membersihkan session lama dan membuat session baru
            session_regenerate_id(true);

            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['nama'] = $row['nama'];

            // Menyamakan format role
            $_SESSION['level'] = ucfirst(
                strtolower(trim($row['level']))
            );

            mysqli_stmt_close($stmt);

            // Menuju dashboard
            header("Location: index.php");
            exit;
        } else {
            $error = "Username atau password salah.";
        }
    } else {
        $error = "Username atau password salah.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - Konservasi Energi</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-page">

<div class="login-container">
    <!-- Sisi Kiri: Informasi / Branding -->
    <div class="login-brand-side">
        <div class="brand-badge">
            <span>🌿</span> Konservasi Energi
        </div>
        <h1>Kelola Energi Sekolah Lebih Bijak</h1>
        <p>
            Catat setiap perangkat listrik di sekolah secara berkala, 
            mulai dari sebelum dipakai hingga sesudah dimatikan untuk efisiensi maksimal.
        </p>
        
        <div class="steps-grid">
            <div class="step-item"><span>01</span> SEBELUM</div>
            <div class="step-item"><span>02</span> SELAMA</div>
            <div class="step-item"><span>03</span> SESUDAH</div>
            <div class="step-item"><span>04</span> 5R</div>
        </div>
    </div>

    <!-- Sisi Kanan: Form Login -->
    <div class="login-form-side">
        <div class="form-header">
            <h2>Selamat Datang</h2>
            <p>Silakan masuk menggunakan akun yang telah diberikan.</p>
        </div>

        <?php if ($error): ?>
            <div class="alert-error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label for="username">Username</label>
                <input 
                    type="text" 
                    id="username"
                    name="username" 
                    placeholder="Masukkan username Anda" 
                    required 
                    autocomplete="off"
                    autofocus
                >
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <button type="submit" name="login" class="btn-submit">
                Masuk ke Sistem
            </button>
        </form>

        <div class="demo-credentials">
            <span class="demo-title">Akun Demo Akses Cepat:</span>
            <div class="demo-list">
                <span><strong>Admin:</strong> admin / admin123</span>
                <span><strong>Guru:</strong> guru / guru123</span>
                <span><strong>Siswa:</strong> siswa / siswa123</span>
            </div>
        </div>
    </div>
</div>

</body>
</html>