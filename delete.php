<?php
session_start();
if (!isset($_SESSION['login'])) {
    if ($_SESSION['login'] != true) {
        header("Location: login.php");
        exit;
    }
}

$mysqli = new mysqli('localhost', 'root', '', 'uas');

if ($mysqli->connect_error) {
    die("Koneksi gagal: " . $mysqli->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $mysqli->prepare("DELETE FROM krs WHERE id = ?");
    $stmt->bind_param('i', $id); 

    if ($stmt->execute()) {
        $_SESSION['success_message'] = 'Data berhasil dihapus!';
        header('Location: index.php');
        exit();
    } else {
        $_SESSION['error_message'] = 'Data tidak bisa dihapus: ' . $stmt->error;
        header('Location: index.php');
        exit();
    }

    $stmt->close();
} else {
    $_SESSION['error_message'] = 'id/kode tidak ditemukan!';
    header('Location: index.php');
    exit();
}

$mysqli->close();
$conn->close();
?>
