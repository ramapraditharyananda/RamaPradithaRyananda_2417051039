<?php
session_start();

if (!isset($_SESSION['nama']) || $_SESSION['nama'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

require 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {
    $id = intval($_POST['id']);
    $nama_baru = trim($_POST['nama']);
    $password_baru = $_POST['password'];
    $hashed = password_hash($password_baru, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");
    $stmt->bind_param("ssi", $nama_baru, $hashed, $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT nama FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nama_lama);
$stmt->fetch();
$stmt->close();

if (!$nama_lama) {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Pengguna</title>
</head>
<body>
    <h2>Edit Data Pengguna</h2>
    <label>Nama Pengguna:</label><br>
    <input type="text" id="nama" value="<?php echo htmlspecialchars($nama_lama); ?>"><br><br>
    <label>Password Baru:</label><br>
    <input type="password" id="password" placeholder="Masukkan password baru"><br><br>
    <button onclick="submitForm()">Simpan Perubahan</button>
    <br><br>
    <a href="dashboard.php"><button type="button">Batal</button></a>

    <script>
    function submitForm() {
        var nama = document.getElementById('nama').value;
        var password = document.getElementById('password').value;
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'edit.php';
        var fId = document.createElement('input');
        fId.type = 'hidden'; fId.name = 'id'; fId.value = '<?php echo $id; ?>';
        var fNama = document.createElement('input');
        fNama.type = 'hidden'; fNama.name = 'nama'; fNama.value = nama;
        var fPass = document.createElement('input');
        fPass.type = 'hidden'; fPass.name = 'password'; fPass.value = password;
        var fSimpan = document.createElement('input');
        fSimpan.type = 'hidden'; fSimpan.name = 'simpan'; fSimpan.value = '1';
        form.appendChild(fId);
        form.appendChild(fNama);
        form.appendChild(fPass);
        form.appendChild(fSimpan);
        document.body.appendChild(form);
        form.submit();
    }
    </script>
</body>
</html>