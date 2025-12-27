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
                <h3>Nilai Preferensi</h3>
                <div class="row">
            <div class="col-sm-6">
                <div class="card" style="height:30rem">
                <div class="card-body">
                    <h5 class="card-title">Simple Additive Weighting (SAW)</h5>
                    <p class="card-text">Nilai Preferensi (Vi)</p>
                       <!-- tabel  -->
        <?php
                // Mulai hitung waktu sebelum normalisasi
                $start_time = microtime(true);
                ?>
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-3">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">No</th>
                                <th scope="col">Nama Alternatif</th>
                                <th scope="col" class="text-center">Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $alternatif_query = mysqli_query($koneksi, "SELECT * FROM tl_alternatif ORDER BY id_alter");
                            $no = 1;

                            while ($alternatif = mysqli_fetch_array($alternatif_query)) {
                                $sawhslkhr = 0; // Inisialisasi hasil akhir untuk setiap alternatif
                                $id_alter = $alternatif['id_alter'];
                                $nama_alter = $alternatif['nama_alter'];
                            ?>
                                <tr>
                                    <!-- Kolom Nomor -->
                                    <td scope="col" class="text-center"><?php echo $no++; ?></td>

                                    <!-- Kolom Nama Alternatif -->
                                    <td scope="row"><?php echo htmlspecialchars($nama_alter); ?></td>

                                    <!-- Kolom Hasil -->
                                    <?php
                                    // Query untuk mendapatkan nilai subkriteria berdasarkan alternatif
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

                                    while ($data_nilai = mysqli_fetch_array($query_nilai)) {
                                        $nli = $data_nilai['nli']; // Nilai subkriteria
                                        $id_kriteria = $data_nilai['id_kriteria'];
                                        $atribut = $data_nilai['atribut']; // Atribut ('benefit' atau 'cost')
                                        $bobot = $data_nilai['bobot'] / 100; // Mengonversi bobot ke desimal

                                        // Mendapatkan nilai maksimum dan minimum untuk setiap kriteria
                                        $max_query = mysqli_query($koneksi, "
                                            SELECT MAX(s.nilai_subkriteria) AS nmax
                                            FROM tl_subkriteria s
                                            INNER JOIN tl_nilai kp ON s.id_subkriteria = kp.id_subkriteria
                                            WHERE kp.id_kriteria = '$id_kriteria'
                                        ");
                                        $max_data = mysqli_fetch_assoc($max_query);
                                        $tmax = $max_data['nmax'];

                                        $min_query = mysqli_query($koneksi, "
                                             SELECT MIN(s.nilai_subkriteria) AS nmin
                                            FROM tl_subkriteria s
                                            INNER JOIN tl_nilai kp ON s.id_subkriteria = kp.id_subkriteria
                                            WHERE kp.id_kriteria = '$id_kriteria'
                                        ");
                                        $min_data = mysqli_fetch_assoc($min_query);
                                        $tmin = $min_data['nmin'];

                                        // Menghitung nilai terbobot berdasarkan atribut
                                        if ($atribut == 'benefit') {
                                            $hsl = $nli / $tmax;
                                        } else {
                                            $hsl = $tmin / $nli;
                                        }

                                        // Mengalikan dengan bobot
                                        $hslbbt = $hsl * $bobot;
                                        $sawhslkhr += $hslbbt;
                                    }

                                    // Format hasil akhir
                                    $hslr = number_format($sawhslkhr, 2);
                                    echo "<td scope='col' class='text-center'>$hslr</td>";
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

                </div>
            </div>
        </div>
        <!-- Topsis -->
         <?php
        // Mulai hitung waktu sebelum normalisasi
        $start_time = microtime(true);
        ?>
            <div class="col-sm-6">
                <div class="card" style="height:30rem">
                <div class="card-body">
                    <h5 class="card-title">Technique for Order Preference by Similarity to Ideal Solution (TOPSIS)</h5>
                    <p class="card-text">Nilai Preferensi (Vi)</p>

                      <?php
                // Mulai hitung waktu sebelum normalisasi
                $start_time = microtime(true);
                ?>
               <?php
                    // Ambil semua data kriteria terlebih dahulu
                    $kriteria_data = [];
                    $kriteria_query = mysqli_query($koneksi, "SELECT id_kriteria, tps_akar, bobot, atribut FROM tl_kriteria");
                    while ($row = mysqli_fetch_assoc($kriteria_query)) {
                        $kriteria_data[$row['id_kriteria']] = $row;
                    }

                    // Ambil semua data nilai alternatif terhadap kriteria
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
                        $max_min_values[$id_kriteria] = [
                            'max' => max(array_column($nilai_data, $id_kriteria)),
                            'min' => min(array_column($nilai_data, $id_kriteria))
                        ];
                    }
                    ?>

<div class="table-responsive">
    <table class="table table-striped table-hover mb-3">
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th scope="col" class="text-start">Alternatif</th>
                <th scope="col">Hasil</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $c = mysqli_query($koneksi, "SELECT * FROM tl_alternatif ORDER BY id_alter");
            $no=1;
            while ($a = mysqli_fetch_array($c)) {
                $id_alter = $a['id_alter'];
                $nama_alter = $a['nama_alter'];

                // Inisialisasi variabel untuk DI+ dan DI-
                $dphsl_plus = 0; // Untuk DI+
                $dphsl_minus = 0; // Untuk DI-

                foreach ($kriteria_data as $id_kriteria => $kriteria) {
                    $nli = $nilai_data[$id_alter][$id_kriteria] ?? 0; // Nilai alternatif terhadap kriteria

                    // Ideal positif (DI+)
                    $ideal_positif = ($kriteria['atribut'] === 'benefit') 
                        ? $max_min_values[$id_kriteria]['max'] 
                        : $max_min_values[$id_kriteria]['min'];
                    $dpls1_plus = $nli - $ideal_positif;
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

                // Hitung preferensi TOPSIS
                $preferensi = ($dpakr_plus + $dpakr_minus > 0) 
                    ? round($dpakr_minus / ($dpakr_minus + $dpakr_plus), 4) 
                    : 0;

                echo "<tr>";
                 echo "<td class='text-center'>" . $no++ . "</td>";
                echo "<td>" . $nama_alter . "</td>";
                echo "<td>" . number_format($preferensi,2) . "</td>"; // Preferensi
                echo "</tr>";
            }
            ?>
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
                    
                </div>
                </div>
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