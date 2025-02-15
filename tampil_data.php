<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = "";
$db   = "jawaban_uas";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data mahasiswa, mata kuliah, dan SKS dengan LEFT JOIN untuk memastikan semua mahasiswa tampil
$sql = "SELECT DISTINCT mahasiswa.nama, mahasiswa.nim, 
               COALESCE(sks.jml_sks, 0) AS jml_sks, 
               COALESCE(matkul.nama, 'Belum Memilih') AS nama_matkul 
        FROM mahasiswa 
        LEFT JOIN sks ON mahasiswa.id_sks = sks.id_sks 
        LEFT JOIN matkul ON matkul.id_matkul = sks.id_sks";

// Eksekusi query dan cek apakah terjadi kesalahan
$result = $conn->query($sql);
if (!$result) {
    die("Error: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pemilihan SKS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <h2>Data Pemilihan SKS</h2>
    
    <table>
        <tr>
            <th>Nama Mahasiswa</th>
            <th>NIM</th>
            <th>Mata Kuliah</th>
            <th>Jumlah SKS</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>".$row['nama']."</td>";
                echo "<td>".$row['nim']."</td>";
                echo "<td>".$row['nama_matkul']."</td>";
                echo "<td>".$row['jml_sks']."</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>Tidak ada data</td></tr>";
        }
        ?>
    </table>

    <br>
    <a href="form_sks.php" class="btn">Kembali ke Form Pemilihan SKS</a>

</body>
</html>

<?php
$conn->close();
?>
