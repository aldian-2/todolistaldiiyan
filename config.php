<?php
$koneksi = new mysqli("localhost", "root", "", "todo_list");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}
?>
