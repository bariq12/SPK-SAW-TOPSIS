<?php
require "koneksi.php";

// Cek apakah 'id_subkriteria' dan 'id_kriteria' ada di URL dan tidak kosong
if (isset($_GET['id_subkriteria'], $_GET['id_kriteria']) && !empty($_GET['id_subkriteria']) && !empty($_GET['id_kriteria'])) {
    $id_subkriteria = intval($_GET['id_subkriteria']); // Hindari SQL Injection
    $id_kriteria = intval($_GET['id_kriteria']); // Pastikan id_kriteria valid

    // Ambil data berdasarkan ID
    $sql = "SELECT * FROM tl_subkriteria WHERE id_subkriteria = '$id_subkriteria'";
    $result = $koneksi->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_array();
    } else {
        echo "<script>alert('Data tidak ditemukan!'); window.history.back();</script>";
        exit();
    }
} else {
    echo "<script>alert('ID tidak valid!'); window.history.back();</script>";
    exit();
}
?>

<html lang="en">
<?php include 'layout/header.php'?>
<body>
    <div class="wrapper">
        <?php require "layout/sidebar.php"; ?>
        <div class="main">
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <h3>Edit Subkriteria</h3>
                    <div class="card" style="height:30rem">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <form action="subkriteria-proses.php?proses=edit&id_subkriteria=<?= $id_subkriteria; ?>" method="POST">
                                        <input type="hidden" name="id_subkriteria" value="<?= $id_subkriteria; ?>">
                                        <input type="hidden" name="id_kriteria" value="<?= $id_kriteria; ?>">

                                        <div class="form-group">
                                            <label for="nama_subkriteria">Nama Subkriteria</label>
                                            <input type="text" class="form-control mb-2 mt-2" name="nama_subkriteria" value="<?= htmlspecialchars($row['nama_subkriteria']); ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="nilai_subkriteria">Nilai</label>
                                            <input type="number" class="form-control mb-2 mt-2" name="nilai_subkriteria" value="<?= $row['nilai_subkriteria']; ?>" required>
                                        </div>

                                        <div class="form-group">
                                            <a href="subkriteria.php?id_kriteria=<?= $id_kriteria; ?>" class="btn btn-danger btn-sm">Batal</a>
                                            <input type="submit" class="btn btn-info btn-sm" value="Simpan">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <?php require "layout/footer.php"; ?>
        </div>
    </div>
    <?php require "layout/js.php"; ?>
</body>
</html>
