<?php
include "koneksi.php";

if (isset($_GET['proses'])) {
        if ($_GET['proses'] == 'simpan') {
        $nama = $_POST['nama'];
        // $x = $db->query($sql);
        // var_dump($x);
        $sql = "INSERT INTO tl_alternatif (nama_alter) VALUES ('$nama')";

        if ($koneksi->query($sql) === true) {
            header("location:./alter.php");
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
}
    elseif ($_GET['proses'] == 'edit') {
        if (isset($_POST['id_alter']) && isset($_POST['nama_alter'])) {
            $id_alter = intval($_POST['id_alter']); // Pastikan ID hanya angka
            $nama_alter = $koneksi->real_escape_string($_POST['nama_alter']); 

            $sql = "UPDATE tl_alternatif SET nama_alter='$nama_alter' WHERE id_alter='$id_alter'";
            $result = $koneksi->query($sql);

            if ($result) {
                echo "<script>alert('Data berhasil diperbarui!'); window.location.href = 'alter.php';</script>";
            } else {
                echo "<script>alert('Error: " . $koneksi->error . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('ID atau Nama tidak valid!'); window.history.back();</script>";
        }
    } 
    elseif ($_GET['proses'] == 'hapus') {
       if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Pastikan ID adalah angka
    $sql = "DELETE FROM tl_alternatif WHERE id_alter = $id";

    if ($koneksi->query($sql) === TRUE) {
        // Cek apakah masih ada data dalam tabel
        $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tl_alternatif");
        $row = mysqli_fetch_assoc($result);

        if ($row['total'] == 0) {
            // Jika semua data sudah terhapus, reset Auto Increment ke 1
            mysqli_query($koneksi, "ALTER TABLE tl_alternatif AUTO_INCREMENT = 1");
        }

        header("Location: ./alter.php");
        exit();
    } else {
        echo "Error: " . $koneksi->error;
    }
} else {
    echo "ID tidak ditemukan!";
}
    }
}

