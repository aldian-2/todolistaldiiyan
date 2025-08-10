<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

include "config.php";

$user_id = $_SESSION['id']; // Ambil user yang login

// Tambah Tugas
if (isset($_POST['tambah'])) {
    $tasklabel  = $_POST['label'];
    $priority   = $_POST['priority'];
    $deskripsi  = $_POST['deskripsi'];
    $deadline   = $_POST['deadline'];
    $createdat  = date('Y-m-d');
    $taskstatus = 'open';

    // Validasi deadline
    if (strtotime($deadline) < strtotime($createdat)) {
        echo "<script>alert('Tanggal deadline tidak boleh di masa lalu!'); window.location='".$_SERVER['PHP_SELF']."';</script>";
        exit;
    }

    $koneksi->query("INSERT INTO task (tasklabel, taskstatus, createdat, priority, deadline, user_id, deskripsi) 
        VALUES ('$tasklabel', '$taskstatus', '$createdat', '$priority', '$deadline', '$user_id', '$deskripsi')");
}


// Ambil semua tugas user
$task = $koneksi->query("SELECT * FROM task WHERE user_id='$user_id'");
?>

<link rel="stylesheet" href="style.css">
<div class="main-container">
    <h2>To-Do List</h2>

    <form method="post" class="task-form">
        <input type="text" name="label" placeholder="Tugas baru..." required>
        <textarea name="deskripsi" placeholder="Deskripsi tugas..." rows="3" required></textarea>
        <select name="priority" required>
            <option value="penting dan mendesak">Penting dan Mendesak</option>
            <option value="penting tapi tidak mendesak">Penting tapi Tidak Mendesak</option>
            <option value="tidak penting tapi mendesak">Tidak Penting tapi Mendesak</option>
            <option value="tidak penting dan tidak mendesak">Tidak Penting dan Tidak Mendesak</option>
        </select>
        <input type="date" name="deadline" required>
        <button name="tambah">Tambah</button>
    </form>

    <div class="task-list">
        <?php while ($row = $task->fetch_assoc()): ?>
            <div class="task-item <?= $row['taskstatus'] == 'tutup' ? 'done' : '' ?>">
                <p><strong><?= htmlspecialchars($row['tasklabel']) ?></strong> (<?= htmlspecialchars($row['priority']) ?>)</p>
                <p><?= nl2br(htmlspecialchars($row['deskripsi'])) ?></p>
                <p>Deadline: <?= htmlspecialchars($row['deadline']) ?></p>
                <p>Status: <?= htmlspecialchars($row['taskstatus']) ?></p>

                <div style="margin-top: 10px;">
                    <?php if ($row['taskstatus'] == 'open'): ?>
                        <a href="tandai_selesai.php?id=<?= $row['taskid'] ?>" class="action-btn" onclick="return confirmComplete(event)">✅ Selesai</a>
                    <?php endif; ?>
                    <a href="edit.php?id=<?= $row['taskid'] ?>" class="action-btn">✏ Edit</a>
                    <a href="hapus.php?id=<?= $row['taskid'] ?>" class="action-btn danger" onclick="return confirmDelete(event)">🗑 Hapus</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <a href="logout.php" class="logout-btn">Logout</a>
</div>

<!-- Konfirmasi Hapus -->
<script>
function confirmDelete(event) {
    event.preventDefault();
    const url = event.currentTarget.href;

    const confirmBox = document.createElement('div');
    confirmBox.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; z-index: 9999;">
            <div style="background: white; padding: 30px; border-radius: 12px; text-align: center; max-width: 300px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <h3 style="margin-bottom: 20px; color: #333;">Yakin ingin menghapus tugas ini?</h3>
                <button id="yesDelete" style="background: #ff4d4d; color: white; border: none;
                    padding: 10px 20px; border-radius: 8px; font-weight: bold; margin-right: 10px; cursor: pointer;">Ya</button>
                <button id="cancelDelete" style="background: #ccc; color: #333; border: none;
                    padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Batal</button>
            </div>
        </div>
    `;
    document.body.appendChild(confirmBox);
    document.getElementById('yesDelete').onclick = () => window.location.href = url;
    document.getElementById('cancelDelete').onclick = () => confirmBox.remove();
}
</script>

<!-- Konfirmasi Tandai Selesai -->
<script>
function confirmComplete(event) {
    event.preventDefault();
    const url = event.currentTarget.href;

    const confirmBox = document.createElement('div');
    confirmBox.innerHTML = `
        <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.4); display: flex; justify-content: center; align-items: center; z-index: 9999;">
            <div style="background: white; padding: 30px; border-radius: 12px; text-align: center; max-width: 300px;
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                <h3 style="margin-bottom: 20px; color: #333;">Tandai tugas ini sebagai selesai?</h3>
                <button id="yesComplete" style="background: #28a745; color: white; border: none;
                    padding: 10px 20px; border-radius: 8px; font-weight: bold; margin-right: 10px; cursor: pointer;">Ya</button>
                <button id="cancelComplete" style="background: #ccc; color: #333; border: none;
                    padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer;">Batal</button>
            </div>
        </div>
    `;
    document.body.appendChild(confirmBox);
    document.getElementById('yesComplete').onclick = () => window.location.href = url;
    document.getElementById('cancelComplete').onclick = () => confirmBox.remove();
}
</script>
