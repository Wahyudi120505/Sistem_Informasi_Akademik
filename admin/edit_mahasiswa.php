<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

// Mengambil id mahasiswa dari URL
if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];

    // Mengambil data mahasiswa berdasarkan id
    $sql = "SELECT * FROM Mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $mahasiswa = $result->fetch_assoc();
    } else {
        echo "Mahasiswa tidak ditemukan!";
        exit;
    }

    // Proses update data mahasiswa
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

        // Update data mahasiswa di tabel Mahasiswa
        $update_mahasiswa_sql = "UPDATE Mahasiswa SET
            nim = '$nim',
            nama = '$nama',
            tanggal_lahir = '$tanggal_lahir',
            alamat = '$alamat',
            jenis_kelamin = '$jenis_kelamin',
            email = '$email',
            no_telepon = '$no_telepon',
            angkatan = '$angkatan',
            id_prodi = '$program_studi'
            WHERE id_mahasiswa = '$id_mahasiswa'";

        if ($conn->query($update_mahasiswa_sql) === TRUE) {
            // Jika data mahasiswa berhasil diperbarui, update data di tabel Users
            $update_user_sql = "UPDATE Users SET username = '$nim' WHERE username = '{$mahasiswa['nim']}'";
            if ($conn->query($update_user_sql) === TRUE) {
                header("Location: manage_mahasiswa.php");
                exit;
            } else {
                echo "Error updating user data: " . $conn->error;
            }
        } else {
            echo "Error updating mahasiswa data: " . $conn->error;
        }
    }
} else {
    echo "ID Mahasiswa tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mahasiswa</title>
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
    <h2>Edit Mahasiswa</h2>
    <form method="POST" action="edit_mahasiswa.php?id=<?php echo $mahasiswa['id_mahasiswa']; ?>">
        <input type="text" name="nim" value="<?php echo $mahasiswa['nim']; ?>" required>
        <input type="text" name="nama" value="<?php echo $mahasiswa['nama']; ?>" required>
        <input type="date" name="tanggal_lahir" value="<?php echo $mahasiswa['tanggal_lahir']; ?>" required>
        <textarea name="alamat" rows="4"><?php echo $mahasiswa['alamat']; ?></textarea>
        <select name="jenis_kelamin" required>
            <option value="L" <?php echo $mahasiswa['jenis_kelamin'] == 'L' ? 'selected' : ''; ?>>Laki-laki</option>
            <option value="P" <?php echo $mahasiswa['jenis_kelamin'] == 'P' ? 'selected' : ''; ?>>Perempuan</option>
        </select>
        <input type="email" name="email" value="<?php echo $mahasiswa['email']; ?>" required>
        <input type="text" name="no_telepon" value="<?php echo $mahasiswa['no_telepon']; ?>" required>
        <input type="number" name="angkatan" value="<?php echo $mahasiswa['angkatan']; ?>" required>
        <select name="program_studi" required>
            <?php
            $prodi_sql = "SELECT * FROM ProgramStudi";
            $prodi_result = $conn->query($prodi_sql);
            while ($prodi = $prodi_result->fetch_assoc()) {
                $selected = ($mahasiswa['id_prodi'] == $prodi['id_prodi']) ? 'selected' : '';
                echo "<option value='{$prodi['id_prodi']}' $selected>{$prodi['nama_prodi']}</option>";
            }
            ?>
        </select>
        <button type="submit">Update Mahasiswa</button>
    </form>
</div>

</body>
</html>
