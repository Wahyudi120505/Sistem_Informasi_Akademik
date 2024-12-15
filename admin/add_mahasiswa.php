<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');
include('validasi_mahasiswa.php'); // Memasukkan file validasi

// Variabel untuk pesan error
$error_message = '';

// Menyimpan data yang dikirimkan dari form untuk mempertahankan inputan
$nim = $nama = $tanggal_lahir = $alamat = $jenis_kelamin = $email = $no_telepon = $angkatan = $program_studi = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = $_POST['nim'];
    $nama = $_POST['nama'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat = $_POST['alamat'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $angkatan = $_POST['angkatan'];
    $program_studi = $_POST['program_studi'];  // Mendapatkan nilai program_studi dari form
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validasi NIM
    $validasi_nim = cekNIM($nim);
    if ($validasi_nim) {
        echo $validasi_nim;
    }
    // Validasi Nama
    elseif (cekNama($nama)) {
        echo "Nama hanya boleh mengandung huruf dan tanda petik satu ('), tidak boleh ada angka atau simbol lainnya.";
    }
    // Validasi Email
    elseif (cekEmail($email)) {
        echo "Email tidak valid atau sudah terdaftar! Pastikan email mengandung '@' dan diakhiri '.com'.";
    }
    // Validasi No Telepon
    elseif (cekNoTelepon($no_telepon)) {
        echo "Nomor telepon tidak valid! Pastikan nomor telepon terdiri dari 10-13 digit dan diawali dengan 081, 082, 083, 085, 087, 089 atau +6281, +6282, +6283, +6285, +6287, +6289.";
    } 
    else {
        // Cek apakah NIM sudah terdaftar
        $check_nim_sql = "SELECT * FROM users WHERE username = '$nim'";
        $result = $conn->query($check_nim_sql);

        if ($result->num_rows > 0) {
            // Jika NIM sudah ada, tampilkan pesan error
            echo "NIM sudah terdaftar. Silakan gunakan NIM yang berbeda.";
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
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Tambah Mahasiswa</h2>
    <!-- Menampilkan pesan error jika ada -->
    <?php if ($error_message) { ?>
        <div class="error"><?php echo $error_message; ?></div>
    <?php } ?>

    <form method="POST" action="add_mahasiswa.php">
        <input type="text" name="nim" placeholder="NIM" value="<?php echo $nim; ?>" required>
        <input type="text" name="nama" placeholder="Nama" value="<?php echo $nama; ?>" required>
        <input type="date" name="tanggal_lahir" placeholder="Tanggal Lahir" value="<?php echo $tanggal_lahir; ?>" required>
        <textarea name="alamat" placeholder="Alamat" rows="4"><?php echo $alamat; ?></textarea>
        <select name="jenis_kelamin" required>
            <option value="L" <?php echo ($jenis_kelamin == 'L') ? 'selected' : ''; ?>>Laki-laki</option>
            <option value="P" <?php echo ($jenis_kelamin == 'P') ? 'selected' : ''; ?>>Perempuan</option>
        </select>
        <input type="email" name="email" placeholder="Email" value="<?php echo $email; ?>" required>
        <input type="text" name="no_telepon" placeholder="No Telepon" value="<?php echo $no_telepon; ?>" required>
        <input type="number" name="angkatan" placeholder="Angkatan" value="<?php echo $angkatan; ?>" required>
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
