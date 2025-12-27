<!DOCTYPE html>
<html lang="en">
    <?php 
    require "layout/header.php";
    require "koneksi.php";
    ?>
<body>
    <div class="wrapper">
    <?php require "layout/sidebar.php";?>
           <div class="main">
           <main class="content px-3 py-4">
            <div class="container-fluid">
                <h3>Alternatif</h3>
                <div class="card" style="height:30rem">
                <div class="card-body">
                    <h5 class="card-title">Tabel alternatif</h5>
                    <p class="card-text">
                    Data-data mengenai kandidat yang akan dievaluasi di representasikan dalam tabel berikut:
                    </p>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-outline-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Tambah Alternatif
                    </button>
                    <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                    <th scope="col" class="text-center">No</th>
                    <th scope="col" >Nama Alternatif</th>
                    <th scope="col" class="text-center">Opsi</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $data = mysqli_query($koneksi,"SELECT * FROM tl_alternatif ORDER BY id_alter");
                $no = 1;
                while ($a=mysqli_fetch_array($data)) {
                    ?>
                <tr>
                    <td scope="col" class="text-center"><?php echo $no++?></td>
                    <td scope="col" ><?php echo $a['nama_alter']?></td>
                    <td class="text-center">
                    <div class='btn-group mb-1'>
        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button"
                id="dropdownMenuButton" data-bs-toggle="dropdown"
                aria-haspopup="true" aria-expanded="false">
                Aksi
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="alter-edit.php?id_alter=<?= $a['id_alter']; ?>">Edit</a>
                <a class="dropdown-item" href="alter-proses.php?id=<?php echo $a['id_alter'];?>&proses=hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
            </div>
        </div>
    </div>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
                    </table>
                </div>
            </div>
            </main> 
               <?php require "layout/footer.php";?>
           </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Alternatif</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="alter-proses.php?proses=simpan" method="post">
            <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Nama</label>
          <input type="text" class="form-control" name="nama" id="nama" aria-describedby="emailHelp">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
    </div>
  </div>
</div>
    <?php  require "layout/js.php"?>
</body>
</html>