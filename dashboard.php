<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: auth.php");
    exit();
}

require 'koneksi.php';

if ($_SESSION['nama'] === 'admin' && isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
<?php if ($_SESSION['nama'] === 'admin'): ?>
    <h2>Selamat Datang, admin!</h2>
    <a href="logout.php"><button>Logout</button></a>
    <h3>Menu Admin: Kelola Pengguna</h3>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Aksi</th>
        </tr>
        <?php
        $result = $conn->query("SELECT id, nama FROM users ORDER BY id DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo htmlspecialchars($row['nama']); ?></td>
            <td>
                <a href="edit.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
                <a href="dashboard.php?hapus=<?php echo $row['id']; ?>" onclick="return confirm('Yakin hapus?')"><button>Hapus</button></a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
<?php else: ?>
    <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h2>
    <a href="logout.php"><button>Logout</button></a>
<?php endif; ?>
</body>
</html>