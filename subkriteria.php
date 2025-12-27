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
                <?php 
                $datasub = mysqli_query($koneksi,"SELECT * FROM tl_kriteria WHERE id_kriteria ='$_GET[id_kriteria]'");
                $asub = mysqli_fetch_array($datasub);?>
                   <h3>Subkriteria(<a href="kriteria.php"><?php echo $asub['nama_kriteria']?></a>)</h3>
                <div class="card">
                <div class="card-body">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-outline-info mb-3" data-bs-toggle="modal" data-bs-target="#Modal1">
                        Tambah Subkriteria
                        </button>
                        <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                        <th class="text-center">No</th>
                        <th >Nama Subkriteria</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $data = mysqli_query($koneksi,"SELECT * FROM tl_subkriteria WHERE id_kriteria ='$_GET[id_kriteria]' ORDER BY id_subkriteria");
                    $no = 0;
                    while ($a=mysqli_fetch_array($data)) {
                        ?>
                    <tr>
                        <td class="text-center"scope="col"><?php echo ++$no?></td>
                        <td scope="col"><?php echo $a['nama_subkriteria']?></td>
                        <td class="text-center" scope="col"><?php echo $a['nilai_subkriteria']?></td>
                        <td class="text-center">
                        <div class='btn-group mb-1 text-center'>
                        <div class="dropdown">
                            <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button"
                                id="dropdownMenuButton" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                Aksi
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="subkriteria-edit.php?id_kriteria=<?= $a['id_kriteria']; ?>&id_subkriteria=<?php echo $a['id_subkriteria']; ?>">Edit</a>
                            <a class="dropdown-item" href="subkriteria-proses.php?id_kriteria=<?php echo $a['id_kriteria']; ?>&id_subkriteria=<?php echo $a['id_subkriteria']; ?>&proses=hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                            </div>
                        </div>
                    </div>
                        </td>
                    </tr>
                    <?php } ?>
                        </td>
                    </tr>
        
                    </tbody>
                        </table>
                    </div>
                </div>
                </div>
            </div>
            </main> 
               <?php require "layout/footer.php";?>
           </div>
    </div>
    <!-- Modal -->
<div class="modal fade" id="Modal1" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Subkriteria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="subkriteria-proses.php?proses=simpan" method="post">
            <input type="hidden" name="id_kriteria" value="<?php echo $_GET['id_kriteria']?>" hidden>
        <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Nama</label>
          <input type="text" class="form-control" name="nama_subkriteria" id="nama_subkriteria" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Nilai</label>
          <input type="number" class="form-control" name="nilai_subkriteria" id="nilai_subkriteria" aria-describedby="emailHelp">
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