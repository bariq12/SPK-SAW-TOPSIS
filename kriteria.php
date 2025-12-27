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
                   <h3>Bobot dan kriteria</h3>
                <div class="card">
                <div class="card-body">
                   <h5 class="card-title">Tabel Bobot dan kriteria</h5>
                        <p class="card-text">
                        Pengambil keputusan memberi bobot preferensi dari setiap kriteria dengan
                        masing-masing jenisnya (keuntungan/benefit atau biaya/cost):
                        </p>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-outline-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah bobot dan kriteria
                        </button>
                        <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                        <th class="text-center">No</th>
                        <th class="text-center">Simbol</th>
                        <th>Kriteria</th>
                        <th class="text-center">Bobot</th>
                        <th class="text-center">Atribut</th>
                        <th class="text-center">Subkriteria</th>
                        <th class="text-center">Opsi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $data = mysqli_query($koneksi,"SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                    $no = 0;
                    while ($a=mysqli_fetch_array($data)) {
                        ?>
                    <tr>
                        <td class="text-center"scope="col"><?php echo ++$no?></td>
                        <td class="text-center" scope="col">C<?php echo $no?></td>
                        <td scope="col"><?php echo $a['nama_kriteria']?></td>
                        <td class="text-center" scope="col"><?php echo $a['bobot']?></td>
                        <td class="text-center" scope="col"><?php echo $a['atribut']?></td>
                        <td class="text-center"> 
                        <a class="btn btn-success btn-sm" href="subkriteria.php?id_kriteria=<?= $a['id_kriteria']; ?>">Subkriteria</a>
                        </td>
                        <td class="text-center">
                        <div class='btn-group mb-1'>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle me-1 btn-sm" type="button"
                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Aksi
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="kriteria-edit.php?id_kriteria=<?= $a['id_kriteria']; ?>">Edit</a>
                    <a class="dropdown-item" href="kriteria-proses.php?id_kriteria=<?php echo $a['id_kriteria']; ?>&proses=hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
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
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah kriteria</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="kriteria-proses.php?proses=simpan" method="post">
            <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Nama</label>
          <input type="text" class="form-control" name="nama" id="nama" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Bobot</label>
          <input type="number" class="form-control" name="bobot" id="bobot" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
          <label for="exampleInputEmail1" class="form-label">Atribut</label>
            <select class="form-select" aria-label="Default select example" name="atribut" id="atribut">
                <option selected></option>
                <option value="benefit">benefit</option>
                <option value="cost">cost</option>
                </select>
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