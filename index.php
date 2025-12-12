<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sistem Penjualan - CRUD</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 12px; margin: 2px; text-decoration: none; border-radius: 4px; }
        .btn-add { background-color: #4CAF50; color: white; }
        .btn-edit { background-color: #2196F3; color: white; }
        .btn-delete { background-color: #f44336; color: white; }
        .btn-view { background-color: #ff9800; color: white; }
        .action { white-space: nowrap; }
    </style>
</head>
<body>
<div class="container">
    <h1>Sistem Manajemen Penjualan</h1>
    
    <!-- Tabel Pelanggan -->
    <h2>Data Pelanggan</h2>
    <a href="create.php?table=pelanggan" class="btn btn-add">Tambah Pelanggan</a>
    <table>
        <tr>
            <th>ID Pelanggan</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Telepon</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM pelanggan";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_pelanggan"]."</td>
                    <td>".$row["nama"]."</td>
                    <td>".$row["alamat"]."</td>
                    <td>".$row["telepon"]."</td>
                    <td class='action'>
                        <a href='update.php?table=pelanggan&id=".$row["id_pelanggan"]."' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?table=pelanggan&id=".$row["id_pelanggan"]."' class='btn btn-delete' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <!-- Tabel Kategori Produk -->
    <h2>Data Kategori Produk</h2>
    <a href="create.php?table=kategori_produk" class="btn btn-add">Tambah Kategori</a>
    <table>
        <tr>
            <th>ID Kategori</th>
            <th>Nama Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT * FROM kategori_produk";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_kategori"]."</td>
                    <td>".$row["nama_kategori"]."</td>
                    <td class='action'>
                        <a href='update.php?table=kategori_produk&id=".$row["id_kategori"]."' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?table=kategori_produk&id=".$row["id_kategori"]."' class='btn btn-delete' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <!-- Tabel Produk -->
    <h2>Data Produk</h2>
    <a href="create.php?table=produk" class="btn btn-add">Tambah Produk</a>
    <table>
        <tr>
            <th>ID Produk</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Kategori</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT p.*, k.nama_kategori 
                FROM produk p 
                JOIN kategori_produk k ON p.id_kategori = k.id_kategori";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_produk"]."</td>
                    <td>".$row["nama_produk"]."</td>
                    <td>".$row["harga"]."</td>
                    <td>".$row["stok"]."</td>
                    <td>".$row["nama_kategori"]."</td>
                    <td class='action'>
                        <a href='update.php?table=produk&id=".$row["id_produk"]."' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?table=produk&id=".$row["id_produk"]."' class='btn btn-delete' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <!-- Tabel Penjualan -->
    <h2>Data Penjualan</h2>
    <a href="create.php?table=penjualan" class="btn btn-add">Tambah Penjualan</a>
    <table>
        <tr>
            <th>ID Penjualan</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT p.*, pl.nama 
                FROM penjualan p 
                JOIN pelanggan pl ON p.id_pelanggan = pl.id_pelanggan";

        $result = $conn->query($sql);
        if (!$result) {
            die("Query error: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_penjualan"]."</td>
                    <td>".$row["tanggal_penjualan"]."</td>
                    <td>".$row["nama"]."</td>
                    <td>".$row["total"]."</td>
                    <td class='action'>
                        <a href='update.php?table=penjualan&id=".$row["id_penjualan"]."' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?table=penjualan&id=".$row["id_penjualan"]."' class='btn btn-delete' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <!-- Tabel Detail Penjualan -->
    <h2>Data Detail Penjualan</h2>
    <a href="create.php?table=detail_penjualan" class="btn btn-add">Tambah Detail</a>
    <table>
        <tr>
            <th>ID Detail</th>
            <th>ID Penjualan</th>
            <th>Produk</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
        <?php
        $sql = "SELECT dp.*, p.nama_produk 
                FROM detail_penjualan dp 
                JOIN produk p ON dp.id_produk = p.id_produk";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>".$row["id_detail"]."</td>
                    <td>".$row["id_penjualan"]."</td>
                    <td>".$row["nama_produk"]."</td>
                    <td>".$row["jumlah"]."</td>
                    <td>".$row["subtotal"]."</td>
                    <td class='action'>
                        <a href='update.php?table=detail_penjualan&id=".$row["id_detail"]."' class='btn btn-edit'>Edit</a>
                        <a href='delete.php?table=detail_penjualan&id=".$row["id_detail"]."' class='btn btn-delete' onclick=\"return confirm('Yakin hapus?')\">Hapus</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
<?php $conn->close(); ?>
