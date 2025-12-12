<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1, h2 { color: #333; }
        form { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #45a049; }
        .back { margin-top: 10px; display: inline-block; }
        .msg { padding: 10px; border-radius:4px; margin-bottom: 20px; }
        .msg.ok { background:#e8f5e8; border:1px solid #c8e6c9; color:#2e7d32; }
        .msg.err { background:#ffebee; border:1px solid #ffcdd2; color:#c62828; }
    </style>
</head>
<body>
<div class="container">
<?php

if (!isset($_GET['table']) || empty($_GET['table'])) {
    echo "<div class='msg err'>Parameter table tidak ditemukan. Gunakan create.php?table=nama_tabel</div>";
    echo "<a href='index.php' class='back btn'>Kembali</a>";
    exit;
}

$table = $_GET['table'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "";
    $params = [];

    switch($table) {

        case 'pelanggan':
            $nama = trim($_POST['nama'] ?? '');
            $alamat = trim($_POST['alamat'] ?? '');
            $telepon = trim($_POST['telepon'] ?? '');
            $sql = "INSERT INTO pelanggan (nama, alamat, telepon) VALUES (?, ?, ?)";
            $params = [$nama, $alamat, $telepon];
            break;

        case 'kategori_produk':
            $nama_kategori = trim($_POST['nama_kategori'] ?? '');
            $sql = "INSERT INTO kategori_produk (nama_kategori) VALUES (?)";
            $params = [$nama_kategori];
            break;

        case 'produk':
            $nama_produk = trim($_POST['nama_produk'] ?? '');
            $harga = $_POST['harga'] ?? 0;
            $stok  = $_POST['stok'] ?? 0;
            $id_kategori = $_POST['id_kategori'] ?? null;
            $sql = "INSERT INTO produk (nama_produk, harga, stok, id_kategori) VALUES (?, ?, ?, ?)";
            $params = [$nama_produk, $harga, $stok, $id_kategori];
            break;

        case 'penjualan':
            $tanggal = $_POST['tanggal_penjualan'] ?? null;
            $id_pelanggan = $_POST['id_pelanggan'] ?? null;
            $total = $_POST['total'] ?? 0;
            $sql = "INSERT INTO penjualan (tanggal_penjualan, id_pelanggan, total) VALUES (?, ?, ?)";
            $params = [$tanggal, $id_pelanggan, $total];
            break;

        case 'detail_penjualan':
            $id_penjualan = $_POST['id_penjualan'] ?? null;
            $id_produk = $_POST['id_produk'] ?? null;
            $jumlah = $_POST['jumlah'] ?? 0;
            $subtotal = $_POST['subtotal'] ?? 0;

            $sql = "INSERT INTO detail_penjualan (id_penjualan, id_produk, jumlah, subtotal) 
                    VALUES (?, ?, ?, ?)";
            $params = [$id_penjualan, $id_produk, $jumlah, $subtotal];
            break;

        default:
            echo "<div class='msg err'>Tabel tidak dikenali: ".htmlspecialchars($table)."</div>";
            exit;
    }

    if ($sql) {
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo "<div class='msg err'><strong>Prepare error:</strong> " . htmlspecialchars($conn->error) . "</div>";
            exit;
        }

        $types = str_repeat('s', count($params));

        if (count($params) > 0) {
            if (!$stmt->bind_param($types, ...$params)) {
                echo "<div class='msg err'>Bind error: " . htmlspecialchars($stmt->error) . "</div>";
                exit;
            }
        }

        if ($stmt->execute()) {
            echo "<div class='msg ok'>Data berhasil ditambahkan!</div>";
            echo "<a href='index.php' class='back btn'>Kembali ke Daftar</a>";
        } else {
            echo "<div class='msg err'>Execute error: " . htmlspecialchars($stmt->error) . "</div>";
        }

        $stmt->close();
    }

} else {

    echo "<h1>Tambah Data " . ucfirst(str_replace('_', ' ', $table)) . "</h1>";
    echo "<form method='post'>";

    switch($table) {

        case 'pelanggan':
            echo "
            <div class='form-group'>
                <label>Nama Pelanggan:</label>
                <input type='text' name='nama' required>
            </div>
            <div class='form-group'>
                <label>Alamat:</label>
                <input type='text' name='alamat' required>
            </div>
            <div class='form-group'>
                <label>Telepon:</label>
                <input type='text' name='telepon' required>
            </div>";
            break;

        case 'kategori_produk':
            echo "
            <div class='form-group'>
                <label>Nama Kategori:</label>
                <input type='text' name='nama_kategori' required>
            </div>";
            break;

        case 'produk':
            echo "
            <div class='form-group'>
                <label>Nama Produk:</label>
                <input type='text' name='nama_produk' required>
            </div>
            <div class='form-group'>
                <label>Harga:</label>
                <input type='number' name='harga' step='0.01' required>
            </div>
            <div class='form-group'>
                <label>Stok:</label>
                <input type='number' name='stok' required>
            </div>
            <div class='form-group'>
                <label>Kategori:</label>
                <select name='id_kategori' required>
                    <option value=''>Pilih Kategori</option>";
                    $res = $conn->query("SELECT * FROM kategori_produk");
                    while($r = $res->fetch_assoc()) {
                        echo "<option value='".$r['id_kategori']."'>".$r['nama_kategori']."</option>";
                    }
            echo "</select></div>";
            break;

        case 'penjualan':
            echo "
            <div class='form-group'>
                <label>Tanggal Penjualan:</label>
                <input type='date' name='tanggal_penjualan' required>
            </div>
            <div class='form-group'>
                <label>Pelanggan:</label>
                <select name='id_pelanggan' required>
                    <option value=''>Pilih Pelanggan</option>";
                    $res = $conn->query("SELECT * FROM pelanggan");
                    while($r = $res->fetch_assoc()) {
                        echo "<option value='".$r['id_pelanggan']."'>".$r['nama']."</option>";
                    }
            echo "</select></div>
            <div class='form-group'>
                <label>Total:</label>
                <input type='number' name='total' step='0.01' required>
            </div>";
            break;

        case 'detail_penjualan':
            echo "
            <div class='form-group'>
                <label>ID Penjualan:</label>
                <select name='id_penjualan' required>
                    <option value=''>Pilih Penjualan</option>";
                    $res = $conn->query("SELECT * FROM penjualan");
                    while($r = $res->fetch_assoc()) {
                        echo "<option value='".$r['id_penjualan']."'>Penjualan #".$r['id_penjualan']."</option>";
                    }
            echo "</select></div>

            <div class='form-group'>
                <label>Produk:</label>
                <select name='id_produk' required>  
                    <option value=''>Pilih Produk</option>";
                    $res = $conn->query("SELECT * FROM produk");
                    while($r = $res->fetch_assoc()) {
                        echo "<option value='".$r['id_produk']."'>".$r['nama_produk']."</option>";
                    }
            echo "</select></div>

            <div class='form-group'>
                <label>Jumlah:</label>
                <input type='number' name='jumlah' required>
            </div>

            <div class='form-group'>
                <label>Subtotal:</label>
                <input type='number' name='subtotal' step='0.01' required>
            </div>";
            break;

        default:
            echo "<div class='msg err'>Form untuk tabel ini belum disiapkan.</div>";
            break;
    }

    echo "<button type='submit' class='btn'>Simpan</button></form>";
    echo "<a href='index.php' class='back btn'>Kembali</a>";
}
?>
</div>
</body>
</html>
<?php $conn->close(); ?>
