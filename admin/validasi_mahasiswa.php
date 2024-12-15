<?php
include('../koneksi.php');

// Fungsi untuk mengecek apakah NIM sudah terdaftar
function cekNIM($nim) {
    global $conn;

    // Memeriksa apakah NIM hanya terdiri dari angka
    if (!preg_match('/^\d+$/', $nim)) {
        return "NIM hanya boleh angka.";
    }

    // Mengecek apakah NIM sudah terdaftar di database
    $sql = "SELECT * FROM Mahasiswa WHERE nim = '$nim'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return "NIM sudah terdaftar. Silakan gunakan NIM yang lain.";
    }

    return false; // NIM valid dan belum terdaftar
}

// Fungsi untuk mengecek apakah email sudah terdaftar dan formatnya valid
function cekEmail($email) {
    global $conn;
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@.*\.com$/', $email)) {
        return true; // Email tidak valid
    }

    $sql = "SELECT * FROM Mahasiswa WHERE email = '$email'";
    $result = $conn->query($sql);
    return $result->num_rows > 0;
}

// Fungsi untuk mengecek apakah nomor telepon sudah terdaftar dan valid
function cekNoTelepon($no_telepon) {
    global $conn;
    
    // Validasi format nomor telepon yang dimulai dengan kode yang benar
    if (!preg_match('/^(08[1-9]\d{6,8}|(\+62)(81|82|83|85|87|89)\d{6,8})$/', $no_telepon)) {
        return true; // Nomor telepon tidak valid
    }

    // Mengecek apakah nomor telepon sudah terdaftar di database
    $sql = "SELECT * FROM Mahasiswa WHERE no_telepon = '$no_telepon'";
    $result = $conn->query($sql);
    return $result->num_rows > 0; // Kembali true jika nomor sudah terdaftar
}

// Fungsi untuk mengecek apakah nama hanya mengandung huruf dan simbol apostrof
function cekNama($nama) {
    // Memastikan nama hanya mengandung huruf dan simbol apostrof
    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚàèìòùÀÈÌÒÙ' ]+$/", $nama)) {
        return true; // Nama tidak valid
    }
    return false;
}
?>
