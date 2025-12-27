<?php
include "koneksi.php";

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 'simpan') {
        $id_kriteria = intval($_POST['id_kriteria']);
        $nama_subkriteria = $koneksi->real_escape_string($_POST['nama_subkriteria']); // Pastikan nama_subkriteria dalam format angka
        $nilai_subkriteria = intval($_POST['nilai_subkriteria']);

        $sql = "INSERT INTO tl_subkriteria (id_kriteria, nama_subkriteria, nilai_subkriteria) VALUES ('$id_kriteria', '$nama_subkriteria', '$nilai_subkriteria')";

        if ($koneksi->query($sql) === TRUE) {
            header("Location: ./subkriteria.php?id_kriteria=$_POST[id_kriteria]");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } 
    elseif ($_GET['proses'] == 'edit') {
             $id_subkriteria = intval($_GET['id_subkriteria']);
                $id_kriteria = intval($_POST['id_kriteria']);
                $nama_subkriteria = $koneksi->real_escape_string($_POST['nama_subkriteria']);
                $nilai_subkriteria = intval($_POST['nilai_subkriteria']);

                // Update data
                $sql = "UPDATE tl_subkriteria 
                        SET id_kriteria='$id_kriteria', 
                            nama_subkriteria='$nama_subkriteria', 
                            nilai_subkriteria='$nilai_subkriteria' 
                        WHERE id_subkriteria='$id_subkriteria'";

                if ($koneksi->query($sql) === TRUE) {
                    header("Location: ./subkriteria.php?id_kriteria=$id_kriteria");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $koneksi->error;
                }
    } 
    elseif ($_GET['proses'] == 'hapus') {
       $id_subkriteria = intval($_GET['id_subkriteria']);
        $id_kriteria = intval($_GET['id_kriteria']);

        // Hapus data
        $sql = "DELETE FROM tl_subkriteria WHERE id_subkriteria = '$id_subkriteria'";

        if ($koneksi->query($sql) === TRUE) {
            // Cek apakah tabel masih memiliki data
            $result = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tl_subkriteria");
            $row = mysqli_fetch_assoc($result);

            if ($row['total'] == 0) {
                // Jika tabel kosong, reset auto increment
                mysqli_query($koneksi, "ALTER TABLE tl_subkriteria AUTO_INCREMENT = 1");
            }

            header("Location: ./subkriteria.php?id_kriteria=$id_kriteria");
            exit();
        } else {
            echo "Error: " . $koneksi->error;
        }
   
    }
}

