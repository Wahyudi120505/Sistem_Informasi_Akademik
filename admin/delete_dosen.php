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

    // Mengambil id_user dari tabel Dosen
    $sql_dosen = "SELECT id_user FROM Dosen WHERE id_dosen = '$id_dosen'";
    $result_dosen = $conn->query($sql_dosen);
    $dosen = $result_dosen->fetch_assoc();

    if ($dosen) {
        $id_user = $dosen['id_user'];

        // Menghapus data dosen dari tabel Dosen
        $sql_delete_dosen = "DELETE FROM Dosen WHERE id_dosen = '$id_dosen'";

        // Menghapus data user dari tabel Users
        $sql_delete_user = "DELETE FROM users WHERE id_user = '$id_user'";

        if ($conn->query($sql_delete_dosen) === TRUE && $conn->query($sql_delete_user) === TRUE) {
            // Redirect ke halaman manage_dosen.php setelah data dosen dan user terhapus
            header("Location: manage_dosen.php");
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Dosen tidak ditemukan!";
        exit;
    }
} else {
    echo "ID dosen tidak ditemukan!";
}
?>
