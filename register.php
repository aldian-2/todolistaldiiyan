<?php
include "config.php";
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $koneksi->query("INSERT INTO users (username, password) VALUES ('$username', '$password')");
    header("Location: index.php");
}
?>

<link rel="stylesheet" href="style.css">
<div class="login-container">
    <h2>Daftar Akun</h2>
    <form method="post">
        <input type="text" name="username" placeholder="username" required><br>
        <input type="password" name="password" placeholder="password" required><br>
        <button type="submit" name="register">Daftar</button>
    </form>
</div>
