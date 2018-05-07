<?php
	date_default_timezone_set("Asia/Jakarta");

	$tgl = trim($_POST['tgl']);
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
<center><h5>LAPORAN PEMBAYARAN HUTANG PENJUALAN PER-HARI<br>
			<small>TANGGAL : <b><?php echo date('d-m-Y', strtotime($tgl)); ?></b></small></h5></center>


<?php
	echo"<table class='w3-table w3-tiny w3-bordered tbl' cellpadding='0'>
		<thead>
		<tr class='w3-dark-grey'>
			<th>NO</th>
			<th>TGL. BAYAR</th>
			<th>KODE PELANGGAN</th>
			<th>NAMA PELANGGAN</th>
			<th>PETUGAS</th>
			<th>NAMA PENYETOR</th>
			<th>JUMLAH</th>
		</tr>
	</thead>
	<tbody>";


		$sql = mysql_query("SELECT a.*, b.nama_pelanggan FROM tb_bayar_hutang a 
						LEFT JOIN tb_pelanggan b ON a.kode_pelanggan = b.kode_pelanggan 
						WHERE a.tgl_bayar = '$tgl' 
						ORDER BY a.id_bayar DESC") or die(mysql_error());

		$total = 0;
		$no = 1;
		while ($m = mysql_fetch_assoc($sql)) {
			$total = $total + $m['jumlah'];

			echo"<tr>
				<td>$no</td>
				<td>$m[tgl_bayar]</td>
				<td>$m[kode_pelanggan]</td>
				<td>$m[nama_pelanggan]</td>
				<td>".nama_petugas($m['petugas'])."</td>
				<td>$m[nama_penyetor]</td>
				<td>Rp. ".number_format($m['jumlah'])."</td>
			</tr>";
			$no++;
		}

		echo"<tr>
			<td colspan='6'><b class='w3-right'>Total :</b></td>
			<td>Rp. ".number_format($total)."</td>
		</tr>";


		echo"</tbody>
	</table></div>";


?>
