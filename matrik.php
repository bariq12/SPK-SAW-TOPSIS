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
            <h3>Matriks</h3>
        <div class="card" >
  <div class="card-body">
    <h5 class="card-title mb-3">Matrik Keputusan</h5>
   <button type="button" class="btn btn-outline-info mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
    Isi Nilai Alternatif
</button>
<!-- Tabel -->
<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th scope="col" colspan="1" class="text-start">Alternatif</th>
                <th scope="col" colspan="6">Kriteria</th>
            </tr>
            <tr>
                <th scope="row"></th> <!-- Kosong untuk Alternatif -->
                <?php
                // Ambil data kriteria
                $kriteria_query = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                if (!$kriteria_query || mysqli_num_rows($kriteria_query) == 0) {
                    echo "<td colspan='7' class='text-center'>Tidak ada data kriteria tersedia.</td>";
                } else {
                    $no = 0;
                    while ($kriteria = mysqli_fetch_assoc($kriteria_query)) {
                        $no++;
                        echo "<th scope='col' >C{$no}</th>";
                    }
                }
                ?>
                <th scope="col">Opsi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Ambil data alternatif
            $alternatif_query = mysqli_query($koneksi, "SELECT * FROM tl_alternatif ORDER BY id_alter");
            if (!$alternatif_query || mysqli_num_rows($alternatif_query) == 0) {
                echo "<tr><td colspan='8' class='text-center'>Tidak ada data alternatif tersedia.</td></tr>";
            } else {
                while ($alternatif = mysqli_fetch_assoc($alternatif_query)) {
                    $id_alter = $alternatif['id_alter'];
                    $nama_alter = htmlspecialchars($alternatif['nama_alter']);
                    echo "<tr>";
                    echo "<td scope='row'>$nama_alter</td>";

                    // Ambil nilai untuk alternatif ini
                    $nilai_query = mysqli_query($koneksi, "
                        SELECT b.nilai_subkriteria AS nli 
                        FROM tl_nilai a, tl_subkriteria b
                        WHERE a.id_alter = '$id_alter' AND b.id_subkriteria=a.id_subkriteria
                        ORDER BY a.id_kriteria
                    ");
                    $nilai_array = [];
                    while ($nilai = mysqli_fetch_assoc($nilai_query)) {
                        $nli = $nilai['nli'];
                        $nilai_array[] = $nli;
                        echo "<td >$nli</td>";
                    }

                    // Jika tidak ada nilai, tampilkan placeholder
                    $kriteria_count = mysqli_num_rows($kriteria_query);
                    for ($i = count($nilai_array); $i < $kriteria_count; $i++) {
                        echo "<td >-</td>";
                    }

                    // Cek apakah semua nilai kosong atau nol
                    $all_empty = array_filter($nilai_array, function ($v) {
                        return !empty($v) && $v != "0";
                    }) ? false : true;
                   // Tombol Aksi
                    echo "<td>";
                    if (!$all_empty) {
                        echo "
                        <a href='matrik-proses.php?id_alter=$id_alter&proses=hapus' 
                        class='btn btn-danger btn-sm' 
                        onclick='return confirm(\"Yakin ingin menghapus?\")'>
                        Hapus
                        </a>
                        ";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
            ?>
        </tbody>
        <tr>
            <th>Atribut</th>
           <?php
                $datakr = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                while ($akr = mysqli_fetch_array($datakr)) { 
                    echo "<th >".$akr['atribut']."</th>";
                }
                ?>
        </tr>
        <tr>
            <th>Bobot WIJ</th>
            <?php
                $datakr = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                while ($akr = mysqli_fetch_array($datakr)) { 
                     $bobot_nm = $akr['bobot']/100;
                    echo "<th >".number_format($bobot_nm,2)."</th>";
                }
                echo "<th scope='row'></th>"
                ?>
        </tr>
    </table>
</div>
  <!-- matrik normalisasi SAW dan TOPSIS -->  
     <nav>
  <div class="nav nav-tabs justify-content-center sticky-top mt-3" id="nav-tab" role="tablist">
    <button class="nav-link active sticky-top" id="nav-home-tab" data-bs-toggle="tab" data-bs-target="#nav-saw" type="button" role="tab" aria-controls="nav-home" aria-selected="true">SAW</button>
    <button class="nav-link sticky-top" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-topsis" type="button" role="tab" aria-controls="nav-profile" aria-selected="false">TOPSIS</button>
  </div>
</nav>
<!-- SAW -->
<div class="tab-content " id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-saw" role="tabpanel" aria-labelledby="nav-home-tab" tabindex="0">
 <!-- SAW -->
    <h6 class="mt-2">Matrik Normalisasi RIJ</h6>

   <?php
// Mulai hitung waktu sebelum normalisasi
$start_time = microtime(true);
?>
<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th scope="col">Nama Alternatif</th>
                <th scope="col" colspan="4">Kriteria</th>
            </tr>
            <tr>
                 <th scope="row"></th> <!-- Kosong untuk Alternatif -->
                <?php
                $data = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                $no = 0;
                while ($a = mysqli_fetch_array($data)) { 
                    $no++; 
                ?>
                    <th scope="col">C<?php echo $no; ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            $alternatif_query = mysqli_query($koneksi, "SELECT * FROM tl_alternatif ORDER BY id_alter");
            if (!$alternatif_query) {
                die("Error fetching alternatif: " . mysqli_error($koneksi));
            }

            $no = 1;

            while ($alternatif = mysqli_fetch_array($alternatif_query)) {
                $hsl = 0; // Inisialisasi hasil akhir untuk setiap alternatif
                $id_alter = $alternatif['id_alter'];
                $nama_alter = $alternatif['nama_alter'];
            ?>
                <tr>
                    <td scope="row"><?php echo htmlspecialchars($nama_alter); ?></td>

                    <!-- Kolom Hasil -->
                    <?php
                  $query_nilai = mysqli_query($koneksi, "
                    SELECT 
                        s.nilai_subkriteria AS nli, 
                        kp.id_kriteria AS id_kriteria, 
                        k.atribut AS atribut, 
                        k.bobot AS bobot
                    FROM 
                        tl_subkriteria s
                    INNER JOIN 
                        tl_nilai kp ON s.id_subkriteria = kp.id_subkriteria
                    INNER JOIN 
                        tl_kriteria k ON kp.id_kriteria = k.id_kriteria
                    WHERE 
                        kp.id_alter = '$id_alter'
                    ORDER BY 
                        kp.id_kriteria
                ");

                if (!$query_nilai) {
                    die("Error fetching nilai: " . mysqli_error($koneksi));
                }

                while ($data_nilai = mysqli_fetch_array($query_nilai)) {
                    $nli = $data_nilai['nli']; // Nilai subkriteria
                    $id_kriteria = $data_nilai['id_kriteria'];
                    $atribut = $data_nilai['atribut']; // Atribut ('benefit' atau 'cost')
                    // Mendapatkan nilai maksimum untuk kriteria tertentu
                    $max_query = mysqli_query($koneksi, "
                        SELECT MAX(s.nilai_subkriteria) AS nmax
                        FROM tl_subkriteria s
                        INNER JOIN tl_nilai kp ON s.id_subkriteria = kp.id_subkriteria
                        WHERE kp.id_kriteria = '$id_kriteria'
                    ");

                    if (!$max_query) {
                        die("Error fetching max value: " . mysqli_error($koneksi));
                    }

                    $max_data = mysqli_fetch_array($max_query);
                    $tmax = $max_data['nmax'];

                    // Mendapatkan nilai minimum untuk kriteria tertentu
                    $min_query = mysqli_query($koneksi, "
                        SELECT MIN(s.nilai_subkriteria) AS nmin
                        FROM tl_subkriteria s
                        INNER JOIN tl_nilai kp ON s.id_subkriteria = kp.id_subkriteria
                        WHERE kp.id_kriteria = '$id_kriteria'
                    ");

                    if (!$min_query) {
                        die("Error fetching min value: " . mysqli_error($koneksi));
                    }

                    $min_data = mysqli_fetch_array($min_query);
                    $tmin = $min_data['nmin'];

                    // Menghitung nilai terbobot berdasarkan atribut
                    if ($atribut == 'benefit') {
                        $hsl = $nli / $tmax;
                    } else {
                        $hsl = $tmin / $nli;
                    }

                    $hslr = round($hsl, 2);

                    // Tampilkan nilai $hslr di kolom tabel
                    echo "<td scope='col'>$hslr</td>";
                }
                    ?>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php
// Hitung waktu setelah normalisasi
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
?>

<!-- Menampilkan Waktu Eksekusi -->
<div class="alert alert-info">
    <strong>Waktu Eksekusi:</strong> <?php echo round($execution_time, 2); ?> detik
</div>
<!-- TOPSIS -->
</div>
  <div class="tab-pane fade " id="nav-topsis" role="tabpanel" aria-labelledby="nav-profile-tab" tabindex="0">
    <!-- TOPSIS -->
  <?php
// Mulai hitung waktu sebelum normalisasi
$start_time = microtime(true);

// Ambil semua data kriteria
$kriteria_query = mysqli_query($koneksi, "
    SELECT id_kriteria, atribut, bobot 
    FROM tl_kriteria 
    ORDER BY id_kriteria
");
$kriteria_data = [];
while ($row = mysqli_fetch_assoc($kriteria_query)) {
    $kriteria_data[$row['id_kriteria']] = $row;
}

// Ambil semua data alternatif
$alternatif_query = mysqli_query($koneksi, "
    SELECT id_alter, nama_alter 
    FROM tl_alternatif 
    ORDER BY id_alter
");
$alternatif_data = [];
while ($row = mysqli_fetch_assoc($alternatif_query)) {
    $alternatif_data[$row['id_alter']] = $row;
}

// Ambil semua nilai dari tl_nilai dan gabungkan dengan kriteria dan subkriteria
$nilai_query = mysqli_query($koneksi, "
    SELECT 
        n.id_alter, 
        n.id_kriteria, 
        s.nilai_subkriteria AS nilai, 
        k.tps_akar, 
        k.bobot 
    FROM 
        tl_nilai n
    INNER JOIN 
        tl_subkriteria s ON n.id_subkriteria = s.id_subkriteria
    INNER JOIN 
        tl_kriteria k ON n.id_kriteria = k.id_kriteria
    ORDER BY 
        n.id_alter, n.id_kriteria
");
$nilai_data = [];
while ($row = mysqli_fetch_assoc($nilai_query)) {
    $nilai_data[$row['id_alter']][$row['id_kriteria']] = $row;
}

// Hitung pembagi (tps_akar) untuk setiap kriteria
foreach ($kriteria_data as $id_kriteria => $kriteria) {
    $sum_of_squares = 0;
    foreach ($nilai_data as $id_alter => $kriteria_nilai) {
        if (isset($kriteria_nilai[$id_kriteria])) {
            $sum_of_squares += pow($kriteria_nilai[$id_kriteria]['nilai'], 2);
        }
    }
    $tps_akar = sqrt($sum_of_squares);
    $kriteria_data[$id_kriteria]['tps_akar'] = $tps_akar;

    // Update tps_akar di tabel tl_kriteria
    mysqli_query($koneksi, "
        UPDATE tl_kriteria 
        SET tps_akar = '$tps_akar' 
        WHERE id_kriteria = '$id_kriteria'
    ");
}
?>
<h6 class="mt-3">Matrik Normalisasi RIJ</h6>
<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th>Pembagi</th>
                <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
                    <td><?= number_format($kriteria['tps_akar'], 4) ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <th scope="col" colspan="1" class="text-start">Alternatif</th>
                <th scope="col" colspan="<?= count($kriteria_data) ?>">Kriteria</th>
            </tr>
            <tr>
                <th scope="row"></th> <!-- Kosong untuk Alternatif -->
                <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
                    <th scope="col">C<?= array_search($id_kriteria, array_keys($kriteria_data)) + 1 ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatif_data as $id_alter => $alternatif): ?>
                <tr>
                    <td scope="row"><?= htmlspecialchars($alternatif['nama_alter']) ?></td>
                    <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
                        <?php
                        $nli = $nilai_data[$id_alter][$id_kriteria]['nilai'] ?? 0;
                        $tps_akar = $kriteria['tps_akar'];
                        $nmr_mtr = $nli / $tps_akar;
                        echo "<td>" . number_format($nmr_mtr, 4) . "</td>";
                        ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<h6>Matrik Normalisasi YIJ</h6>
<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th scope="col" colspan="1" class="text-start">Alternatif</th>
                <th scope="col" colspan="<?= count($kriteria_data) ?>">Kriteria</th>
            </tr>
            <tr>
                <th scope="row"></th> <!-- Kosong untuk Alternatif -->
                <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
                    <th scope="col">C<?= array_search($id_kriteria, array_keys($kriteria_data)) + 1 ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatif_data as $id_alter => $alternatif): ?>
                <tr>
                    <td scope="row"><?= htmlspecialchars($alternatif['nama_alter']) ?></td>
                    <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
                        <?php
                        $nli = $nilai_data[$id_alter][$id_kriteria]['nilai'] ?? 0;
                        $tps_akar = $kriteria['tps_akar'];
                        $bobot = $kriteria['bobot'] / 100;
                        $nmr_mtr = $nli / $tps_akar;
                        $nmyij = $bobot * $nmr_mtr;
                        echo "<td>" . number_format($nmyij, 4) . "</td>";

                        // Update norml_tps di tabel tl_nilai
                        mysqli_query($koneksi, "
                            UPDATE tl_nilai 
                            SET norml_tps = '$nmyij' 
                            WHERE id_alter = '$id_alter' AND id_kriteria = '$id_kriteria'
                        ");
                        ?>
                    <?php endforeach; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tr>
            <th>A+/YIJ+</th>
            <?php
            $datapls = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
            while ($apls = mysqli_fetch_array($datapls)) {
                $id_kriteria = $apls['id_kriteria'];
                // Query untuk mendapatkan nilai maksimum
                $yijplsmax = mysqli_query($koneksi, "
                    SELECT MAX(norml_tps) AS maxmormaly 
                    FROM tl_nilai 
                    WHERE id_kriteria = $id_kriteria 
                    ORDER BY id_kriteria
                ");
                $plsmax = mysqli_fetch_array($yijplsmax);
                $plmax = $plsmax['maxmormaly'];

                // Query untuk mendapatkan nilai minimum
                $yijplsmin = mysqli_query($koneksi, "
                    SELECT MIN(norml_tps) AS minmormaly 
                    FROM tl_nilai 
                    WHERE id_kriteria = $id_kriteria 
                    ORDER BY id_kriteria
                ");
                $plsmin = mysqli_fetch_array($yijplsmin);
                $plmin = $plsmin['minmormaly'];

                // Tentukan ideal positif berdasarkan atribut kriteria
                if ($apls['atribut'] == 'benefit') {
                    $hsl = $plmax; // Ideal positif untuk benefit adalah nilai maksimum
                } else {
                    $hsl = $plmin; // Ideal positif untuk cost adalah nilai minimum
                }

                // Format hasil ke 4 digit desimal
                $hsl = round($hsl, 4);
                echo "<th scope='col'>" . $hsl . "</th>";
            }
            ?>
        </tr>
        <tr>
            <th>A-/YIJ-</th>
            <?php
                $datapls = mysqli_query($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
                while ($apls = mysqli_fetch_array($datapls)) {
                    $id_kriteria = $apls['id_kriteria'];
                    // Query untuk mendapatkan nilai maksimum
                    $yijplsmax = mysqli_query($koneksi, "
                        SELECT MAX(norml_tps) AS maxmormaly 
                        FROM tl_nilai 
                        WHERE id_kriteria = $id_kriteria 
                        ORDER BY id_kriteria
                    ");
                    $plsmax = mysqli_fetch_array($yijplsmax);
                    $plmax = $plsmax['maxmormaly'];

                    // Query untuk mendapatkan nilai minimum
                    $yijplsmin = mysqli_query($koneksi, "
                        SELECT MIN(norml_tps) AS minmormaly 
                        FROM tl_nilai 
                        WHERE id_kriteria = $id_kriteria 
                        ORDER BY id_kriteria
                    ");
                    $plsmin = mysqli_fetch_array($yijplsmin);
                    $plmin = $plsmin['minmormaly'];

                    // Tentukan ideal positif berdasarkan atribut kriteria
                    if ($apls['atribut'] == 'cost') {
                        $hsl = $plmax; 
                    } else {
                        $hsl = $plmin; 
                    }

                    // Format hasil ke 4 digit desimal
                    $hsl = round($hsl, 4);
                    echo "<th scope='col'>" . $hsl . "</th>";
                }
                ?>
        </tr>
    </table>
</div>
<?php
// Ambil semua data kriteria terlebih dahulu
$kriteria_data = [];
$kriteria_query = mysqli_query($koneksi, "
    SELECT id_kriteria, tps_akar, bobot, atribut 
    FROM tl_kriteria 
    ORDER BY id_kriteria
");
while ($row = mysqli_fetch_assoc($kriteria_query)) {
    $kriteria_data[$row['id_kriteria']] = $row;
}

// Ambil semua data alternatif
$alternatif_data = [];
$alternatif_query = mysqli_query($koneksi, "
    SELECT id_alter, nama_alter 
    FROM tl_alternatif 
    ORDER BY id_alter
");
while ($row = mysqli_fetch_assoc($alternatif_query)) {
    $alternatif_data[$row['id_alter']] = $row;
}

// Ambil semua nilai dari tl_nilai
$nilai_data = [];
$nilai_query = mysqli_query($koneksi, "
    SELECT id_alter, id_kriteria, norml_tps 
    FROM tl_nilai 
    ORDER BY id_alter, id_kriteria
");
while ($row = mysqli_fetch_assoc($nilai_query)) {
    $nilai_data[$row['id_alter']][$row['id_kriteria']] = $row['norml_tps'];
}

// Hitung nilai maksimum dan minimum untuk setiap kriteria
$max_min_values = [];
foreach ($kriteria_data as $id_kriteria => $kriteria) {
    $values = array_column($nilai_data, $id_kriteria);
    if (!empty($values)) {
        $max_min_values[$id_kriteria] = [
            'max' => max($values),
            'min' => min($values)
        ];
    } else {
        $max_min_values[$id_kriteria] = [
            'max' => 0,
            'min' => 0
        ];
    }
}
?>

<h6>Matrik Ideal Positif dan Ideal Negatif</h6>
<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th scope="col" class="text-start">Alternatif</th>
                <th scope="col">DI+</th>
                <th scope="col">DI-</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alternatif_data as $id_alter => $alternatif): ?>
                <?php
                $dphsl_plus = 0; // Untuk DI+
                $dphsl_minus = 0; // Untuk DI-
                foreach ($kriteria_data as $id_kriteria => $kriteria) {
                    $nli = $nilai_data[$id_alter][$id_kriteria] ?? 0;

                    // Ideal positif (DI+)
                    $ideal_positif = ($kriteria['atribut'] === 'benefit')
                        ? $max_min_values[$id_kriteria]['max']
                        : $max_min_values[$id_kriteria]['min'];
                    $dpls1_plus = $ideal_positif - $nli;
                    $dpkt_plus = pow($dpls1_plus, 2);
                    $dphsl_plus += $dpkt_plus;

                    // Ideal negatif (DI-)
                    $ideal_negatif = ($kriteria['atribut'] === 'benefit')
                        ? $max_min_values[$id_kriteria]['min']
                        : $max_min_values[$id_kriteria]['max'];
                    $dpls1_minus = $nli - $ideal_negatif;
                    $dpkt_minus = pow($dpls1_minus, 2);
                    $dphsl_minus += $dpkt_minus;
                }

                // Hitung akar dari jumlah kuadrat
                $dpakr_plus = sqrt($dphsl_plus); // DI+
                $dpakr_minus = sqrt($dphsl_minus); // DI-
                ?>
                <tr>
                    <td><?= htmlspecialchars($alternatif['nama_alter']) ?></td>
                    <td><?= number_format($dpakr_plus, 4) ?></td> <!-- DI+ -->
                    <td><?= number_format($dpakr_minus, 4) ?></td> <!-- DI- -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php
// Hitung waktu setelah normalisasi
$end_time = microtime(true);
$execution_time = $end_time - $start_time;
?>
<!-- Menampilkan Waktu Eksekusi -->
<div class="alert alert-info">
    <strong>Waktu Eksekusi:</strong> <?= round($execution_time, 2) ?> detik
</div>
</div>
</div>

</div>
  </div>
</div>
    </main>
        <?php require "layout/footer.php";?>
    </div>
</div>
</div>
 <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Tambah Nilai</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <?php
        // Fungsi untuk mengeksekusi query dengan prepared statement
        function executeQuery($koneksi, $query, $params = []) {
            $stmt = $koneksi->prepare($query);
            if ($params) {
                $types = str_repeat('s', count($params));
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            return $stmt->get_result();
        }

        // Ambil data alternatif
        $alternatif_query = executeQuery($koneksi, "SELECT * FROM tl_alternatif ORDER BY id_alter");
        $alternatif_data = [];
        if ($alternatif_query && $alternatif_query->num_rows > 0) {
            while ($row = $alternatif_query->fetch_assoc()) {
                $alternatif_data[] = $row;
            }
        } else {
            echo "<div class='alert alert-warning'>Tidak ada data alternatif tersedia.</div>";
        }

        // Ambil data kriteria
        $kriteria_query = executeQuery($koneksi, "SELECT * FROM tl_kriteria ORDER BY id_kriteria");
        $kriteria_data = [];
        if ($kriteria_query && $kriteria_query->num_rows > 0) {
            while ($row = $kriteria_query->fetch_assoc()) {
                $kriteria_data[$row['id_kriteria']] = $row;
            }
        } else {
            echo "<div class='alert alert-warning'>Tidak ada data kriteria tersedia.</div>";
        }

        // Ambil data subkriteria untuk semua kriteria
        $subkriteria_data = [];
        if (!empty($kriteria_data)) {
            $kriteria_ids = implode(',', array_keys($kriteria_data));
            $subkriteria_query = executeQuery($koneksi, "SELECT * FROM tl_subkriteria WHERE id_kriteria IN ($kriteria_ids) ORDER BY id_kriteria, nilai_subkriteria DESC");
            if ($subkriteria_query && $subkriteria_query->num_rows > 0) {
                while ($row = $subkriteria_query->fetch_assoc()) {
                    $subkriteria_data[$row['id_kriteria']][] = $row;
                }
            }
        }
        ?>

        <!-- Form tambah nilai -->
        <form action="matrik-proses.php?proses=simpan" method="post">
          <div class="mb-3">
            <label for="id_alter" class="form-label">Nama Alternatif</label>
            <select class="form-control" name="id_alter" id="id_alter" required>
              <option value="" disabled selected>Pilih alternatif</option>
              <?php foreach ($alternatif_data as $alternatif): ?>
                <option value="<?= htmlspecialchars($alternatif['id_alter']) ?>"><?= htmlspecialchars($alternatif['nama_alter']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>

          <?php if (!empty($kriteria_data)): ?>
            <?php foreach ($kriteria_data as $id_kriteria => $kriteria): ?>
              <div class="form-group mb-2">
                <label for="nilai_<?= $id_kriteria ?>"><?= htmlspecialchars($kriteria['nama_kriteria']) ?></label>
                <select class="form-control" name="<?= $id_kriteria ?>" required>
                  <option value="" disabled selected>Pilih</option>
                  <?php if (isset($subkriteria_data[$id_kriteria])): ?>
                    <?php foreach ($subkriteria_data[$id_kriteria] as $subkriteria): ?>
                      <option value="<?= htmlspecialchars($subkriteria['id_subkriteria']) ?>">
                        <?= htmlspecialchars($subkriteria['nama_subkriteria']) ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option disabled>Tidak ada subkriteria tersedia</option>
                  <?php endif; ?>
                </select>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="alert alert-danger">Tidak ada kriteria yang dapat diisi.</div>
          <?php endif; ?>
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