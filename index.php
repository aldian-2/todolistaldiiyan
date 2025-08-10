<?php
session_start();
include "config.php";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $koneksi->query("SELECT * FROM users WHERE username='$username'");
    $users = $result->fetch_assoc();

    if ($users && password_verify($password, $users['password'])) {
        $_SESSION['id'] = $users['id'];
        header("Location: home.php");
    } else {
        $error = "Login gagal!";
    }
}
?>

<link rel="stylesheet" href="style.css">
<div class="login-container">
    <h2>Login To-Do List</h2>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" name="login">Login</button>
        <p>Belum punya akun? <a href="register.php">Daftar</a></p>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </form>
</div>
