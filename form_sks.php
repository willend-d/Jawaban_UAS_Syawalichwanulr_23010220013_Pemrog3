<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jawaban_uas"; // Ganti dengan nama database yang kamu gunakan

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil daftar mata kuliah
$sql = "SELECT id_matkul, nama FROM matkul";
$result = $conn->query($sql);

// Jika tombol submit ditekan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_mahasiswa = $_POST["nama"];
    $nim            = $_POST["nim"];
    $id_matkul      = $_POST["id_matkul"];
    $jml_sks        = $_POST["jml_sks"];

    // Validasi jumlah SKS (misal batas maksimal 24 SKS)
    if ($jml_sks > 24) {
        echo "<script>alert('Jumlah SKS melebihi batas! Silakan pilih ulang.');</script>";
    } else {
        // Simpan data ke tabel SKS
        $insert_sks = "INSERT INTO sks (jml_sks) VALUES ('$jml_sks')";
        if ($conn->query($insert_sks) === TRUE) {
            $id_sks = $conn->insert_id; // Ambil ID SKS yang baru saja dimasukkan

            // Simpan data ke tabel Mahasiswa
            $insert_mhs = "INSERT INTO mahasiswa (nama, nim, id_sks) VALUES ('$nama_mahasiswa', '$nim', '$id_sks')";
            $conn->query($insert_mhs);

            // Simpan data ke tabel Matkul
            $insert_matkul = "UPDATE matkul SET id_sks = '$id_sks' WHERE id_matkul = '$id_matkul'";
            $conn->query($insert_matkul);

            echo "<script>alert('Data berhasil disimpan!');</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pemilihan SKS</title>
</head>
<body>
    <h2>Form Pemilihan SKS</h2>
    <form method="post">
        Nama Mahasiswa: <input type="text" name="nama" required><br><br>
        NIM: <input type="text" name="nim" required><br><br>

        Pilih Mata Kuliah:
        <select name="id_matkul" required>
            <?php
            // Koneksi ulang untuk mengambil data
            $conn = new mysqli($host, $user, $pass, $db);
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='".$row["id_matkul"]."'>".$row["nama"]."</option>";
                }
            }
            $conn->close();
            ?>
        </select><br><br>

        Jumlah SKS: <input type="number" name="jml_sks" required><br><br>
        <input type="submit" value="Simpan">
    </form>

    <br>
    <a href="tampil_data.php"><button>Lihat Data yang Sudah Disimpan</button></a>
</body>
</html>
