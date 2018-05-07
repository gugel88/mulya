<?php
	date_default_timezone_set("Asia/Jakarta");

	$tgl1 = trim($_POST['tgl1']);
	$tgl2 = trim($_POST['tgl2']);
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
<center><h5>LAPORAN PENJUALAN PER-PERIODE<br>
			<small>TANGGAL DARI : <b><?php echo date('d-m-Y', strtotime($tgl1)); ?></b> s/d <b><?php echo date('d-m-Y', strtotime($tgl2)); ?></b></small></h5></center>


<?php

echo"<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable'>
	<thead>
		<tr class='w3-grey'>
			<th>NO</th>
			<th>NO. TRANSAKSI</th>
			<th>KODE PEL.</th>
			<th>NAMA PELANGGAN</th>
			<th>TGL. TRANSAKSI</th>
			<th>PETUGAS</th>
			<th>STATUS</th>
			<th>TOTAL BAYAR</th>
			<th>POTONGAN</th>
			<th>TOTAL</th>
		</tr>
	</thead>
	<tbody>";

	$query = "SELECT * FROM tb_penjualan 
				WHERE tgl_transaksi >= date('$tgl1') 
				AND tgl_transaksi <= date('$tgl2')";
	
	$sql_kul = mysql_query($query);
	$fd_kul = mysql_num_rows($sql_kul);

	if($fd_kul > 0)
	{
		//$jumlah = 0;
		$tunai = 0;
		$hutang = 0;
		$no = 1;
		while ($m = mysql_fetch_assoc($sql_kul)) {
			$total_bayar = total_penjualan($m['no_transaksi']);

			if($m['status'] == 'LUNAS') {
				$total = $total_bayar - $m['potongan'];
				$tunai = $tunai + $total;
			}
			elseif($m['status'] == 'HUTANG') {
				$total = $total_bayar - $m['potongan'];
				$hutang = $hutang + $total;	
			}

			echo"<tr>
				<td>$no</td>
				<td>$m[no_transaksi]</td>
				<td>$m[kode_pelanggan]</td>
				<td>$m[nama_pelanggan]</td>
				<td>$m[timestmp]</td>
				<td>".nama_petugas($m['petugas'])."</td>
				<td>$m[status]</td>
				<td>Rp. ".number_format($total_bayar)."</td>
				<td>Rp. ".number_format($m['potongan'])."</td>
				<td>Rp. ".number_format($total)."</td>
			
			</tr>";
			$no++;
		}
		$jumlah = $tunai + $hutang;
		echo"<tr>
			<td colspan='9'><b class='w3-right'>Jumlah Tunai/Lunas :</b></td>
			<td>Rp. ".number_format($tunai)."</td>
		</tr>
		<tr>
			<td colspan='9'><b class='w3-right'>Jumlah Hutang :</b></td>
			<td>Rp. ".number_format($hutang)."</b></td>
		</tr>
		<tr>
			<td colspan='9'><b class='w3-right'>Total :</td>
			<td>Rp. ".number_format($jumlah)."</b></td>
		</tr>";

	}
	else
	{
		echo"<tr>
			<td colspan='10'><div class='w3-center'><i>Data Transaksi Not Found.</i></div></td>
		</tr>";
	}
	

	echo"</tbody>

</table>";


?>

<br>
<div class="w3-row-padding w3-tiny">
	<div class="w3-col s4 w3-center">
		<br>
		<p>Karyawan Toko,</p>
		<br>
		<br>

		<p>( _________________________ )</p>
	</div>

	<div class="w3-col s4">
		&nbsp;

	</div>

	<div class="w3-col s4 w3-center">
		<p>Sukabumi, <?php echo tglindo(date('Y-m-d')); ?>
		<br>Hormat Kami,</p>
		<br>
		<br>

		<p>(<u> _________________________ </u>)</p>
	</div>

</div>