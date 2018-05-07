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
<center><h5>LAPORAN PENJUALAN PER-HARI<br>
			<small>TANGGAL : <b><?php echo date('d-m-Y', strtotime($tgl)); ?></b></small></h5></center>

<?php
$penjualan_tunai = 0;
$penjualan_hutang = 0;
$total_penjualan =0;

$trsan = mysql_query("SELECT * FROM tb_penjualan WHERE tgl_transaksi = '$tgl'") or die(mysql_error());
$no = 1;
while ($t = mysql_fetch_assoc($trsan)) {
	echo"<table class='w3-table w3-tiny'>
		<thead>
			<tr class='w3-light-grey'>
				<th width='80px' rowspan='3'>NO : $no</th>
				<th width='20%'>NO. TRANSAKSI</th>
				<th><span class='w3-text-blue'>: $t[no_transaksi]</span></th>
				<th rowspan='3'>STATUS : $t[status]</th>
			</tr>
			<tr class='w3-light-grey'>
				<th>NAMA PELANGGAN</th>
				<th><span class='w3-text-blue'>: $t[nama_pelanggan]</span></th>
			</tr>
			<tr class='w3-light-grey'>
				<th>TANGGAL TRANSAKSI</th>
				<th><span class='w3-text-blue'>: ".tglindo($t['tgl_transaksi'])."</span></th>
				
			</tr>

		</thead>
		<tbody>
		<tr>
			<td colspan='4' class='w3-padding-0'>

			<table class='w3-table w3-tiny'>
				<tr>
					<td>Kode Barang</td>
					<td>Nama Barang</td>
					<td>Qty</td>
					<td>Harga Jual</td>
					<td>Disc.</td>
					<td>Total</td>
				</tr>";
				$tbarang = mysql_query("SELECT a.*, b.nama_barang FROM tb_detail_penjualan a 
								LEFT JOIN tb_barang b 
								ON a.kode_barang = b.kode_barang 
								WHERE a.no_transaksi = '$t[no_transaksi]'") or die(mysql_error());

				$sub_total = 0;
				$total_harga = 0;

				while ($b = mysql_fetch_assoc($tbarang)) {
					$harga_disc = $b['harga'] - (($b['harga'] * $b['disc']) / 100);
					$sub_total = $harga_disc * $b['qty'];
					$total_harga = $total_harga + $sub_total;

					echo"<tr>
						<td width='220px'>$b[kode_barang]</td>
						<td width='30%'>$b[nama_barang]</td>
						<td width='40'>$b[qty]</td>
						<td>Rp. ".number_format($b['harga'])."</td>
						<td>".number_format($b['disc'])."%</td>
						<td>Rp. ".number_format($sub_total)."</td>
					</tr>";
				}


				$total_bayar = $total_harga - $t['potongan'];
				$sisa = $t['bayar'] - $total_bayar;

				if ($t['status'] == "LUNAS") {
					$penjualan_tunai += $total_bayar;
				}
				elseif ($t['status'] == "HUTANG") {
					$penjualan_hutang += $total_bayar;
				}
		echo"</table>


			</td>
		</tr>

		</tbody>
		<tfoot>
		<tr class='w3-light-grey'>
			<th colspan='3'><span class='w3-right'>Total Harga :</span></th>
			<th>Rp. ".number_format($total_harga)."</th>
		</tr>
		<tr class='w3-light-grey'>
			<th colspan='3'><span class='w3-right'>Potongan Harga :</span></th>
			<th>Rp. ".number_format($t['potongan'])."</th>
		</tr>
		<tr class='w3-light-grey'>
			<th colspan='3'><span class='w3-right'>Total Bayar :</span></th>
			<th>Rp. ".number_format($total_bayar)."</th>
		</tr>
		</tfoot>
	</table><br>";

	$no++;
}
$total_penjualan = $penjualan_tunai + $penjualan_hutang;
?>
<div class="w3-row-padding w3-border">
	<div class="w3-col s8">PENJUALAN LUNAS</div>
	<div class="w3-col s4">Rp. <?php echo number_format($penjualan_tunai); ?></div>
</div>
<div class="w3-row-padding w3-border">
	<div class="w3-col s8">PENJUALAN HUTANG</div>
	<div class="w3-col s4">Rp. <?php echo number_format($penjualan_hutang); ?></div>
</div>
<div class="w3-row-padding w3-border">
	<div class="w3-col s8">TOTAL</div>
	<div class="w3-col s4">Rp. <?php echo number_format($total_penjualan); ?></div>
</div>
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