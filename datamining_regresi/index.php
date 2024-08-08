<!DOCTYPE html>
<html>

<head>
	<title>forecasting</title>
	<h2>Peramalan Penjualan Mobil Honda Menggunakan Regresi Linear</h2>
</head>

<body>
	<table>
		<tr>
			<td><b>NIM :</b></td>
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
	include "koneksi.php";
	//Mendapatkan data x dan menyimpannya dalam array
	echo "Data Penjualan Mobil";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Tahun</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Penjualan</th>";
	$i = 0;
	$query = mysqli_query($conn, "SELECT * FROM penjualan");
	$jumdata = mysqli_num_rows($query);
	while ($data = mysqli_fetch_array($query)) {
		$tahun[$i] = $data['tahun'];
		$X[$i] = $data['penjualan'];
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$tahun[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$X[$i]</td>";
		echo "</tr>";
		$i++;
	}
	echo "</table>";

	//Mendapatkan nilai target
	$n = $jumdata;
	for ($i = 0; $i < $n - 1; $i++) { 	//$n - 1 karena di tahun 2017 tidak punya target
		$Y_target[$i] = $X[$i + 1];
	}

	//Menghitung XY dan X_kuadrat di setiap indeks array
	//Menghitung sigma_X, sigma_Y, sigma_XY, sigma_Xkuadrat dan kuadrat_sigmaX
	$sigma_X = 0;
	$sigma_Y = 0;
	$sigma_XY = 0;
	$sigma_Xkuadrat = 0;
	for ($i = 0; $i < $n - 1; $i++) {
		$XY[$i] = $X[$i] * $Y_target[$i];
		$X_kuadrat[$i] = pow($X[$i], 2);

		$sigma_X += $X[$i];
		$sigma_Y += $Y_target[$i];
		$sigma_XY += $XY[$i];
		$sigma_Xkuadrat += $X_kuadrat[$i];
	}
	$kuadrat_sigmaX = pow($sigma_X, 2);

	//Menghitung konstanta a
	$atas = ($sigma_Y * $sigma_Xkuadrat) - ($sigma_X * $sigma_XY);
	$bawah = ($n * $sigma_Xkuadrat) - $kuadrat_sigmaX;
	$a = $atas / $bawah;

	//Menghitung konstanta b
	$atas = ($n * $sigma_XY) - ($sigma_X * $sigma_Y);
	$bawah = ($n * $sigma_Xkuadrat) - $kuadrat_sigmaX;
	$b = $atas / $bawah;

	//Menghitung prediksi penjualan mobil tahun terakhir
	//Mengambil informasi tahun terakhir dari dataset
	$query = mysqli_query($conn, "SELECT MAX(tahun) AS tahunmax FROM penjualan");
	$data = mysqli_fetch_array($query);
	$tahunmax = $data['tahunmax'];
	$tahundepan = $tahunmax + 1;

	//Mengambil informasi nilai penjualan terakhir, yaitu pada tahunmax
	$query = mysqli_query($conn, "SELECT penjualan FROM penjualan WHERE tahun = $tahunmax");
	$data = mysqli_fetch_array($query);
	$X_sekarang = $data['penjualan'];

	//Memprediksi penjualan di tahun depan
	$Y = $a + ($b * $X_sekarang);
	$Y = round($Y); //karena jumlah penjualan tidak mungkin decimal

	//MENGHITUNG MAPE
	//Menghitung prediksi penjualan di setiap data pada dataset
	for ($i = 1; $i < $n; $i++) {
		$Y_output[$i] = $a + ($b * $X[$i - 1]);
		$Y_output[$i] = round($Y_output[$i]); //karena jumlah penjualan tidak mungkin decimal
	}

	//menggunakan variabel temporer (sementara) untuk menghitung sigma pada MAPE
	$temp = 0;
	for ($i = 1; $i < $n; $i++) {
		$temp += abs(($X[$i] - $Y_output[$i]) / $X[$i]);
		//abs (absolute) adalah function untuk mengkonversi bilangan ke nilai mutlak
	}
	$MAPE = (1 / ($n - 1)) * $temp * 100;

	//Menampilkan hasil perhitungan dalam tabel
	echo "<br><br>Tabel Perhitungan";
	echo "<br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Tahun</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>X</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Y (target)</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>X kuadrat</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>XY</th>";
	for ($i = 0; $i < $n - 1; $i++) {
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$tahun[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$X[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$Y_target[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$X_kuadrat[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$XY[$i]</td>";
		echo "</tr>";
	}
	echo "<tr>";
	echo "<td style='padding-left: 10px; padding-right: 10px;'>TOTAL</td>";
	echo "<td style='padding-left: 10px; padding-right: 10px;'>$sigma_X</td>";
	echo "<td style='padding-left: 10px; padding-right: 10px;'>$sigma_Y</td>";
	echo "<td style='padding-left: 10px; padding-right: 10px;'>$sigma_Xkuadrat</td>";
	echo "<td style='padding-left: 10px; padding-right: 10px;'>$sigma_XY</td>";
	echo "</tr>";
	echo "</table>";
	echo "Kuadrat dari Sigma_X : $kuadrat_sigmaX";
	echo "<br>Konstanta a : $a";
	echo "<br>Konstanta b : $b";
	echo "<br><br>Prediksi penjualan untuk tahun $tahundepan sebanyak $Y unit";
	echo "<br>";
	echo "<br>Perhitungan MAPE</br>";
	echo "<table border='1' style='border-collapse: collapse;'>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Tahun</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Y (Aktual)</th>";
	echo "<th style='padding-left: 10px; padding-right: 10px;'>Y (Prediksi)</th>";
	for ($i = 1; $i < $n; $i++) {
		echo "<tr>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$tahun[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$X[$i]</td>";
		echo "<td style='padding-left: 10px; padding-right: 10px;'>$Y_output[$i]</td>";
		echo "</tr>";
	}
	echo "</table>";
	echo "<br><br> MAPE : $MAPE %";
	?>
</body>

</html>