<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hapus Data</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; text-align: center; }
        .message { padding: 20px; margin: 20px 0; border-radius: 5px; }
        .success { background-color: #e8f5e8; border: 1px solid #c8e6c9; color: #2e7d32; }
        .error { background-color: #ffebee; border: 1px solid #ffcdd2; color: #c62828; }
        .btn { padding: 10px 20px; background: #f44336; color: white; text-decoration: none; border-radius: 5px; }
        .btn:hover { background: #c62828; }
    </style>
</head>
<body>
<div class="container">

<?php
// ====== AMBIL PARAMETER ======
$table = $_GET['table'] ?? null;
$id    = $_GET['id'] ?? null;

// Validasi awal
if (!$table || !$id) {
    echo "<div class='message error'>Parameter tidak lengkap!</div>";
    exit;
}

// Mapping primary key
$primaryKey = [
    'pelanggan'        => 'id_pelanggan',
    'kategori_produk'  => 'id_kategori',
    'produk'           => 'id_produk',
    'penjualan'        => 'id_penjualan',
    'detail_penjualan' => 'id_detail'
];

if (!isset($primaryKey[$table])) {
    echo "<div class='message error'>Tabel tidak dikenali!</div>";
    exit;
}

$canDelete = true;
$message   = "Data tidak boleh dihapus!";

// ==========================
// CEK RELASI FOREIGN KEY
// ==========================
$checkQueries = [
    'pelanggan'       => "SELECT COUNT(*) AS c FROM penjualan WHERE id_pelanggan = ?",
    'kategori_produk' => "SELECT COUNT(*) AS c FROM produk WHERE id_kategori = ?",
    'produk'          => "SELECT COUNT(*) AS c FROM detail_penjualan WHERE id_produk = ?",
    'penjualan'       => "SELECT COUNT(*) AS c FROM detail_penjualan WHERE id_penjualan = ?",
    // detail_penjualan TIDAK punya relasi → aman langsung hapus
];

if (isset($checkQueries[$table])) {
    $stmt = $conn->prepare($checkQueries[$table]);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();

    if ($res['c'] > 0) {
        $canDelete = false;

        switch ($table) {
            case 'pelanggan':        $message = "Tidak dapat menghapus: pelanggan masih memiliki transaksi penjualan."; break;
            case 'kategori_produk':  $message = "Tidak dapat menghapus: kategori masih memiliki produk."; break;
            case 'produk':           $message = "Tidak dapat menghapus: produk masih memiliki detail penjualan."; break;
            case 'penjualan':        $message = "Tidak dapat menghapus: penjualan masih memiliki detail penjualan."; break;
        }
    }
}

// ==========================
// EKSEKUSI DELETE
// ==========================

if ($canDelete) {

    $pk = $primaryKey[$table];
    $sql = "DELETE FROM $table WHERE $pk = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "<div class='message success'>$message</div>";
    } else {
        echo "<div class='message error'>Gagal menghapus: " . $stmt->error . "</div>";
    }

} else {
    echo "<div class='message error'>$message</div>";
}

echo "<a href='index.php' class='btn'>Kembali</a>";
?>

</div>
</body>
</html>
<?php $conn->close(); ?>
