<?php

// Fungsi validasi NIP
function validasiNIP($nip) {
    if (!preg_match('/^\d{18}$/', $nip)) {
        return "NIP harus terdiri dari 18 digit angka.";
    }
    return false;
}

// Fungsi validasi Email
function validasiEmail($email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !strpos($email, '@') || !strpos($email, '.com')) {
        return "Email tidak valid. Email harus mengandung '@' dan '.com'.";
    }
    return false;
}

// Fungsi validasi No Telepon
function validasiNoTelepon($no_telepon) {
    if (!preg_match('/^(081|082|083|085|087|089|\+6281|\+6282|\+6283|\+6285|\+6287|\+6289)\d{6,12}$/', $no_telepon)) {
        return "Nomor telepon tidak valid. Harus dimulai dengan 081, 082, 083, 085, 087, 089 atau +6281, +6282, +6283, +6285, +6287, +6289.";
    }
    return false;
}

// Fungsi validasi Nama (hanya huruf dan petik satu)
function validasiNama($nama) {
    if (!preg_match('/^[a-zA-Z\'\s]+$/', $nama)) {
        return "Nama hanya boleh mengandung huruf dan petik satu (').";
    }
    return false;
}

// Fungsi validasi Password
function validasiPassword($password) {
    if (strlen($password) < 8) {
        return "Password harus memiliki minimal 8 karakter.";
    }
    return false;
}

// Fungsi validasi untuk cek NIP, email, dan no telepon apakah sudah ada di database
function cekDataTersedia($conn, $nip, $email, $no_telepon) {
    // Mengecek apakah NIP sudah ada
    $sql_check_nip = "SELECT * FROM Dosen WHERE nip = '$nip'";
    $result_nip = $conn->query($sql_check_nip);
    if ($result_nip->num_rows > 0) {
        return "NIP sudah terdaftar, silakan masukkan NIP yang berbeda.";
    }

    // Mengecek apakah email sudah ada
    $sql_check_email = "SELECT * FROM Dosen WHERE email = '$email'";
    $result_email = $conn->query($sql_check_email);
    if ($result_email->num_rows > 0) {
        return "Email sudah terdaftar, silakan masukkan email yang berbeda.";
    }

    // Mengecek apakah no telepon sudah ada
    $sql_check_telepon = "SELECT * FROM Dosen WHERE no_telepon = '$no_telepon'";
    $result_telepon = $conn->query($sql_check_telepon);
    if ($result_telepon->num_rows > 0) {
        return "No telepon sudah terdaftar, silakan masukkan no telepon yang berbeda.";
    }

    return false;
}

?>
