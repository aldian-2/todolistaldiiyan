<?php
session_start();
include "config.php";

$id = $_GET['id'];
$user_id = $_SESSION['id'];

$koneksi->query("DELETE FROM task WHERE taskid='$id' AND user_id='$user_id'");
header("Location: home.php");
exit;
