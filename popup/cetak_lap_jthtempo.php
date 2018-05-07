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
<center><h5>REKAP PELANGGAN SUDAH JATUH TEMPO TANGGAL : 
<b><?php echo tglindo($tgl); ?></b></h5></center>

<?php
	echo"<table class='w3-table w3-tiny w3-bordered tbl' cellpadding='0'>
		<thead>
		<tr class='w3-dark-grey'>
			<th>NO</th>
			<th>NO STRUK</th>
			<th>KODE PELANGGAN</th>
			<th>NAMA PELANGGAN</th>
			<th>TGL. TRANSAKSI</th>
			<th>STATUS</th>
		</tr>
	</thead>
	<tbody>";


	$sql = "SELECT * FROM tb_penjualan 
	WHERE tgl_tempo = date('$tgl') 
	ORDER BY nama_pelanggan ASC";
	$result = mysql_query($sql) or die(mysql_error());

	$no = 1;
	while ($m = mysql_fetch_assoc($result)) {
		echo"<tr>
			<td>$no</td>
			<td>$m[no_struk]</td>
			<td>$m[kode_pelanggan]</td>
			<td>$m[nama_pelanggan]</td>
			<td>".tglindo($m['tgl_transaksi'])."</td>
			<td>$m[status]</td>
		</tr>";
		$no++;
	}

		echo"</tbody>
	</table></div>";
		

?>