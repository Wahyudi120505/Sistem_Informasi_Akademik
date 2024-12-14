<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $angkatan = $_POST['angkatan'];
    $program_studi = $_POST['program_studi'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Mengecek apakah nim sudah ada di database
    $check_nim_sql = "SELECT * FROM Mahasiswa WHERE nim = '$nim'";
    $check_nim_result = $conn->query($check_nim_sql);

    if ($check_nim_result->num_rows > 0) {
        // Jika nim sudah ada, tampilkan pesan error
        echo "NIM sudah terdaftar! Silakan gunakan NIM yang lain.";
    } else {
        // Mengecek apakah email sudah ada di database
        $check_email_sql = "SELECT * FROM Mahasiswa WHERE email = '$email'";
        $check_email_result = $conn->query($check_email_sql);

        if ($check_email_result->num_rows > 0) {
            // Jika email sudah ada, tampilkan pesan error
            echo "Email sudah terdaftar! Silakan gunakan email yang lain.";
        } else {
            // Mengecek apakah no_telepon sudah ada di database
            $check_phone_sql = "SELECT * FROM Mahasiswa WHERE no_telepon = '$no_telepon'";
            $check_phone_result = $conn->query($check_phone_sql);

            if ($check_phone_result->num_rows > 0) {
                // Jika no_telepon sudah ada, tampilkan pesan error
                echo "Nomor telepon sudah terdaftar! Silakan gunakan nomor yang lain.";
            } else {
                // Menambahkan data pengguna ke tabel users
                $insert_user_sql = "INSERT INTO users (username, password, role) VALUES ('$nim', '$password', 'mahasiswa')";
                if ($conn->query($insert_user_sql) === TRUE) {
                    // Ambil id_user dari user yang baru saja dimasukkan
                    $id_user = $conn->insert_id;

                    // Menambahkan data mahasiswa
                    $sql = "INSERT INTO Mahasiswa (nim, nama, tanggal_lahir, alamat, jenis_kelamin, email, no_telepon, angkatan, id_prodi, id_user) 
                            VALUES ('$nim', '$nama', '$tanggal_lahir', '$alamat', '$jenis_kelamin', '$email', '$no_telepon', '$angkatan', '$program_studi', '$id_user')";

                    if ($conn->query($sql) === TRUE) {
                        // Redirect ke halaman manage_mahasiswa.php setelah data berhasil ditambahkan
                        header("Location: manage_mahasiswa.php");
                        exit; // Pastikan untuk menghentikan eksekusi script setelah redirect
                    } else {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    echo "Error: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input, select, textarea, button {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        textarea {
            resize: vertical;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Mahasiswa</h2>
    <form method="POST" action="add_mahasiswa.php">
        <input type="text" name="nim" placeholder="NIM" required>
        <input type="text" name="nama" placeholder="Nama" required>
        <input type="date" name="tanggal_lahir" placeholder="Tanggal Lahir" required>
        <textarea name="alamat" placeholder="Alamat" rows="4"></textarea>
        <select name="jenis_kelamin" required>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="no_telepon" placeholder="No Telepon" required>
        <input type="number" name="angkatan" placeholder="Angkatan" required>
        <select name="program_studi" required>
            <?php
            $prodi_sql = "SELECT * FROM ProgramStudi";
            $prodi_result = $conn->query($prodi_sql);
            while ($prodi = $prodi_result->fetch_assoc()) {
                echo "<option value='{$prodi['id_prodi']}'>{$prodi['nama_prodi']}</option>";
            }
            ?>
        </select>

        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Tambah Mahasiswa</button>
    </form>
</div>

</body>
</html>
