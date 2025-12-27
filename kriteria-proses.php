<?php
include "koneksi.php";

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 'simpan') {
        $nama = $koneksi->real_escape_string($_POST['nama']);
        $bobot = floatval($_POST['bobot']); // Pastikan bobot dalam format angka
        $atribut = $koneksi->real_escape_string($_POST['atribut']);

        $sql = "INSERT INTO tl_kriteria (nama_kriteria, bobot, atribut) VALUES ('$nama', '$bobot', '$atribut')";

        if ($koneksi->query($sql) === TRUE) {
            header("Location: ./kriteria.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $koneksi->error;
        }
    } 
    elseif ($_GET['proses'] == 'edit') {
        if (isset($_POST['id_kriteria']) && isset($_POST['nama_kriteria']) && isset($_POST['bobot']) && isset($_POST['atribut'])) {
            $id_kriteria = intval($_POST['id_kriteria']);
            $nama = $koneksi->real_escape_string($_POST['nama_kriteria']);
            $bobot = floatval($_POST['bobot']);
            $atribut = $koneksi->real_escape_string($_POST['atribut']);

            $sql = "UPDATE tl_kriteria SET nama_kriteria='$nama', bobot='$bobot', atribut='$atribut' WHERE id_kriteria='$id_kriteria'";

            if ($koneksi->query($sql) === TRUE) {
                header("Location: ./kriteria.php");
                exit();
            } else {
                echo "Error: " . $sql . "<br>" . $koneksi->error;
            }
        } else {
            echo "<script>alert('Data tidak lengkap!'); window.history.back();</script>";
        }
    } 
    elseif ($_GET['proses'] == 'hapus') {
        if (isset($_GET['id_kriteria'])) {
            $id_kriteria = intval($_GET['id_kriteria']); // Pastikan ID hanya angka

            // Langkah 1: Hapus semua subkriteria terkait dari tabel tl_subkriteria
            $sql_delete_subkriteria = "DELETE FROM tl_subkriteria WHERE id_kriteria = $id_kriteria";
            if (!$koneksi->query($sql_delete_subkriteria)) {
                echo "Error deleting subcriteria: " . $koneksi->error;
                exit();
            }

            // Langkah 2: Hapus kriteria dari tabel tl_kriteria
            $sql_delete_kriteria = "DELETE FROM tl_kriteria WHERE id_kriteria = $id_kriteria";
            if ($koneksi->query($sql_delete_kriteria) === TRUE) {
                // Cek apakah masih ada data dalam tabel tl_kriteria
                $result_kriteria = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tl_kriteria");
                $row_kriteria = mysqli_fetch_assoc($result_kriteria);

                if ($row_kriteria['total'] == 0) {
                    // Jika semua data sudah terhapus, reset Auto Increment ke 1 untuk tl_kriteria
                    mysqli_query($koneksi, "ALTER TABLE tl_kriteria AUTO_INCREMENT = 1");
                }

                // Cek apakah masih ada data dalam tabel tl_subkriteria
                $result_subkriteria = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM tl_subkriteria");
                $row_subkriteria = mysqli_fetch_assoc($result_subkriteria);

                if ($row_subkriteria['total'] == 0) {
                    // Jika semua data sudah terhapus, reset Auto Increment ke 1 untuk tl_subkriteria
                    mysqli_query($koneksi, "ALTER TABLE tl_subkriteria AUTO_INCREMENT = 1");
                }

                // Redirect ke halaman kriteria setelah berhasil
                header("Location: ./kriteria.php");
                exit();
            } else {
                echo "Error deleting criteria: " . $koneksi->error;
            }
        } else {
            echo "<script>alert('ID tidak ditemukan!'); window.history.back();</script>";
        }
    }
}

