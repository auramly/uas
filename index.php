<?php
session_start();
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "uas";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$sql = "SELECT * FROM krs";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(to bottom, #0f0c29, #302b63, #24243e);
            min-height: 100vh;
            margin: 0;
            color: #fff; /* Agar teks terlihat jelas */
        }
        .container {
            background: rgba(255, 255, 255, 0.9); /* Semi transparan untuk kontras */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .table {
            background-color: #fff; /* Tetap putih untuk tabel */
            color: #000; /* Warna teks tabel tetap hitam */
        }
        .btn {
            color: #fff; /* Warna teks pada tombol */
        }
        h2{
            text-align: center;
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <h2>Data Kartu Rencana Studi</h2>
        <?php
        if (isset($_SESSION['success_message'])) {
            echo "<div class='alert alert-success' role='alert'>{$_SESSION['success_message']}</div>";
            unset($_SESSION['success_message']);
        }

        if (isset($_SESSION['error_message'])) {
            echo "<div class='alert alert-danger' role='alert'>{$_SESSION['error_message']}</div>";
            unset($_SESSION['error_message']);
        }
        ?>
        <a href="create.php" class="btn btn-primary mb-3">Tambah</a>
        <table class="table table-striped table-bordered">
            <thead>
                <tr style="text-align: center">
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Mata Kuliah</th>
                    <th>Semester</th>
                    <th>Tahun Ajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody style="text-align: center">
                <?php
                    $sql = "SELECT * FROM krs"; 
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['id']}</td>
                                    <td>{$row['nim_mahasiswa']}</td>
                                    <td>{$row['nama_mata_kuliah']}</td>
                                    <td>{$row['semester']}</td>
                                    <td>{$row['tahun_ajaran']}</td>
                                    <td>
                                        <a href='edit.php?id={$row['id']}' class='btn btn-success btn-sm'>Update</a>
                                        <a href='delete.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data pada ID {$row['id']}?\")'>Hapus</a>
                                    </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6' class='text-center'>Tidak ada data.</td></tr>";
                    }
                ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            <a href="logout.php" class="btn btn-dark mb-3">Logout</a>
        </div>
    </div>
</body>

    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>