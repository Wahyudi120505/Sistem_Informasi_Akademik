<?php
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

// Hubungkan ke database
include('koneksi.php');

// Cek apakah tabel ProgramStudi kosong, jika kosong tambahkan data default
$sql_check = "SELECT COUNT(*) as total FROM ProgramStudi";
$result_check = $conn->query($sql_check);
$row_check = $result_check->fetch_assoc();

if ($row_check['total'] == 0) {
    // Jika kosong, tambahkan data default
    $sql_insert = "INSERT INTO ProgramStudi (nama_prodi, fakultas) VALUES
                   ('Informatika', 'Fakultas Teknik'),
                   ('Sistem Informasi', 'Fakultas Ekonomi'),
                   ('Teknik Mesin', 'Fakultas Teknik')";
    if ($conn->query($sql_insert) === TRUE) {
        // Setelah berhasil menambahkan data, arahkan ke halaman login
        header("Location: login.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    // Jika data sudah ada, arahkan langsung ke halaman login
    header("Location: login.php");
    exit;
}

// Menutup koneksi
$conn->close();
?>
