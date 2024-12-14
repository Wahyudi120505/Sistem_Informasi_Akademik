<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

// Menampilkan daftar dosen
$sql = "SELECT * FROM Dosen";
$result = $conn->query($sql);
?>

<h1>Daftar Dosen</h1>
<a href="../admin/add_dosen.php">Tambah Dosen</a>
<table border="1">
    <tr>
        <th>NIP</th>
        <th>Nama</th>
        <th>Email</th>
        <th>Jabatan</th>
        <th>Action</th>
    </tr>

    <?php while ($dosen = $result->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $dosen['nip']; ?></td>
        <td><?php echo $dosen['nama']; ?></td>
        <td><?php echo $dosen['email']; ?></td>
        <td><?php echo $dosen['jabatan']; ?></td>
        <td>
            <a href="edit_dosen.php?id=<?php echo $dosen['id_dosen']; ?>">Edit</a>
            <a href="delete_dosen.php?id=<?php echo $dosen['id_dosen']; ?>">Hapus</a>
        </td>
    </tr>
    <?php } ?>
</table>

<!-- Tombol Kembali -->
<a href="dashboard.php">
    <button>Kembali ke Dashboard</button>
</a>
