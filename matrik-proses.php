<?php
include "koneksi.php";

if (isset($_GET['proses'])) {
    if ($_GET['proses'] == 'simpan') {
        // Ambil ID alternatif dari form
        $id_alter = $koneksi->real_escape_string($_POST['id_alter']);

        // Ambil semua kriteria
        $query_kriteria = mysqli_query($koneksi, "SELECT * FROM tl_kriteria");
        if (!$query_kriteria) {
            die("Error fetching criteria: " . $koneksi->error);
        }

        while ($kriteria = mysqli_fetch_assoc($query_kriteria)) {
            $id_kriteria = $kriteria['id_kriteria']; // ID Kriteria

            // Periksa apakah nilai untuk kriteria ini ada di POST
            if (isset($_POST[$id_kriteria])) {
                $selected_subkriteria_id = $koneksi->real_escape_string($_POST[$id_kriteria]);

                // Validasi bahwa subkriteria yang dipilih benar-benar ada di database
                $query_subkriteria = mysqli_query($koneksi, "SELECT id_subkriteria FROM tl_subkriteria WHERE id_subkriteria = '$selected_subkriteria_id'");
                if (!$query_subkriteria || mysqli_num_rows($query_subkriteria) == 0) {
                    die("Invalid subcriteria ID: $selected_subkriteria_id");
                }

                // Simpan ke database
                $sql = "INSERT INTO tl_nilai (id_alter, id_kriteria, id_subkriteria) 
                        VALUES ('$id_alter', '$id_kriteria', '$selected_subkriteria_id')";

                if (!$koneksi->query($sql)) {
                    echo "Error: " . $sql . "<br>" . $koneksi->error;
                }
            }
        }

        // Redirect setelah semua data tersimpan
        header("Location: ./matrik.php");
        exit();
    } elseif ($_GET['proses'] == 'hapus') {
        // Hapus data nilai berdasarkan ID alternatif
        $id_alter = $koneksi->real_escape_string($_GET['id_alter']);
        $sql = "DELETE FROM tl_nilai WHERE id_alter='$id_alter'";

        if ($koneksi->query($sql)) {
            // Cek apakah tabel masih memiliki data
            $cek_data = mysqli_query($koneksi, "SELECT COUNT(*) as jumlah FROM tl_nilai");
            $data = mysqli_fetch_assoc($cek_data);

            if ($data['jumlah'] == 0) {
                // Reset AUTO_INCREMENT jika tabel kosong
                mysqli_query($koneksi, "ALTER TABLE tl_nilai AUTO_INCREMENT = 1");
            }

            header("Location: ./matrik.php");
            exit();
        } else {
            echo "Error: " . $koneksi->error;
        }
    }
}
?>