<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        h1 { color: #333; }
        form { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; }
        .btn { padding: 10px 15px; background-color: #2196F3; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background-color: #0b7dda; }
        .back { margin-top: 10px; display: inline-block; }
    </style>
</head>
<body>
<div class="container">

<?php
$table = $_GET['table'];
$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "";
    $params = [];

    switch($table) {

        case 'pelanggan':
            $nama = $_POST['nama'];
            $alamat = $_POST['alamat'];
            $telepon = $_POST['telepon'];

            $sql = "UPDATE pelanggan SET nama=?, alamat=?, telepon=? WHERE id_pelanggan=?";
            $params = [$nama, $alamat, $telepon, $id];
            break;

        case 'kategori_produk':
            $nama_kategori = $_POST['nama_kategori'];
            $sql = "UPDATE kategori_produk SET nama_kategori=? WHERE id_kategori=?";
            $params = [$nama_kategori, $id];
            break;

        case 'produk':
            $nama_produk = $_POST['nama_produk'];
            $harga = $_POST['harga'];
            $stok = $_POST['stok'];
            $id_kategori = $_POST['id_kategori'];

            $sql = "UPDATE produk SET nama_produk=?, harga=?, stok=?, id_kategori=? WHERE id_produk=?";
            $params = [$nama_produk, $harga, $stok, $id_kategori, $id];
            break;

        case 'penjualan':
            $tanggal = $_POST['tanggal_penjualan'];
            $id_pelanggan = $_POST['id_pelanggan'];
            $total = $_POST['total'];

            $sql = "UPDATE penjualan SET tanggal_penjualan=?, id_pelanggan=?, total=? WHERE id_penjualan=?";
            $params = [$tanggal, $id_pelanggan, $total, $id];
            break;

        case 'detail_penjualan':
            $id_penjualan = $_POST['id_penjualan'];
            $id_produk = $_POST['id_produk'];
            $jumlah = $_POST['jumlah'];
            $subtotal = $_POST['subtotal'];

            $sql = "UPDATE detail_penjualan SET id_penjualan=?, id_produk=?, jumlah=?, subtotal=? WHERE id_detail=?";
            $params = [$id_penjualan, $id_produk, $jumlah, $subtotal, $id];
            break;
    }

    if ($sql) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(str_repeat("s", count($params)), ...$params);
        
        if ($stmt->execute()) {
            echo "<div style='color:green;padding:10px;background:#e8f5e8;border:1px solid #c8e6c9;border-radius:4px;margin-bottom:20px;'>Data berhasil diperbarui!</div>";
            echo "<a href='index.php' class='btn back'>Kembali</a>";
        } else {
            echo "<div style='color:red;padding:10px;background:#ffebee;border:1px solid #ffcdd2;border-radius:4px;margin-bottom:20px;'>Error: ".$stmt->error."</div>";
        }

        $stmt->close();
    }

} else {

    echo "<h1>Edit Data " . ucfirst(str_replace("_", " ", $table)) . "</h1>";
    echo "<form method='post'>";

    switch($table) {

        case 'pelanggan':
            $sql = "SELECT * FROM pelanggan WHERE id_pelanggan=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            echo "
                <div class='form-group'>
                    <label>Nama Pelanggan:</label>
                    <input type='text' name='nama' value='{$row['nama']}' required>
                </div>
                <div class='form-group'>
                    <label>Alamat:</label>
                    <input type='text' name='alamat' value='{$row['alamat']}' required>
                </div>
                <div class='form-group'>
                    <label>Telepon:</label>
                    <input type='text' name='telepon' value='{$row['telepon']}' required>
                </div>
            ";
            break;

        case 'kategori_produk':
            $sql = "SELECT * FROM kategori_produk WHERE id_kategori=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            echo "
                <div class='form-group'>
                    <label>Nama Kategori:</label>
                    <input type='text' name='nama_kategori' value='{$row['nama_kategori']}' required>
                </div>
            ";
            break;

        case 'produk':
            $sql = "SELECT * FROM produk WHERE id_produk=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            echo "
                <div class='form-group'>
                    <label>Nama Produk:</label>
                    <input type='text' name='nama_produk' value='{$row['nama_produk']}' required>
                </div>
                <div class='form-group'>
                    <label>Harga:</label>
                    <input type='number' name='harga' value='{$row['harga']}' step='0.01' required>
                </div>
                <div class='form-group'>
                    <label>Stok:</label>
                    <input type='number' name='stok' value='{$row['stok']}' required>
                </div>
                <div class='form-group'>
                    <label>Kategori:</label>
                    <select name='id_kategori' required>";
            
            $kategori = $conn->query("SELECT * FROM kategori_produk");
            while ($k = $kategori->fetch_assoc()) {
                $sel = $k['id_kategori'] == $row['id_kategori'] ? "selected" : "";
                echo "<option value='{$k['id_kategori']}' $sel>{$k['nama_kategori']}</option>";
            }

            echo "</select></div>";
            break;

        case 'penjualan':
            $sql = "SELECT * FROM penjualan WHERE id_penjualan=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            echo "
                <div class='form-group'>
                    <label>Tanggal Penjualan:</label>
                    <input type='date' name='tanggal_penjualan' value='{$row['tanggal_penjualan']}' required>
                </div>
                <div class='form-group'>
                    <label>Pelanggan:</label>
                    <select name='id_pelanggan' required>";
            
            $pelanggan = $conn->query("SELECT * FROM pelanggan");
            while ($p = $pelanggan->fetch_assoc()) {
                $sel = $p['id_pelanggan'] == $row['id_pelanggan'] ? "selected" : "";
                echo "<option value='{$p['id_pelanggan']}' $sel>{$p['nama']}</option>";
            }

            echo "</select></div>
                <div class='form-group'>
                    <label>Total:</label>
                    <input type='number' name='total' value='{$row['total']}' step='0.01' required>
                </div>";
            break;

        case 'detail_penjualan':
            $sql = "SELECT * FROM detail_penjualan WHERE id_detail=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();

            echo "
                <div class='form-group'>
                    <label>ID Penjualan:</label>
                    <select name='id_penjualan' required>";
            
            $penjualan = $conn->query("SELECT * FROM penjualan");
            while ($pn = $penjualan->fetch_assoc()) {
                $sel = $pn['id_penjualan'] == $row['id_penjualan'] ? "selected" : "";
                echo "<option value='{$pn['id_penjualan']}' $sel>Penjualan #{$pn['id_penjualan']}</option>";
            }

            echo "</select></div>
                <div class='form-group'>
                    <label>Produk:</label>
                    <select name='id_produk' required>";

            $produk = $conn->query("SELECT * FROM produk");
            while ($pr = $produk->fetch_assoc()) {
                $sel = $pr['id_produk'] == $row['id_produk'] ? "selected" : "";
                echo "<option value='{$pr['id_produk']}' $sel>{$pr['nama_produk']}</option>";
            }

            echo "</select></div>
                <div class='form-group'>
                    <label>Jumlah:</label>
                    <input type='number' name='jumlah' value='{$row['jumlah']}' required>
                </div>
                <div class='form-group'>
                    <label>Subtotal:</label>
                    <input type='number' name='subtotal' value='{$row['subtotal']}' step='0.01' required>
                </div>";
            break;

    }

    echo "<button class='btn' type='submit'>Update</button>";
    echo "</form><a class='btn back' href='index.php'>Kembali</a>";

    $stmt->close();
}

$conn->close();
?>

</div>
</body>
</html>
