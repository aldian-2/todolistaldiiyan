<?php
session_start();
include "config.php";

$id = $_GET['id'];
$user_id = $_SESSION['id'];

// Ambil data hanya jika task milik user yang login
$data = $koneksi->query("SELECT * FROM task WHERE taskid='$id' AND user_id='$user_id'")->fetch_assoc();

// Jika task tidak ditemukan atau bukan milik user
if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='home.php';</script>";
    exit;
}

if (isset($_POST['update'])) {
    $label = $_POST['label'];
    $priority = $_POST['priority'];
    $deadline = $_POST['deadline'];

    $koneksi->query("UPDATE task SET tasklabel='$label', priority='$priority', deadline='$deadline' WHERE taskid='$id' AND user_id='$user_id'");
    header("Location: home.php");
    exit;
}
?>

<link rel="stylesheet" href="style.css">
<div class="main-container">
    <h2>Edit Tugas</h2>
    <form method="post" class="task-form">
    <input type="text" name="label" value="<?= $data['tasklabel'] ?>" required>
    <select name="priority">
        <option value="penting dan mendesak" <?= $data['priority']=='penting dan mendesak'?'selected':'' ?>>Penting dan Mendesak</option>
        <option value="penting tapi tidak mendesak" <?= $data['priority']=='penting tapi tidak mendesak'?'selected':'' ?>>Penting tapi Tidak Mendesak</option>
        <option value="tidak penting tapi mendesak" <?= $data['priority']=='tidak penting tapi mendesak'?'selected':'' ?>>Tidak Penting tapi Mendesak</option>
        <option value="tidak penting dan tidak mendesak" <?= $data['priority']=='tidak penting dan tidak mendesak'?'selected':'' ?>>Tidak Penting dan Tidak Mendesak</option>
    </select>
    <input type="date" name="deadline" value="<?= $data['deadline'] ?>" required>
    
    <div class="button-group">
    <button type="submit" name="update" class="action-btn">💾 Update</button>
    <a href="home.php" class="cancel-btn action-btn danger">❌ Cancel</a>
</div>

</form>


</div>
