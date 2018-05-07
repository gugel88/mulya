<link rel="stylesheet" type="text/css" href="../../css/pace.css">
<script src="../../js/pace.min.js"></script>
<?php
	date_default_timezone_set('Asia/Jakarta');
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";
	include"../../lib/fungsi_transaction.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_GET['mulya']) && isset($_GET['act']))
	{
		$mulya = $_GET['mulya'];
		$act = $_GET['act'];
	}
	else
	{
		$mulya = "";
		$act = "";
	}

	if($mulya == "penjualan" AND $act == "add")
	{
		$cek_barang = mysql_query("SELECT * FROM tb_barang 
								WHERE kode_barang = '$_POST[id]'") or die(mysql_error());

		if (mysql_num_rows($cek_barang) > 0) {
			$disc = 0;
			if(!empty($_POST['disc']) AND is_numeric($_POST['disc']))
			{
				$disc = $_POST['disc'];
			}

			$b = mysql_fetch_assoc($cek_barang);
			$stok_masuk = stok_masuk($b['kode_barang']);
			$stok_keluar = stok_keluar($b['kode_barang']);
			$total_stok = ($b['jml_stok'] + $stok_masuk) - $stok_keluar;

			$retur_jual = stok_retur_jual($b['kode_barang']);
			$retur_beli = stok_retur_beli($b['kode_barang']);

			$cek_stok_tmp = mysql_query("SELECT * FROM tb_detail_penjualan_tmp 
										WHERE kode_barang = '$_POST[id]'") or die(mysql_error());
			$cekdbrg = mysql_fetch_assoc($cek_stok_tmp);

			//sisa stok
			$sisa = (($total_stok + $retur_jual) - $retur_beli) - $cekdbrg['qty'];
			if ($sisa > 0) {
				$cek_det_barang = mysql_query("SELECT * FROM tb_detail_penjualan_tmp 
										WHERE kode_barang = '$_POST[id]' 
										AND petugas = '$_SESSION[login_id]'") or die(mysql_error());
				if(mysql_num_rows($cek_det_barang) > 0)
				{
					mysql_query("UPDATE tb_detail_penjualan_tmp SET qty = qty + 1 
								WHERE kode_barang = '$_POST[id]' 
								AND petugas = '$_SESSION[login_id]'") or die(mysql_error());
				}
				else
				{
					mysql_query("INSERT INTO tb_detail_penjualan_tmp (kode_barang,
																		harga,
																		disc,
																		qty,
																		petugas,
																		timestmp)
																VALUES('$_POST[id]',
																		'$_POST[harga]',
																		$disc,
																		1,
																		'$_SESSION[login_id]',
																		NOW())") or die(mysql_error());
				}
					
				echo"<script>
					window.history.back();
				</script>";	
			}
			else
			{
				flash('example_message', '<p>Data Stok tidak cukup.</p>', 'w3-red');
				echo"<script>
					window.history.back();
				</script>";	
			}
				
		}
		else
		{
			echo"Tidak barang!";
		}

	}

	elseif ($mulya == "penjualan" AND $act == "batal") {
		mysql_query("DELETE FROM tb_detail_penjualan_tmp 
					WHERE kode_barang = '$_GET[id]' 
					AND petugas = '$_SESSION[login_id]'") or die(mysql_error());

		echo"<script>
			window.history.back();
		</script>";	
	}

	elseif ($mulya == "penjualan" AND $act == "bataltransaksi") {
		mysql_query("DELETE FROM tb_detail_penjualan_tmp 
					WHERE petugas = '$_SESSION[login_id]'") or die(mysql_error());

		echo"<script>
			window.history.back();
		</script>";	
	}

	elseif ($mulya == "penjualan" AND $act == "editqty") {
		$rowNums = isset($_POST['rowNums']) ? $_POST['rowNums'] : NULL;

		$pesan = array();
		if (count($rowNums) > 0) {
			//print_r($rowNums);
			foreach ($rowNums as $i) {
				$cek_barang = mysql_query("SELECT * FROM tb_barang 
									WHERE kode_barang = '".$_POST['kode_'.$i]."'") or die(mysql_error());

				if (mysql_num_rows($cek_barang) > 0) {

					$b = mysql_fetch_assoc($cek_barang);
					$stok_masuk = stok_masuk($b['kode_barang']);
					$stok_keluar = stok_keluar($b['kode_barang']);
					$total_stok = ($b['jml_stok'] + $stok_masuk) - $stok_keluar;

					$retur_jual = stok_retur_jual($b['kode_barang']);
					$retur_beli = stok_retur_beli($b['kode_barang']);


					//sisa stok
					$sisa = (($total_stok + $retur_jual) - $retur_beli);
					if ($sisa >= $_POST['qty_'.$i]) {
						mysql_query("UPDATE tb_detail_penjualan_tmp SET qty = '".$_POST['qty_'.$i]."',  
																		disc = '".$_POST['disc_'.$i]."' 
									WHERE kode_barang = '".$_POST['kode_'.$i]."' 
									AND petugas = '$_SESSION[login_id]'") or die(mysql_error());

					} else {
						$pesan[] = " kode barang : ".$_POST['kode_'.$i]." dengan jumlah : ".$_POST['qty_'.$i]." sisa stok : ".$sisa;
					}
				}

					
			}
			if (count($pesan) > 0) {
				$pesan_er = "";
				foreach ($pesan as $row) {
					$pesan_er .= $row."<br>";
				}
				flash('example_message', '<p>Data Stok tidak cukup. '.$pesan_er.'</p>', 'w3-red');
			}
		}
			
		
		echo"<script>
			window.history.back();
		</script>";	
	}

	elseif ($mulya == "penjualan" AND $act == "editqty1") {
		$rowNums = isset($_POST['rowNums']) ? $_POST['rowNums'] : NULL;

		$pesan = array();
		if (count($rowNums) > 0) {
			//print_r($rowNums);
			foreach ($rowNums as $i) {
				$cek_barang = mysql_query("SELECT * FROM tb_detail_penjualan
									WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());

				if (mysql_num_rows($cek_barang) > 0) {

					$b = mysql_fetch_assoc($cek_barang);
					$stok_masuk = stok_masuk($b['kode_barang']);
					$stok_keluar = stok_keluar($b['kode_barang']);
					$total_stok = ($b['jml_stok'] + $stok_masuk) - $stok_keluar;

					$retur_jual = stok_retur_jual($b['kode_barang']);
					$retur_beli = stok_retur_beli($b['kode_barang']);


					//sisa stok
					$sisa = (($total_stok + $retur_jual) - $retur_beli);
					if ($sisa >= $_POST['qty'.$i]) {
						mysql_query("UPDATE tb_detail_penjualan SET qty = '".$_POST['qty'.$i]."',  
																		
									") or die(mysql_error());

					} else {
						$pesan[] = " kode barang : ".$_POST['kode_'.$i]." dengan jumlah : ".$_POST['qty_'.$i]." sisa stok : ".$sisa;
					}
				}

					
			}
			if (count($pesan) > 0) {
				$pesan_er = "";
				foreach ($pesan as $row) {
					$pesan_er .= $row."<br>";
				}
				flash('example_message', '<p>Data Stok tidak cukup. '.$pesan_er.'</p>', 'w3-red');
			}
		}
			
		
		echo"<script>
			window.history.back();
		</script>";	
	}

	if ($mulya == "penjualan" AND $act == "edit") 
	{
		//variable input
		$no_transaksi = trim($_POST['no_transaksi']);
		$kode_barang= anti_inject($_POST['kode_barang']);
		$nama_barang= anti_inject($_POST['nama_barang']);
		$harga= anti_inject($_POST['harga']);
		$qty= anti_inject($_POST['qty']);
		

  		if(empty($lokasi_file))
		{

			mysql_query("UPDATE tb_detail_penjualan SET no_transaksi= '$no_transaksi', 
											kode_barang= '$kode_barang', 
											nama_barang= '$nama_barang', 
											harga= '$harga', 
											qty= '$qty', 
											 
						WHERE tb_detail_penjualan = '$_POST[id]'") or die(mysql_error());

			flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

			echo"<script>
				window.history.back();
			</script>";

		}
		else
		{
			
				mysql_query("UPDATE tb_detail_penjualan SET no_transaksi= '$no_transaksi', 
											kode_barang= '$kode_barang', 
											nama_barang= '$nama_barang', 
											harga= '$harga', 
											qty= '$qty', 
											 
						WHERE tb_detail_penjualan = '$_POST[id]'") or die(mysql_error());

				
				flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

				echo"<script>
					window.history.back();
				</script>";
			
		}

		
	}

	elseif($mulya == "penjualan" AND $act == "simpan")
	{
		$qtmp = mysql_query("SELECT * FROM tb_detail_penjualan_tmp 
							WHERE petugas = '$_SESSION[login_id]' 
							ORDER BY timestmp ASC");

		if (mysql_num_rows($qtmp) > 0) {
			$no_transaksi = no_kwitansi_auto(); //no transaksi automatis
			$no_struk = no_struk_auto(); //no transaksi automatis

			$jmlbayar = $_POST['jmlbayar2'];
			$total_bayar = 0;

			$tgl = date('Y-m-d');
			$newdate = strtotime('+10 day', strtotime($tgl));
			$tgl_tempo = date('Y-m-j', $newdate);

			while($tmp = mysql_fetch_assoc($qtmp))
			{
				$chart[] = $tmp;

				//hitung total
				$harga_disc = $tmp['harga'] - (($tmp['harga'] * $tmp['disc']) / 100);
				$sub_total = $harga_disc * $tmp['qty'];

				$total_bayar =  $total_bayar + $sub_total;
			}

			if ($_POST['potongan2'] > 0) {
				$total_bayar = $total_bayar - $_POST['potongan2'];
			}
			else
			{
				$total_bayar = $total_bayar;
			}
			
			//print_r($chart);
			$qpel = mysql_query("SELECT * FROM tb_pelanggan 
								WHERE kode_pelanggan = '".anti_inject($_POST['nama'])."'");
			if(mysql_num_rows($qpel) > 0)
			{
				$p = mysql_fetch_assoc($qpel);
				$kode_pel = $p['kode_pelanggan'];
				$nama_pelanggan = anti_inject($p['nama_pelanggan']);
			}
			else
			{
				$kode_pel = "";
				$nama_pelanggan = anti_inject($_POST['nama']);
			}
			//echo $nama_pelanggan;

			//apakah pembayaran sudah cukup
			if (($total_bayar <= $jmlbayar) OR ($_POST['status'] == "HUTANG")) {
				//start transaction
				start_transaction();

				//pembuatan header
				$qsimpanheader = mysql_query("INSERT INTO tb_penjualan(no_transaksi,
																		no_struk, 
																		kode_pelanggan, 
																		nama_pelanggan, 
																		tgl_transaksi, 
																		tgl_tempo, 
																		petugas, 
																		status,
																		bayar, 
																		potongan, 
																		timestmp)
																VALUES('$no_transaksi', 
																		'$no_struk', 
																		'$kode_pel', 
																		'$nama_pelanggan',
																		'$tgl',  
																		'$tgl_tempo', 
																		'$_SESSION[login_id]',
																		'$_POST[status]', 
																		$jmlbayar, 
																		$_POST[potongan2], 
																		NOW())");
				if (!$qsimpanheader) {
					rollback();
					flash('example_message', '<p>Transaksi Gagal.</p>', 'w3-red');
					echo"<script>
						window.history.back();
					</script>";	
				}
				else
				{
					foreach ($chart as $row) {
						$qsimpandetail = mysql_query("INSERT INTO tb_detail_penjualan(no_transaksi,
																						kode_barang,
																						qty,
																						harga, 
																						disc, 
																						petugas, 
																						timestmp)
																				VALUES('$no_transaksi', 
																						'$row[kode_barang]', 
																						$row[qty], 
																						'$row[harga]', 
																						$row[disc], 
																						$row[petugas], 
																						'$row[timestmp]')");
						if (!$qsimpandetail) {
							rollback();
							flash('example_message', '<p>Transaksi gagal.</p>', 'w3-red' );
							echo"<script>
								window.history.back();
							</script>";	
						}
					}
					commit();
					header("location:../../m.php?mulya=penjualan&act=printout&id=".$no_transaksi);
				}
				//commit();
			}
			else {
				flash('example_message', '<p>Pembayaran tidak cukup!</p>', 'w3-yellow');
				echo"<script>
					window.history.back();
				</script>";	
			}

				
		}


		else
		{
			flash('example_message', '<p>Tidak ada barang yang di jual!</p>', 'w3-red');
			echo"<script>
				window.history.back();
			</script>";	
		}
	}


	

	elseif ($mulya == "penjualan" AND $act == "hapus") {
		if(isset($_SESSION['hapuspenjualan']) AND $_SESSION['hapuspenjualan'] <> 'TRUE')
		{
			echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
			die();
		}
		else
		{
			mysql_query("DELETE FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
			flash('example_message', '<p>Berhasil menghapus data transaksi.</p>' );
			echo"<script>
				window.history.back();
			</script>";	
		}
			
	}



?>