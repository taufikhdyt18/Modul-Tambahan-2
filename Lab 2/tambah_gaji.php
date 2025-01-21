<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "startup_def";

$koneksi = mysqli_connect($host, $username, $password, $database);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$pesan = "";

// Proses form jika ada POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $departemen = $_POST['departemen'];
    $gaji_pokok = $_POST['gaji_pokok'];
    $tunjangan = $_POST['tunjangan'];

    // Validasi input
    if (empty($nama) || empty($jabatan) || empty($departemen) || empty($gaji_pokok) || empty($tunjangan)) {
        $pesan = "Semua field harus diisi!";
    } else {
        // Query untuk insert data
        $query = "INSERT INTO pegawai (nama, id_jabatan, id_department, gaji_pokok, tunjangan) 
                 VALUES ('$nama', $jabatan, $departemen, $gaji_pokok, $tunjangan)";
        
        if (mysqli_query($koneksi, $query)) {
            $pesan = "Data gaji berhasil ditambahkan!";
        } else {
            $pesan = "Error: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data Gaji</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"],
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .btn {
            padding: 10px 15px;
            background-color: #9523C5;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #9523C5;
        }
        .pesan {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .sukses {
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            border: 1px solid #ebccd1;
            color: #a94442;
        }
        .tombol-container {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Tambah Data Gaji Pegawai</h2>
        
        <?php if (!empty($pesan)): ?>
            <div class="pesan <?php echo (strpos($pesan, 'berhasil') !== false) ? 'sukses' : 'error'; ?>">
                <?php echo $pesan; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Pegawai:</label>
                <input type="text" name="nama" required>
            </div>

            <div class="form-group">
                <label>Jabatan:</label>
                <select name="jabatan" required>
                    <option value="">Pilih Jabatan</option>
                    <?php
                    $query_jabatan = "SELECT * FROM jabatan";
                    $result_jabatan = mysqli_query($koneksi, $query_jabatan);
                    while ($row = mysqli_fetch_assoc($result_jabatan)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nama_jabatan'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Departemen:</label>
                <select name="departemen" required>
                    <option value="">Pilih Departemen</option>
                    <?php
                    $query_dept = "SELECT * FROM departments";
                    $result_dept = mysqli_query($koneksi, $query_dept);
                    while ($row = mysqli_fetch_assoc($result_dept)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nama_department'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Gaji Pokok:</label>
                <input type="number" name="gaji_pokok" required>
            </div>

            <div class="form-group">
                <label>Tunjangan:</label>
                <input type="number" name="tunjangan" required>
            </div>

            <div class="tombol-container">
                <button type="submit" class="btn">Simpan Data</button>
                <a href="index.php" class="btn" style="background-color: #6c757d; text-decoration: none;">Kembali ke Daftar</a>
            </div>
        </form>
    </div>
</body>
</html>