<?php
	date_default_timezone_set("Asia/Jakarta");
     
    //$tgl1 = trim($_POST['tgl1']);
	//$tgl2 = trim($_POST['tgl2']);

	//link buat paging
	$linkaksi = 'm.php?mod=laporan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}
	
	error_reporting(0);
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
<center><h5>LAPORAN STOK<br>
			<!--<small>TANGGAL DARI : <b><?php echo date('d-m-Y', strtotime($tgl1)); ?></b> s/d <b><?php echo date('d-m-Y', strtotime($tgl2)); ?></b></small></h5></center>-->


<?php

echo"<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable'>
	<thead>
		<tr class='w3-grey'>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan='4'><center>STOK</center></th>
						<th colspan='2'><center>RETUR</center></th>
						<th></th>
						<th></th>
						<th></th>
					</tr>
					<tr class='w3-grey'>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SATUAN</th>
						<th rowspan='2'>KATEGORI</th>
						<th>AWAL</th>
						<th>MASUK</th>
						<th>KELUAR</th>
						<th>TOTAL</th>
						<th>JUAL</th>
						<th>BELI</th>
						<th>SISA</th>
						<th>HARGA BELI</th>
						<th>PERSEDIAAN</th>
					</tr>
	</thead>
	<tbody>";

	

	//$p      = new Paging;
				$batas  = 10;
			    if(isset($_GET['show']) && is_numeric($_GET['show']))
				{
					$batas = (int)$_GET['show'];
					$linkaksi .="&show=$_GET[show]";
				}

				//$posisi = $p->cariPosisi($batas);

				$query = "SELECT * FROM tb_barang ";

				$q 	= "SELECT * FROM tb_barang";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				//$query .= " LIMIT $posisi, $batas";
				$q 	.= " ";
				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);
				
				if($fd_kul > 0)
				{
					$no = $s + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						$stok_masuk = stok_masuk($m['kode_barang']);
						$stok_keluar = stok_keluar($m['kode_barang']);
						//$total
						$total_stok = ($m['jml_stok'] + $stok_masuk) - $stok_keluar;

						$retur_jual = stok_retur_jual($m['kode_barang']);
						$retur_beli = stok_retur_beli($m['kode_barang']);

						$sisa = ($total_stok + $retur_jual) - $retur_beli;
						$sedia = $sisa * $m[harga_beli];

						//$total_bayar1 = total_bayar($m['kode_barang']);
						$gtot = $sedia * stok_masuk($m['kode_barang']);

						$s += $sedia;
						$b += $m[harga_beli];
						$c += $sisa;

						echo"<tr>
							<td>$no</td>
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[satuan]</td>
							<td>".nama_kategori($m['kategori_id'])."</td>
							<td>$m[jml_stok]</td>
							<td>".$stok_masuk."</td>
							<td>".$stok_keluar."</td>
							<td>".$total_stok."</td>
							<td>".$retur_jual."</td>
							<td>".$retur_beli."</td>
							<td>".$sisa."</td>
							<td>Rp ".number_format($m[harga_beli])."</td>
							<td>Rp ".number_format($sedia)."</td>
							
						</tr>";
						$no++;
					}
					
					$jumlah = $tunai + $hutang;
					echo"<tr>
						<td colspan='11'><b class='w3-right'>Jumlah Total :</b></td>
						<td> ".number_format($c)."</td>
						<td>Rp. ".number_format($b)."</td>
						<td>Rp. ".number_format($s)."</td>
					
					
					</tr>";

					$jmldata = mysql_num_rows(mysql_query($q));

					//$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    		//$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman, $linkaksi);
				}
				else
				{
					echo"<tr>
						<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
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