<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

// Koneksi ke database
$mysqli = new mysqli('localhost', 'root', '', 'uas');

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

// Ambil data berdasarkan ID
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah integer

    $stmt = $mysqli->prepare("SELECT * FROM krs WHERE id = ?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $mata_kuliah = $result->fetch_assoc();

    // Jika data tidak ditemukan
    if (!$mata_kuliah) {
        $_SESSION['error_message'] = 'Data tidak ditemukan!';
        header('Location: index.php');
        exit();
    }
    $stmt->close();
}

// Proses update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
    $semester = intval($_POST['semester']);
    $tahun_ajaran = $_POST['tahun_ajaran']; // Tetap string karena varchar

    // Validasi input
    if (empty($nim_mahasiswa) || empty($nama_mata_kuliah) || empty($semester) || empty($tahun_ajaran)) {
        $_SESSION['error_message'] = 'Semua field harus diisi!';
    } else {
        // Query untuk update data
        $stmt = $mysqli->prepare("UPDATE krs SET nim_mahasiswa = ?, nama_mata_kuliah = ?, semester = ?, tahun_ajaran = ? WHERE id = ?");
        $stmt->bind_param('ssisi', $nim_mahasiswa, $nama_mata_kuliah, $semester, $tahun_ajaran, $id);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = 'Data berhasil diperbarui!';
            header('Location: index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'Data tidak bisa diperbarui: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit KRS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-container h2 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            color: #555;
            margin-bottom: 8px;
        }
        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }
        .form-group input:focus {
            border-color: #007BFF;
            background-color: #fff;
            outline: none;
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #007BFF;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #0f0c29;
            box-shadow: 0px 4px 8px rgb(13, 137, 199);
        }
        .message {
            text-align: center;
            margin-top: 10px;
        }
        .message.success {
            color: green;
        }
        .message.error {
            color: red;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>EDIT DATA KRS</h2>
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="message error">
            <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
        </div>
    <?php endif; ?>
    <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($mata_kuliah['id']); ?>">
        
        <div class="form-group">
            <label for="nim_mahasiswa">NIM</label>
            <input type="text" id="nim_mahasiswa" name="nim_mahasiswa" value="<?= htmlspecialchars($mata_kuliah['nim_mahasiswa']); ?>" required>
        </div>

        <div class="form-group">
            <label for="nama_mata_kuliah">Nama Mata Kuliah</label>
            <input type="text" id="nama_mata_kuliah" name="nama_mata_kuliah" value="<?= htmlspecialchars($mata_kuliah['nama_mata_kuliah']); ?>" required>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <input type="number" id="semester" name="semester" value="<?= htmlspecialchars($mata_kuliah['semester']); ?>" required>
        </div>

        <div class="form-group">
            <label for="tahun_ajaran">Tahun Ajaran</label>
            <input type="text" id="tahun_ajaran" name="tahun_ajaran" value="<?= htmlspecialchars($mata_kuliah['tahun_ajaran']); ?>" required>
        </div>

        <div class="form-group">
            <button type="submit">EDIT</button>
        </div>
    </form>
</div>

</body>
</html>