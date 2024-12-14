<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

// Mengecek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Mengecek apakah NIP sudah ada
    $sql_check_nip = "SELECT * FROM Dosen WHERE nip = '$nip'";
    $result_nip = $conn->query($sql_check_nip);
    if ($result_nip->num_rows > 0) {
        echo "NIP sudah terdaftar, silakan masukkan NIP yang berbeda.";
        exit;
    }

    // Mengecek apakah email sudah ada
    $sql_check_email = "SELECT * FROM Dosen WHERE email = '$email'";
    $result_email = $conn->query($sql_check_email);
    if ($result_email->num_rows > 0) {
        echo "Email sudah terdaftar, silakan masukkan email yang berbeda.";
        exit;
    }

    // Mengecek apakah no telepon sudah ada
    $sql_check_telepon = "SELECT * FROM Dosen WHERE no_telepon = '$no_telepon'";
    $result_telepon = $conn->query($sql_check_telepon);
    if ($result_telepon->num_rows > 0) {
        echo "No telepon sudah terdaftar, silakan masukkan no telepon yang berbeda.";
        exit;
    }

    // Proses menambahkan data ke tabel users
    $sql_user = "INSERT INTO Users (username, PASSWORD, role) VALUES ('$nip', '$password', 'dosen')";
    
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
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Error: " . $conn->error;
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
    </style>
</head>
<body>

<h1>Tambah Dosen</h1>

<div class="form-container">
    <form method="POST" action="add_dosen.php">
        <label for="nip">NIP</label>
        <input type="text" name="nip" required>
        
        <label for="nama">Nama</label>
        <input type="text" name="nama" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" required>
        
        <label for="no_telepon">No Telepon</label>
        <input type="text" name="no_telepon" required>
        
        <label for="alamat">Alamat</label>
        <textarea name="alamat" rows="4" required></textarea>
        
        <label for="jabatan">Jabatan</label>
        <input type="text" name="jabatan" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Tambah Dosen</button>
    </form>
</div>

</body>
</html>
