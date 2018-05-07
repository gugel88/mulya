<script src="../js/chart/canvasjs.min.js"></script>
<script src="../js/chart/jquery.canvasjs.min.js"></script>

<?php
	date_default_timezone_set("Asia/Jakarta");

	$tahun = trim($_POST['tahun']);
?>
<h4 class="w3-text-blue" style="padding-bottom:0;margin-bottom:0;"><b><?php echo isset($HEAD['AD_NAMA_USAHA']) ? $HEAD['AD_NAMA_USAHA'] : 'NAMA USAHA ANDA'; ?></b></h4>
<div class="w3-row">
	<div class="w3-col s6 w3-tiny"><?php echo isset($HEAD['AD_ALAMAT_USAHA']) ? $HEAD['AD_ALAMAT_USAHA'] : 'ALAMAT USAHA ANDA'; ?><br>
		Telp./HP <?php echo isset($HEAD['AD_NOMOR_HP']) ? $HEAD['AD_NOMOR_HP'] : 'ALAMAT USAHA ANDA'; ?>
	</div>
	<div class="w3-col s6 w3-tiny">
		&nbsp;
	</div>
</div>
<div style="border-bottom:3px solid #ccc;"></div>
<center><h5>DATA LAPORAN PENJUALAN<br>
			<small>TAHUN : <b><?php echo $tahun; ?></b></small></h5></center>


<?php

echo"<div class='w3-row'>
	<div class='w3-col s12 m6 l4'>
		<table class='w3-table w3-striped w3-bordered w3-tiny'>
			<thead>
				<tr class='w3-grey'>
					<th>BULAN</th>
					<th>TUNAI/CASH</th>
					<th>HUTANG</th>
					<th>TOTAL</th>
				</tr>
			</thead>
			<tbody>";

			for($i=1;$i <= 12; $i++)
			{
				echo"<tr>
					<td>".getBulan($i)."</td>";
						
				$query = "SELECT * FROM tb_penjualan 
							WHERE month(tgl_transaksi) = '$i'
							AND year(tgl_transaksi) = '$tahun' 
							ORDER BY timestmp ASC";
				
				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);

				$jumlah = 0;
				$tunai = 0;
				$hutang = 0;
				while ($m = mysql_fetch_assoc($sql_kul)) {
					if ($m['status'] == 'LUNAS') {
						$total_bayar = total_penjualan($m['no_transaksi']);
						$tot = $total_bayar - $m['potongan'];

						$tunai = $tunai + $tot;
					}
					elseif ($m['status'] == 'HUTANG') {
						$total_bayar = total_penjualan($m['no_transaksi']);
						$tot = $total_bayar - $m['potongan'];

						$hutang = $hutang + $tot;
					}

				}
				$jumlah = $tunai + $hutang;
				echo"<td>Rp. ".number_format($tunai)."</td>";
				echo"<td>Rp. ".number_format($hutang)."</td>";
				echo"<td>Rp. ".number_format($jumlah)."</td>";

				echo"</tr>";
			}

				

			echo"</tbody>

		</table>

	</div>

	<div class='w3-col s12 m6 l8'>
		<div id='chartContainer'></div>
	</div>
</div>";

?>

<script type="text/javascript">
	$.getJSON("../json/grafpjtahunan.php?tahun=<?php echo $tahun; ?>", function (result) {
		chart = new CanvasJS.Chart("chartContainer",
		{
			theme: "theme2",
			animationEnabled: true,
			title:{
				text: "Grafik Penjualan Tahun <?php echo $tahun; ?>"
			},
			exportFileName: "Chart Data Mahasiswa",
			exportEnabled: true,
			legend:{
				verticalAlign: "bottom",
				horizontalAlign: "center"
			},
			data: [
			{       
				type: "line",
				indexLabelFontSize: 20,
				indexLabelFontFamily: "Garamond",
				indexLabelFontColor: "darkgrey",
				indexLabelLineColor: "darkgrey",
				indexLabelPlacement: "outside",
				showInLegend: true,
				toolTipContent: "{y}",
				yValueFormatString: "#0.",
				legendText: "{indexLabel}",
				dataPoints: result
			}
			]
		});
		chart.render();
	});

</script>