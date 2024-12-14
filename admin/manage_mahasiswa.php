<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

// Menampilkan daftar mahasiswa dengan gabungan tabel Mahasiswa, ProgramStudi, dan users
$sql = "
    SELECT m.id_mahasiswa, m.nim, m.nama, m.email, m.tanggal_lahir, m.alamat, m.jenis_kelamin, m.no_telepon, m.angkatan, p.nama_prodi, u.username 
    FROM Mahasiswa m
    JOIN ProgramStudi p ON m.id_prodi = p.id_prodi
    LEFT JOIN users u ON m.id_user = u.id_user
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        a {
            text-decoration: none;
            color: blue;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<h1>Daftar Mahasiswa</h1>
<a href="../admin/add_mahasiswa.php">Tambah Mahasiswa</a>
<table>
    <tr>
        <th>NIM</th>
        <th>Nama</th>
        <th>Program Studi</th>
        <th>Email</th>
        <th>Jenis Kelamin</th>
        <th>No Telepon</th>
        <th>Angkatan</th>
        <th>Action</th>
    </tr>

    <?php while ($mahasiswa = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $mahasiswa['nim']; ?></td>
        <td><?php echo $mahasiswa['nama']; ?></td>
        <td><?php echo $mahasiswa['nama_prodi']; ?></td>
        <td><?php echo $mahasiswa['email']; ?></td>
        <td><?php echo ($mahasiswa['jenis_kelamin'] == 'L') ? 'Laki-Laki' : 'Perempuan'; ?></td>
        <td><?php echo $mahasiswa['no_telepon']; ?></td>
        <td><?php echo $mahasiswa['angkatan']; ?></td>
        <td>
            <a href="edit_mahasiswa.php?id=<?php echo $mahasiswa['id_mahasiswa']; ?>">Edit</a> |
            <a href="delete_mahasiswa.php?id=<?php echo $mahasiswa['id_mahasiswa']; ?>">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- Tombol Kembali -->
<a href="dashboard.php">
    <button>Kembali ke Dashboard</button>
</a>

</body>
</html>
