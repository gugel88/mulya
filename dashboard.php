<script src="js/chart/canvasjs.min.js"></script>
<script src="js/chart/jquery.canvasjs.min.js"></script>

<?php
	date_default_timezone_set('Asia/Jakarta');

	if(!isset($_SESSION['login_user'])){
		header('location: login.php'); // Mengarahkan ke Home Page
	}

?>

<div class="w3-padding-4 w3-tiny w3-blue"><marquee onmouseover="stop()" onmouseout="start()"><?php echo isset($HEAD['AD_NAMA_USAHA']) ? $HEAD['AD_NAMA_USAHA'] : 'NAMA USAHA ANDA'; ?> - <?php echo isset($HEAD['AD_ALAMAT_USAHA']) ? $HEAD['AD_ALAMAT_USAHA'] : 'ALAMAT USAHA ANDA'; ?></marquee></div>

<div class="w3-row-padding">
	<div class="w3-col s8">
		Dashboard
		<div style="border-bottom:1px dashed #ccc;"></div><br>

		<?php
			$jmlsup = mysql_num_rows(mysql_query("SELECT * FROM tb_supplier"));
			$jmlpel = mysql_num_rows(mysql_query("SELECT * FROM tb_pelanggan"));
			$jmlpj = mysql_num_rows(mysql_query("SELECT * FROM tb_penjualan"));
			$jmlbrg = mysql_num_rows(mysql_query("SELECT * FROM tb_barang"));
		?>

		<div class="w3-row-padding">
			<div class="w3-col s3">
				<div class="w3-card-4 w3-indigo w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-cubes"></i><?php echo $jmlbrg; ?></b></h3>
				<span class="w3-tiny">Data Barang</span></div>
			</div>

			<div class="w3-col s3">
				<div class="w3-card-4 w3-indigo w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-user"></i> <?php echo $jmlsup; ?></b></h3>
				<span class="w3-tiny">Data Supplier</span></div>
			</div>

			<div class="w3-col s3">
				<div class="w3-card-4 w3-indigo w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-group"></i> <?php echo $jmlpel; ?></b></h3>
				<span class="w3-tiny">Data Pelanggan</span></div>
			</div>


			<div class="w3-col s3">
				<div class="w3-card-4 w3-indigo w3-leftbar w3-border-red" style="width:100%;"><h3><b><i class="fa fa-shopping-cart"></i> <?php echo $jmlpj; ?></b></h3>
				<span class="w3-tiny">Transaksi Penjualan</span></div>
			</div>
		</div>

		<br>
		Fitur Aplikasi :
		<div style="border-bottom:1px dashed #ccc;"></div><br>

		<ul>
			<li>Master
				<ul>
					<li>Manajemen Data Pelanggan</li>
					<li>Manajemen Data Supplier</li>
					<li>Manajemen Data Supplier</li>
				</ul>
			</li>
			<li>Data Barang
				<ul>
					<li>Manajemen Kategori Barang</li>
					<li>Manajemen Data Barang</li>
				</ul>
			</li>	
			<li>Transaksi
				<ul>
					<li>Input Transaksi Penjualan</li>
					<li>Manajemen Data Penjualan</li>
					<li>Manajemen Data Pembelian</li>
				</ul>
			</li>
			<li>Retur Barang
				<ul>
					<li>Manajemen Retur Penjualan</li>
					<li>Manajemen Retur Pembelian</li>
				</ul>
			</li>
			<li>Pembayaran
				<ul>
					<li>Manajemen Pembayaran Hutang</li>
				</ul>
			</li>
			<li>Laporan
				<ul>
					<li>Laporan Stok Barang</li>
					<li>Laporan Barang Terlaris</li>
					<li>Laporan Rangkuman Penjualan Per-periode</li>
					<li>Laporan Penjualan Per-Tahun</li>
					<li>Laporan Penjualan Barang Per-Hari</li>
					<li>Laporan Penjualan Barang Per-Periode</li>
					<li>Laporan Pembayaran Hutang Per-Hari</li>
					<li>Laporan Pembayaran Hutang Per-Periode</li>
				</ul>
			</li>
		</ul>
	</div>
	<div class="w3-col s4 w3-card">
		User Login
		<div style="border-bottom:1px dashed #ccc;"></div><br>
		<table class='w3-table w3-tiny w3-striped'>
		<?php
			$sqluser = mysql_query("SELECT * FROM user 
									WHERE id_user <> '$_SESSION[login_id]' 
									ORDER BY last_login DESC");
			while ($u = mysql_fetch_assoc($sqluser)) {
				echo"<tr style='border-bottom:1px dashed #ccc;'>
					<td>".strtoupper($u['nama_lengkap'])."</td>
					<td>".strtoupper($u['level'])."</td>
					<td>$u[last_login]</td>
				</tr>";
			}

		?>
		</table><br>

		10 Barang Paling Laris
		<div style="border-bottom:1px solid #ccc;"></div><br>
		<table class='w3-table w3-tiny w3-striped'>
		<?php
			$rlaris = mysql_query("SELECT * FROM barang_laris 
									ORDER BY jumlah DESC LIMIT 10");
			$no = 1;
			while ($b = mysql_fetch_assoc($rlaris)) {
				echo"<tr style='border-bottom:1px dashed #ccc;'>
					<td>$no.</td>
					<td>".strtoupper($b['kode_barang'])."</td>
					<td>".strtoupper($b['nama_barang'])."</td>
				</tr>";

				$no++;
			}

		?>
		</table><br>
		<?php
		if(isset($_SESSION['level']) AND ($_SESSION['level'] == "admin")) :
		?>
		Aktifitas Transaksi
		<div style="border-bottom:1px solid #ccc;"></div><br>
		<table class='w3-table w3-tiny w3-striped'>
		<?php
			$rlog = mysql_query("SELECT * FROM tb_log 
									ORDER BY id_log DESC LIMIT 10");
			$no = 1;
			while ($l = mysql_fetch_assoc($rlog)) {
				echo"<tr style='border-bottom:1px dashed #ccc;'>
					<td>$no.</td>
					<td><i>$l[timestmp] - ".$l['deskripsi']."</i></td>
				</tr>";

				$no++;
			}

		?>
		</table><br>
		<?php
		endif;
		?>
	</div>
</div>