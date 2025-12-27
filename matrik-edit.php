<?php
require "koneksi.php";
?>

<html lang="en">
    <?php include 'layout/header.php'?>
    <body>
    <div class="wrapper">
    <?php require "layout/sidebar.php";?>
           <div class="main">
           <main class="content px-3 py-4">
            <div class="container-fluid">
                <h3>matrik Edit</h3>
                <div class="card" >
                <div class="card-body">
                <div class="row">
                <div class="col">
                    <form action="matrik-proses.php?proses=edit" method="POST">
                    <div class="form-group">
          <label for="exampleInputEmail1" class="form-label">Nama Alternatif</label>
          <?php 
          $id_alter  =$_GET['id_alter'];
          $query5 = mysqli_query($koneksi,"SELECT * FROM alternatif  WHERE id_alter='".$id_alter."'");
          $result5 = mysqli_fetch_array($query5);
          ?>
          <select class="form-control"name="id_alter" id="" aria-label="Disabled select" disabled>
            <option  selected value="<?php echo $result5['id_alter']?>"><?php echo $result5['nama_alter']?></option>
          </select>
            </div>
           <?php
            $id_alter = $_GET['id_alter']; // Ambil ID Alternatif dari URL

            // Ambil data kriteria
            $query4 = mysqli_query($koneksi, "SELECT * FROM kriteria ORDER BY id_kriteria");

            while ($result2 = mysqli_fetch_array($query4)) {
                $id_kriteria = $result2['id_kriteria'];
                $nama_kriteria = $result2['nama_kriteria'];

                // Ambil nilai sebelumnya dari tabel 'nilai' berdasarkan id_alter dan id_kriteria
                $query_nilai = mysqli_query($koneksi, "SELECT nilai FROM tl_nilai WHERE id_alter='$id_alter' AND id_kriteria='$id_kriteria'");
                $result_nilai = mysqli_fetch_array($query_nilai);
                $nilai_sblm = $result_nilai ? $result_nilai['nilai'] : ''; // Jika ada, ambil nilainya. Jika tidak, kosong.

                echo "
                <div class='form-group mb-2 mt-2'>
                    <label class='mb-1'>".$nama_kriteria."</label>
                    <input type='number' class='form-control' name='".$id_kriteria."' value='".$nilai_sblm."'>
                </div>
                ";
            }
        ?>

                    <div class="form-group">
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
