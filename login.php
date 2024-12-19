<?php
// File koneksi database
include('koneksi.php');

// Akun admin default
$default_username = "admin";
$default_password = password_hash("admin123", PASSWORD_DEFAULT);

// Cek apakah akun admin sudah ada
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $default_username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Tambahkan akun admin default jika belum ada
    $sql_insert = "INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ss", $default_username, $default_password);
    if (!$stmt_insert->execute()) {
        die("Gagal menambahkan akun admin default: " . $conn->error);
    }
}

// Proses login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Cek username/nim/nip di database
    $sql = "SELECT id_user, username, password, role FROM (
                SELECT id_user, username, password, 'admin' AS role FROM users
                UNION ALL
                SELECT id_mahasiswa AS id_user, nim AS username, password, 'mahasiswa' AS role FROM Mahasiswa
                UNION ALL
                SELECT id_dosen AS id_user, nip AS username, password, 'dosen' AS role FROM Dosen
            ) AS Users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) { 
            // Login berhasil
            session_start();
            $_SESSION["username"] = $row["username"];
            $_SESSION["id_user"] = $row["id_user"];
            $_SESSION["role"] = $row["role"];

            // Redirect berdasarkan role
            if ($row["role"] === "admin") {
                header("Location: admin/dashboard.php");
            } elseif ($row["role"] === "mahasiswa") {
                header("Location: home_mahasiswa.php");
            } elseif ($row["role"] === "dosen") {
                header("Location: home_dosen.php");
            }
            exit;
        } else {
            $error = "Password salah.";
        }
    } else {
        $error = "Username/NIM/NIP tidak ditemukan.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
    <header>
        <h1>Sistem Informasi Akademik</h1>
    </header>
    <div  class="box">
    <h2>Login</h2>
    <form method="POST" action="">
        <label for="username">Username/NIM/NIP:</label><br>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
    </div>

    <?php if (!empty($error)) { echo "<p style='color: red;'>$error</p>"; } ?>

    <footer>
        <p>&copy; 2024 Sistem Informasi Akademik. All Rights Reserved.</p>
    </footer>
</body>
</html>

