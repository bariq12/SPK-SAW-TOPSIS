<?php
require "koneksi.php";

// Cek apakah 'id_kriteria' ada di URL dan tidak kosong
if (isset($_GET['id_kriteria']) && !empty($_GET['id_kriteria'])) {
    $id_kriteria = intval($_GET['id_kriteria']); // Pastikan hanya angka yang diproses untuk keamanan

    $sql = "SELECT * FROM tl_kriteria WHERE id_kriteria = '$id_kriteria' ";
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
                <h3>kriteria Edit</h3>
                <div class="card" style="height:30rem">
                <div class="card-body">
                <div class="row">
                <div class="col">
                    <form action="kriteria-proses.php?proses=edit" method="POST">
                        <div class="form-group">
                            <label for="basicInput">Nama</label>
                            <input type="text" class="form-control" name="id_kriteria" value="<?=$row['id_kriteria'];?>" hidden>
                            <input type="text" class="form-control mb-2 mt-2" name="nama_kriteria" value="<?=$row['nama_kriteria'];?>">
                        </div>
                        <div class="form-group">
                            <label for="basicInput">Bobot</label>
                            <input type="number" class="form-control mb-2 mt-2" name="bobot" value="<?=$row['bobot'];?>">
                        </div>
                        <div class="form-group">
                           <label for="basicInput">Atribut</label>
                                <?php 
                                // Mendapatkan id_kriteria dari parameter GET
                                $id_kriteria = $_GET['id_kriteria'];
                                $query5 = mysqli_query($koneksi, "SELECT * FROM tl_kriteria WHERE id_kriteria='" . $id_kriteria . "'");
                                $result5 = mysqli_fetch_array($query5);
                                $atribut = $result5['atribut'];
                                ?>
                                <select class="form-control mb-2 mt-2" name="atribut" id="">
                                    <option value="<?php echo $atribut; ?>" selected><?php echo ucfirst($atribut); ?></option>
                                    <?php if ($atribut == 'benefit') { ?>
                                        <option value="cost">Cost</option>
                                    <?php } elseif ($atribut == 'cost') { ?>
                                        <option value="benefit">Benefit</option>
                                    <?php } ?>
                                </select>
                        </div>
                        <div class="form-group">
                            <a href="kriteria.php" class="btn btn-danger btn-sm" >
                           Batal
                       </a>
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
