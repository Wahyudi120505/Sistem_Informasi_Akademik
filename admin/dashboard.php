<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="container">
    <header>
        <h1>Dashboard Admin</h1>
        <p>Selamat datang, <?php echo $_SESSION['username']; ?>!</p>
    </header>

    <nav>
        <ul>
            <li><a href="manage_mahasiswa.php">Kelola Mahasiswa</a></li>
            <li><a href="manage_dosen.php">Kelola Dosen</a></li>
            <li><a href="manage_program_studi.php">Kelola Program Studi</a></li>
            <li><a href="manage_matakuliah.php">Kelola Mata Kuliah</a></li>
            <li><a href="manage_jadwal.php">Kelola Jadwal Kuliah</a></li>
            <li><a href="manage_ruangan.php">Kelola Ruangan</a></li>
            <li><a href="manage_semester.php">Kelola Semester</a></li>
            <li><a href="manage_krs.php">Kelola KRS</a></li>
            <li><a href="manage_khs.php">Kelola KHS</a></li>
            <li><a href="laporan_statistik.php">Laporan Statistik</a></li>
            <li><a href="notifikasi.php">Notifikasi</a></li>
            <li><a href="../logout.php">Logout</a></li>
        </ul>
    </nav>
</div>

</body>
</html>
