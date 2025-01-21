<?php
// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "startup_def";

// Buat koneksi
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fungsi format rupiah
function formatRupiah($angka) {
    return "Rp " . number_format($angka, 0, ',', '.');
}

// Fungsi hitung total gaji
function hitungTotalGaji($gaji_pokok, $tunjangan) {
    return $gaji_pokok + $tunjangan;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Total Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #9523C5;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total {
            font-weight: bold;
            background-color: #e9e9e9;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .ringkasan {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .ringkasan p {
            margin: 10px 0;
        }
        .btn {
            padding: 10px 15px;
            background-color: #9523C5;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Laporan Total Gaji dan Gaji Tertinggi Pegawai</h1>
        <?php
        // Query untuk mengambil data pegawai
        $query = "
            SELECT pegawai.*, jabatan.nama_jabatan, departments.nama_department 
            FROM pegawai 
            LEFT JOIN jabatan ON pegawai.id_jabatan = jabatan.id 
            LEFT JOIN departments ON pegawai.id_department = departments.id
        ";

        $result = mysqli_query($koneksi, $query);

        if ($result) {
        ?>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Departemen</th>
                <th>Gaji Pokok</th>
                <th>Tunjangan</th>
                <th>Total Gaji</th>
            </tr>
            <?php
            $no = 1;
            $total_seluruh_gaji = 0;
            $gaji_tertinggi = 0;
            $pegawai_gaji_tertinggi = '';

            while ($row = mysqli_fetch_assoc($result)) {
                $total_gaji = hitungTotalGaji($row['gaji_pokok'], $row['tunjangan']);
                $total_seluruh_gaji += $total_gaji;

                if ($total_gaji > $gaji_tertinggi) {
                    $gaji_tertinggi = $total_gaji;
                    $pegawai_gaji_tertinggi = $row['nama'];
                }
            ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['nama']; ?></td>
                <td><?php echo $row['nama_jabatan']; ?></td>
                <td><?php echo $row['nama_department']; ?></td>
                <td><?php echo formatRupiah($row['gaji_pokok']); ?></td>
                <td><?php echo formatRupiah($row['tunjangan']); ?></td>
                <td><?php echo formatRupiah($total_gaji); ?></td>
            </tr>
            <?php
            }
            ?>
            <tr class="total">
                <td colspan="6">Total Seluruh Gaji</td>
                <td><?php echo formatRupiah($total_seluruh_gaji); ?></td>
            </tr>
        </table>
        <div style="margin-bottom: 20px;">
            <a href="tambah_gaji.php" class="btn">Tambah Data Gaji</a>
        </div>

        <div class="ringkasan">
            <h2>Ringkasan:</h2>
            <p>Pegawai dengan gaji tertinggi: <strong><?php echo $pegawai_gaji_tertinggi; ?></strong></p>
            <p>Jumlah gaji tertinggi: <strong><?php echo formatRupiah($gaji_tertinggi); ?></strong></p>
        </div>
        <?php
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }

        // Tutup koneksi
        mysqli_close($koneksi);
        ?>
    </div>
</body>
</html>
