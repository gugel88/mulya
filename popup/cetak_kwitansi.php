<?php
	date_default_timezone_set("Asia/Jakarta");
	$sqltrans = mysql_query("SELECT * FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
	$tra = mysql_fetch_assoc($sqltrans);
?>
<h4 class="w3-text-blue" style="padding-bottom:0;margin-bottom:0;"><b><?php echo isset($HEAD['AD_NAMA_USAHA']) ? $HEAD['AD_NAMA_USAHA'] : 'NAMA USAHA ANDA'; ?></b>
<?php echo isset($HEAD['AD_ALAMAT_USAHA']) ? $HEAD['AD_ALAMAT_USAHA'] : 'ALAMAT USAHA ANDA'; ?>
		Telp./HP <?php echo isset($HEAD['AD_NOMOR_HP']) ? $HEAD['AD_NOMOR_HP'] : 'ALAMAT USAHA ANDA'; ?>
</h4>
<!--<div class="w3-medium">
	<div class="w3-col s6 w3-medium"><?php echo isset($HEAD['AD_ALAMAT_USAHA']) ? $HEAD['AD_ALAMAT_USAHA'] : 'ALAMAT USAHA ANDA'; ?><br>
		Telp./HP <?php echo isset($HEAD['AD_NOMOR_HP']) ? $HEAD['AD_NOMOR_HP'] : 'ALAMAT USAHA ANDA'; ?>
	
	</div>
	<div class="w3-col s6 w3-tiny">
		&nbsp;
	</div>
</div>>
<div style="border-bottom:3px solid #ccc;"></div>
<center><h4>KWITANSI PEMBAYARAN</h4>
<center><h4>KWITANSI PEMBELIAN</h4>
<hr />
</center>-->
<?php
	echo"<div class='w5-medium'>
	
	<b>NO : #$tra[no_struk]</b>
	Kepada Yth, 
	$tra[nama_pelanggan] / "?><?php echo !empty($tra['kode_pelanggan']) ? : "-"; ?>
	<center>KWITANSI PEMBELIAN</center>
	<?php echo"</div>
	<div style='height:0px;'></div>";

	echo"<table class='w3-table w3-medium w3-hoverable w3-bordered tbl' cellpadding='0'>
		<thead>
		<tr class='w3-dark-white'>
			<th>NO</th>
			<th>KODE</th>
			<th>BARANG</th>
			<th>HARGA</th>
			<th>BANYAK</th>
			<th colspan='2'>SUB TOTAL</th>
		</tr>
		</thead>

		<tbody>";

	$sql = mysql_query("SELECT a.*, b.nama_barang, b.satuan 
						FROM tb_detail_penjualan a LEFT JOIN tb_barang b 
						ON a.kode_barang = b.kode_barang
						WHERE a.no_transaksi = '$_GET[id]'") or die(mysql_error());
	$sub_total = 0;
	$total = 0;
	$no = 1;
	while($p = mysql_fetch_assoc($sql))
	{
		$harga_disc = $p['harga'] - (($p['harga'] * $p['disc']) / 100);
		$sub_total = $harga_disc * $p['qty'];

		$total = $total + $sub_total;
		echo"<tr>
			<td>$no</td>
			<td>$p[kode_barang]</td>
			<td>$p[nama_barang]</td>
			<td>Rp. ".number_format($p['harga'],0)."</td>
			<td> $p[qty] $p[satuan]</td>
			<td>Rp. ".number_format($sub_total)."</td>
		</tr>";

		$no++;
	}
	$total_bayar = $total - $tra['potongan'];
	$sisa = $tra['bayar'] - $total_bayar;

	echo"</tbody>
		<tfoot>
		<tr class='w3-light-white'>
			<td colspan='5'><b>Total Harga</b></td>
			<td><b>Rp. ".number_format($total)."</b></td>
		</tr>
		<!--<tr class='w3-light-white'>
			<td colspan='5'>Potongan Harga</td>
			<td>Rp. ".number_format($tra['potongan'])."</td>
		</tr>
		<tr class='w3-light-grey'>
			<td colspan='5'><b>Total Bayar</b></td>
			<td><b>Rp. ".number_format($total_bayar)."</b></td>
		</tr>
		<tr class='w3-light-grey'>
			<td colspan='5'><b>Pembayaran</b></td>
			<td><b>Rp. ".number_format($tra['bayar'])."</b></td>
		</tr>
		<tr class='w3-light-grey'>
			<td colspan='5'><b>Kembali</b></td>
			<td><b>Rp. ".number_format($sisa)."</b></td>
		</tr>-->
		</tfoot>
	</table>";

?>

<div class="w3 w3-tiny">
	<div class="w3-col s4 w3-center">
		<br>
		<h3>Pembeli,</h3>
		<br>
		<br>

		<p>( _________________________ )</p>
	</div>

	<div class="w3-col s4">
		&nbsp;

	</div>

	<div class="w3-col s4 w3-center">
		<h4>Sukabumi, <?php echo date('d-m-Y', strtotime($tra['tgl_transaksi'])); ?>
		<br>Hormat Kami,</h4>
		<br>
		

		<p>( _________________________ )</p>
	</div>

</div>