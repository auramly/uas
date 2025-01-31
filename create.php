<?php

session_start();
if (!isset($_SESSION['login'])) {
    if ($_SESSION['login'] != true) {
        header("Location: login.php");
        exit;
    }
}

$host = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "uas"; 

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$query = "SELECT MAX(id) AS last_id FROM krs";
$result = $conn->query($query);
$last_id = 0;

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $last_id = (int)$row['last_id'];
}

$new_id = $last_id + 1;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nama_mata_kuliah = $_POST['nama_mata_kuliah'];
    $semester = $_POST['semester'];
    $tahun_ajaran = $_POST['tahun_ajaran'];

    $sql = "INSERT INTO krs(id, nim_mahasiswa, nama_mata_kuliah, semester, tahun_ajaran) 
            VALUES ('$new_id', '$nim_mahasiswa', '$nama_mata_kuliah', '$semester', '$tahun_ajaran')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Data Berhasil Ditambahkan!";
    } else {
        $_SESSION['error_message'] = "Lengkapi Data!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mata Kuliah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to top, #0f0c29, #302b63, #24243e);
            height: 110vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            margin-top: 200px;
            background-color: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
        }
        .form-container h2 {
            text-align: center;
            color: #444;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
            color: #555;
        }
        .form-group input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .form-group input:focus {
            border-color: #0f0c29;
            box-shadow: 0px 4px 8px rgba(255, 117, 140, 0.4);
        }
        .form-group button {
            width: 100%;
            padding: 12px;
            background-color: #0f0c29;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .form-group button:hover {
            background-color:rgb(13, 137, 199);
            box-shadow: 0px 4px 10px rgba(255, 117, 140, 0.4);
        }
        .message {
            text-align: center;
            margin-top: 20px;
            color: green;
            font-weight: bold;
        }
        .view-button {
            text-align: center;
            margin-top: 30px;
        }
        .view-button a {
            padding: 12px 142px;
            background-color:rgb(13, 137, 199);
            color: white;
            font-size: 14px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .view-button a:hover {
            box-shadow: 0px 4px 8px rgb(13, 137, 199);
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Form Mata Kuliah</h2>
        <?php
            if (isset($_SESSION['success_message'])) {
                echo "<div class='alert alert-success' role='alert' text-center>{$_SESSION['success_message']}</div>";
                    unset($_SESSION['success_message']);
                }

            if (isset($_SESSION['error_message'])) {
                echo "<div class='alert alert-danger' role='alert' text-center>{$_SESSION['error_message']}</div>";
                    unset($_SESSION['error_message']);
                }
        ?>
        <form action="" method="POST">
            <div class="form-group mb-3">
                <label for="id">ID</label>
                <input type="text" id="id" name="id" value="<?= $new_id; ?>" readonly class="form-control">
            </div>

            <div class="form-group mb-3">
                <label for="nim_mahasiswa">NIM</label>
                <input type="text" id="nim_mahasiswa" name="nim_mahasiswa" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="nama_mata_kuliah">Nama Mata Kuliah</label>
                <input type="text" id="nama_mata_kuliah" name="nama_mata_kuliah" class="form-control" required>
            </div>

            <div class="form-group mb-3">
                <label for="semester">Semester</label>
                <input type="number" id="semester" name="semester" class="form-control" value="<?= htmlspecialchars($mata_kuliah['semester']); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="tahun_ajaran">Tahun Ajaran</label>
                <input type="text" id="tahun_ajaran" name="tahun_ajaran" class="form-control" required>
            </div>

            <div class="form-group mb-4">
                <button type="submit">Tambah</button>
            <div class="view-button">
                <a href="index.php">Tampilkan Data</a>
            </div>
            </div>
        </form>
    </div>
</body>
</html>
<?php
$conn->close();
?>