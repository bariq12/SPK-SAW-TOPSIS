<?php
require "koneksi.php";

// Cek apakah 'id_alter' ada di URL dan tidak kosong
if (isset($_GET['id_alter']) && !empty($_GET['id_alter'])) {
    $id_alter = intval($_GET['id_alter']); // Pastikan hanya angka yang diproses untuk keamanan

    // Perbaiki nama tabel dari 'alternative' ke 'alternatif'
    $sql = "SELECT * FROM tl_alternatif WHERE id_alter = '$id_alter' ";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_array();
    } else {
        echo "Data dengan ID tersebut tidak ditemukan!";
        exit();
    }
} else {
    echo "ID tidak valid atau tidak ditemukan!";
    exit();
}
?>


<html lang="en">
    <?php include 'layout/header.php'?>
    <body>
    <div class="wrapper">
    <?php require "layout/sidebar.php";?>
           <div class="main">
           <main class="content px-3 py-4">
            <div class="container-fluid">
                <h3>alternatif Edit</h3>
                <div class="card" style="height:30rem">
                <div class="card-body">
                <div class="row">
                <div class="col">
                    <form action="alter-proses.php?proses=edit" method="POST">
                    <div class="form-group">
                    <label for="basicInput">nama</label>
                    <input type="text" class="form-control " name="id_alter" value="<?=$row['id_alter'];?>" hidden>
                    <input type="text" class="form-control mb-2 mt-2" name="nama_alter" value="<?=$row['nama_alter'];?>">
                    </div>
                    <div class="form-group">
                    <a href="kriteria.php" class="btn btn-danger btn-sm" > Batal </a>
                    <input type="submit" class="btn btn-info btn-sm">
                    </div>
                    </form>
                    </div>
                </div>
            </div>
            </main> 
               <?php require "layout/footer.php";?>
           </div>
    </div>
    <?php  require "layout/js.php"?>
</body>
</html>
