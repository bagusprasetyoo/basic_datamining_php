<!DOCTYPE html>
<html>

<head>
	<title>KMEANS</title>
	<h2>
		<center>Aplikasi Penentuan Lokasi Posko Covid-19</center>
	</h2>
</head>

<body>
	<table >
		<tr>
			<td><b>NIM  :</b></td>
			<td>2101301114</td>
		</tr>
		<tr>
			<td><b>Nama :</b></td>
			<td>Bagus Prasetyo</td>
		</tr>
		<tr>
			<td><b>Kelas :</b></td>
			<td>3D TI</td>
		</tr>
	</table>
	<br>
	<?php
	include 'koneksi.php';
	echo "Dataset";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Data</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
	$i = 0; 	//inisialisasi indeks array
	$query = mysqli_query($conn, "SELECT * FROM covid");
	$jumdata = mysqli_num_rows($query);	  //mengetahui jumlah baris data
	while ($data = mysqli_fetch_array($query)) {
		$id[$i] = $data['Data'];		//menyimpan setiap nomor data ke dalam array $id
		$lat[$i] = $data['Latitude'];	//menyimpan setiap latitude ke dalam array $lat
		$lng[$i] = $data['Longitude'];	//menyimpan setiap longitude ke dalam array $lng
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$id[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$lat[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$lng[$i]</td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";

	$nilai_K = 3;
	$maxIterasi = 1000;

	//INISIALISASI CENTROID
	//acak indeks dari 0 sampai $jumdata - 1
	$min = 0;
	$max = $jumdata - 1;
	for ($K = 1; $K <= $nilai_K; $K++) {
		if ($K == 1) {
			$acak[$K] = rand($min, $max);
			$pusatLat[$K] = $lat[$acak[$K]];
			$pusatLng[$K] = $lng[$acak[$K]];
		} else {
			//kode berikut untuk memastikan randomnya unique (berbeda)
			$status = "false";
			while ($status == "false") {
				$status = "true";
				for ($a = $K - 1; $a < $K; $a++) {
					$acak[$K] = rand($min, $max);
					if ($acak[$K] == $acak[$a]) {
						$status = "false";
					}
				}
				$pusatLat[$K] = $lat[$acak[$K]];
				$pusatLng[$K] = $lng[$acak[$K]];
			}
		}
	}

	//menampilkan centroid awal tersebut
	echo "<br><br>Centroid Awal";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>K</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
	for ($K = 1; $K <= $nilai_K; $K++) {
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$K</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLat[$K]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLng[$K]</td>";
		echo "</tr>";
	}
	echo "</table>";

	//MENGHITUNG JARAK SETIAP DATA DENGAN SETIAP PUSAT CLUSTER
	for ($i = 0; $i < $jumdata; $i++) {
		for ($K = 1; $K <= $nilai_K; $K++) {
			$jarak[$i][$K] = sqrt(pow($lat[$i] - $pusatLat[$K], 2) + pow($lng[$i] - $pusatLng[$K], 2));
		}
	}

	//MENENTUKAN KEANGGOTAAN SETIAP DATASET BERDASARKAN JARAK TERKECIL DENGAN CENTROID/PUSAT CLUSTER
	for ($i = 0; $i < $jumdata; $i++) {
		$terkecil = 1000;	//inisialisasi nilai terkecil dengan nilai yg sangat besar
		for ($K = 1; $K <= $nilai_K; $K++) {
			if ($jarak[$i][$K] < $terkecil) {
				$terkecil = $jarak[$i][$K];
				$cluster[$i][0] = $K;
			}
		}
	}

	//menampilkan jarak setiap data dengan keanggotaannya
	echo "<br><br>Jarak dan Keanggotaan Setiap Data";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Data</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
	for ($K = 1; $K <= $nilai_K; $K++) {
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Jarak K $K</th>";
	}
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Ket</th>";
	for ($i = 0; $i < $jumdata; $i++) {
		echo "<tr>";
		$no = $i + 1;
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$no</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$lat[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$lng[$i]</td>";
		for ($K = 1; $K <= $nilai_K; $K++) {
			$jarak_pusat = $jarak[$i][$K];
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$jarak_pusat</td>";
		}
		$posisi = $cluster[$i][0];
		echo "<td style='padding-left: 10px; padding-right: 10px;'>K $posisi</td>";
		echo "</tr>";
	}
	echo "</table>";

	//ITERASI
	$status = "false";		//inisialisasi status
	$iterasi = 1;			//inisialisasi iterasi, dimulai dari iterasi 1
	while ($status == "false" && $iterasi < $maxIterasi) {

		//1. Update centroid/pusat cluster
		for ($K = 1; $K <= $nilai_K; $K++) {
			$temp_lat = 0;			//inisialisasi untuk sigma latitude
			$temp_lng = 0;			//inisialisasi untuk sigma longitude
			$jumdata_cluster = 0;	//inisialisasi jumlah data yang anggota cluster K

			//cek di setiap data, mana saja yg merupakan anggota cluster K
			for ($i = 0; $i < $jumdata; $i++) {
				if ($cluster[$i][$iterasi - 1] == $K) {
					$temp_lat += $lat[$i];	//jika iya, maka jumlahkan latitudenya
					$temp_lng += $lng[$i];	//jika iya, maka jumlahkan longitudenya
					$jumdata_cluster += 1;	//jika iya, maka jumlah data cluster ditambahkan			
				}
			}
			//kemudian hitung rata-ratanya, sebagai pusat cluster baru
			$pusatLat[$K] = $temp_lat / $jumdata_cluster;
			$pusatLng[$K] = $temp_lng / $jumdata_cluster;
		}

		//tampilkan pusat cluster baru dalam bentuk tabel
		echo "<br><br><br>ITERASI $iterasi";
		echo "<br>Update Pusat Cluster";
		echo "<br>";
		echo "<table border='1' style='border-collapse: collapse;'>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>K</th>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
		for ($K = 1; $K <= $nilai_K; $K++) {
			echo "<tr>";
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$K</td>";
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLat[$K]</td>";
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLng[$K]</td>";
			echo "</tr>";
		}
		echo "</table>";


		//2. Menghitung jarak setiap dataset dengan pusat cluster baru
		for ($i = 0; $i < $jumdata; $i++) {
			for ($K = 1; $K <= $nilai_K; $K++) {
				$jarak[$i][$K] = sqrt(pow($lat[$i] - $pusatLat[$K], 2) + pow($lng[$i] - $pusatLng[$K], 2));
			}
		}


		//3. Menentukan keanggotaan setiap dataset berdasarkan jarak terkecil dengan centroid/pusat cluster baru
		for ($i = 0; $i < $jumdata; $i++) {
			$terkecil = 1000;	//inisialisasi nilai terkecil dengan nilai yg sangat besar
			for ($K = 1; $K <= $nilai_K; $K++) {
				if ($jarak[$i][$K] < $terkecil) {
					$terkecil = $jarak[$i][$K];
					$cluster[$i][$iterasi] = $K;	//indeks cluster di iterasi sekarang
				}
			}
		}

		//tampilkan jarak dan keanggotaan dalam bentuk tabel
		echo "<br><br>Jarak dan Keanggotaan Setiap Data";
		echo "<br>";
		echo "<table border='1' style='border-collapse: collapse;'>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Data</th>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
		for ($K = 1; $K <= $nilai_K; $K++) {
			echo "<th style='padding-left: 10px; padding-right: 10px;'>Jarak K $K</th>";
		}
		echo "<th style='padding-left: 10px; padding-right: 10px;'>Ket</th>";
		for ($i = 0; $i < $jumdata; $i++) {
			echo "<tr>";
			$no = $i + 1;
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$no</td>";
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$lat[$i]</td>";
			echo "<td style='padding-left: 10px; padding-right: 10px;'>$lng[$i]</td>";
			for ($K = 1; $K <= $nilai_K; $K++) {
				$jarak_pusat = $jarak[$i][$K];
				echo "<td style='padding-left: 10px; padding-right: 10px;'>$jarak_pusat</td>";
			}
			$posisi = $cluster[$i][$iterasi];	//keanggotaan data pada iterasi terkini
			echo "<td style='padding-left: 10px; padding-right: 10px;'>K $posisi</td>";
			echo "</tr>";
		}
		echo "</table>";


		//4. Cek kondisi berhenti dengan membandingkan keanggotaan setiap data pada iterasi sekarang dengan iterasi sebelumnya
		$status = "true";	//inisialisasi nilai status
		for ($i = 0; $i < $jumdata; $i++) {
			//dicek, jika ternyata keanggotaan dgn iterasi sebelumnya ada yg sama, maka status langsung menjadi false
			if ($cluster[$i][$iterasi - 1] != $cluster[$i][$iterasi]) {
				$status = "false";
			}
		}

		//menambahkan nilai iterasi scr increment
		$iterasi++;
	}


	//MENAMPILKAN HASIL BERUPA CENTROID
	echo "<br><br><br>HASIL AKHIR, LOKASI POSKO COVID-19";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>K</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Latitude</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Longitude</th>";
	for ($K = 1; $K <= $nilai_K; $K++) {
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$K</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLat[$K]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$pusatLng[$K]</td>";
		echo "</tr>";
	}
	echo "</table>";
	?>
</body>

</html>