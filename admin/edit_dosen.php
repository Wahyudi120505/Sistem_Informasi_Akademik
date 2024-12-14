<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

// Mengecek apakah id dosen ada dalam URL
if (isset($_GET['id'])) {
    $id_dosen = $_GET['id'];

    // Ambil data dosen berdasarkan id
    $sql = "SELECT * FROM Dosen WHERE id_dosen = '$id_dosen'";
    $result = $conn->query($sql);
    $dosen = $result->fetch_assoc();

    if (!$dosen) {
        echo "Dosen tidak ditemukan!";
        exit;
    }
}

// Mengecek apakah data sudah dikirimkan via POST untuk update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $alamat = $_POST['alamat'];
    $jabatan = $_POST['jabatan'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Update data dosen
    $sql_dosen = "UPDATE Dosen SET 
            nip = '$nip', 
            nama = '$nama', 
            email = '$email', 
            no_telepon = '$no_telepon', 
            alamat = '$alamat', 
            jabatan = '$jabatan'
            WHERE id_dosen = '$id_dosen'";

    // Update data user jika ada perubahan di password atau email
    $sql_user = "UPDATE Users SET  
            PASSWORD = '$password' 
            WHERE id_user = (SELECT id_user FROM Dosen WHERE id_dosen = '$id_dosen')";

    if ($conn->query($sql_dosen) === TRUE && $conn->query($sql_user) === TRUE) {
        // Redirect ke halaman manage_dosen.php setelah data dosen dan user diperbarui
        header("Location: manage_dosen.php");
        exit;
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
    <title>Edit Dosen</title>
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

<h1>Edit Dosen</h1>

<div class="form-container">
    <form method="POST" action="edit_dosen.php?id=<?php echo $dosen['id_dosen']; ?>">
        <label for="nip">NIP</label>
        <input type="text" name="nip" value="<?php echo $dosen['nip']; ?>" required>
        
        <label for="nama">Nama</label>
        <input type="text" name="nama" value="<?php echo $dosen['nama']; ?>" required>
        
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php echo $dosen['email']; ?>" required>
        
        <label for="no_telepon">No Telepon</label>
        <input type="text" name="no_telepon" value="<?php echo $dosen['no_telepon']; ?>" required>
        
        <label for="alamat">Alamat</label>
        <textarea name="alamat" rows="4" required><?php echo $dosen['alamat']; ?></textarea>
        
        <label for="jabatan">Jabatan</label>
        <input type="text" name="jabatan" value="<?php echo $dosen['jabatan']; ?>" required>
        
        <label for="password">Password</label>
        <input type="password" name="password" required>
        
        <button type="submit">Update Dosen</button>
    </form>
</div>

</body>
</html>
