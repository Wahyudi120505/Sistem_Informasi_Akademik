<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');
include('validasi_dosen.php'); // Sertakan file validasi_dosen.php

$error_message = "";

// Default values untuk input form
$nip_value = '';
$nama_value = '';
$email_value = '';
$no_telepon_value = '';
$alamat_value = '';
$jabatan_value = '';
$password_value = '';

// Mengecek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $password = $_POST['password'];

    // Simpan data input untuk dipertahankan jika validasi gagal
    $nip_value = $nip;
    $nama_value = $nama;
    $email_value = $email;
    $no_telepon_value = $no_telepon;
    $alamat_value = $alamat;
    $jabatan_value = $jabatan;
    $password_value = $password;

    // Validasi Nama
    if ($validasi_nama = validasiNama($nama)) {
        $error_message = $validasi_nama;
    }

    // Validasi NIP
    if ($validasi_nip = validasiNIP($nip)) {
        $error_message = $validasi_nip;
    }

    // Validasi Email
    if ($validasi_email = validasiEmail($email)) {
        $error_message = $validasi_email;
    }

    // Validasi No Telepon
    if ($validasi_telepon = validasiNoTelepon($no_telepon)) {
        $error_message = $validasi_telepon;
    }

    // Validasi Password
    if ($validasi_password = validasiPassword($password)) {
        $error_message = $validasi_password;
    }

    // Validasi cek NIP, email, dan no telepon di database
    if ($validasi_data = cekDataTersedia($conn, $nip, $email, $no_telepon)) {
        $error_message = $validasi_data;
    }

    if (empty($error_message)) {
        // Proses menambahkan data ke tabel users
        $sql_user = "INSERT INTO Users (username, PASSWORD, role) VALUES ('$nip', '" . password_hash($password, PASSWORD_DEFAULT) . "', 'dosen')";
        
        if ($conn->query($sql_user) === TRUE) {
            // Ambil id_user yang baru saja ditambahkan
            $id_user = $conn->insert_id;

            // Proses menambahkan data ke tabel Dosen
            $sql_dosen = "INSERT INTO Dosen (nip, nama, email, no_telepon, alamat, jabatan, id_user)
                          VALUES ('$nip', '$nama', '$email', '$no_telepon', '$alamat', '$jabatan', '$id_user')";

            if ($conn->query($sql_dosen) === TRUE) {
                // Redirect ke halaman manage_dosen.php setelah data dosen ditambahkan
                header("Location: manage_dosen.php");
                exit;
            } else {
                $error_message = "Error: " . $conn->error;
            }
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dosen</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        
        h1 {
            text-align: center;
            color: #333;
        }

        .form-container {
            width: 100%;
            max-width: 600px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        label {
            font-size: 14px;
            margin-bottom: 6px;
            display: block;
            color: #333;
        }

        input[type="text"], input[type="email"], input[type="password"], textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0 16px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        .form-container input[type="text"]:focus,
        .form-container input[type="email"]:focus,
        .form-container input[type="password"]:focus,
        .form-container textarea:focus {
            border-color: #4CAF50;
        }

        .form-container label {
            font-weight: bold;
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Tambah Dosen</h1>

<div class="form-container">
    <!-- Menampilkan pesan error jika ada -->
    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <form method="POST" action="add_dosen.php">
        <label for="nip">NIP</label>
        <input type="text" name="nip" value="<?php echo htmlspecialchars($nip_value); ?>" required>
        
        <label for="nama">Nama</label>
        <input type="text" name="nama" value="<?php echo htmlspecialchars($nama_value); ?>" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email_value); ?>" required>
        
        <label for="no_telepon">No Telepon</label>
        <input type="text" name="no_telepon" value="<?php echo htmlspecialchars($no_telepon_value); ?>" required>
        
        <label for="alamat">Alamat</label>
        <textarea name="alamat" rows="4"><?php echo htmlspecialchars($alamat_value); ?></textarea>
        
        <label for="jabatan">Jabatan</label>
        <input type="text" name="jabatan" value="<?php echo htmlspecialchars($jabatan_value); ?>" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" value="<?php echo htmlspecialchars($password_value); ?>" required>
        
        <button type="submit">Simpan</button>
    </form>
</div>

</body>
</html>
