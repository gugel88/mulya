<?php
	date_default_timezone_set("Asia/Jakarta");
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
<center><h5>DATA BARANG</h5></center>

<?php
	echo"<table class='w3-table w3-tiny w3-bordered tbl' cellpadding='0'>
		<thead>
		<tr class='w3-dark-grey'>
			<th>NO</th>
			<th>KODE BARANG</th>
			<th>NAMA BARANG</th>
			
			<th>HARGA BELI</th>
			<th>HARGA JUAL</th>
			<th>KATEGORI</th>
		</tr>
	</thead>
	<tbody>";


	$sql = "SELECT * FROM tb_barang ORDER BY nama_barang ASC";
	$result = mysql_query($sql) or die(mysql_error());

	$no = 1;
	while ($m = mysql_fetch_assoc($result)) {
		echo"<tr>
			<td>$no</td>
			<td>$m[kode_barang]</td>
			<td>$m[nama_barang]</td>
			
			<td>Rp. ".number_format($m['harga_beli'])."</td>
			<td>Rp. ".number_format($m['harga_jual'])."</td>
			<td>".nama_kategori($m['kategori_id'])."</td>
		</tr>";
		$no++;
		}

		echo"</tbody>
	</table></div>";
		

?>