<?php
session_start();
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

include('../koneksi.php');

if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];

    // Mengambil id_user terkait mahasiswa yang akan dihapus
    $get_user_id_sql = "SELECT id_user FROM Mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'";
    $result = $conn->query($get_user_id_sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $id_user = $user['id_user'];

        // Mulai transaksi untuk memastikan kedua penghapusan terjadi secara bersamaan
        $conn->begin_transaction();

        try {
            // Hapus data mahasiswa dari tabel Mahasiswa berdasarkan id_mahasiswa
            $delete_mahasiswa_sql = "DELETE FROM Mahasiswa WHERE id_mahasiswa = '$id_mahasiswa'";
            $conn->query($delete_mahasiswa_sql);

            // Hapus data pengguna dari tabel users berdasarkan id_user
            $delete_user_sql = "DELETE FROM users WHERE id_user = '$id_user'";
            $conn->query($delete_user_sql);

            // Commit transaksi jika kedua query berhasil
            $conn->commit();

            // Redirect ke halaman manage_mahasiswa.php setelah data berhasil dihapus
            header("Location: manage_mahasiswa.php");
            exit;

        } catch (Exception $e) {
            // Jika terjadi error, rollback transaksi
            $conn->rollback();
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Mahasiswa tidak ditemukan.";
    }
} else {
    echo "ID mahasiswa tidak ditemukan.";
}
?>
