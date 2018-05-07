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

	if(isset($_SESSION['pembelian']) AND $_SESSION['pembelian'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
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

	if($mulya == "pembelian" AND $act == "add")
	{
		$cek_barang = mysql_query("SELECT * FROM tb_barang 
								WHERE kode_barang = '$_POST[barang]'") or die(mysql_error());

		if (mysql_num_rows($cek_barang) > 0) {
			$cek_det_barang = mysql_query("SELECT * FROM tb_detail_pembelian_tmp 
										WHERE kode_barang = '$_POST[barang]' 
										AND petugas = '$_SESSION[login_id]'") or die(mysql_error());
			if(mysql_num_rows($cek_det_barang) > 0)
			{
				mysql_query("UPDATE tb_detail_pembelian_tmp SET qty = qty + $_POST[qty] 
							WHERE kode_barang = '$_POST[barang]' 
							AND petugas = '$_SESSION[login_id]'") or die(mysql_error());

				echo"<script>
					window.history.back();
				</script>";
			}
			else
			{
				mysql_query("INSERT INTO tb_detail_pembelian_tmp (kode_barang,
																	harga_beli,
																	qty,
																	petugas,
																	timestmp)
															VALUES('$_POST[barang]',
																	'$_POST[harga2]',
																	$_POST[qty],
																	'$_SESSION[login_id]',
																	NOW())") or die(mysql_error());

				echo"<script>
					window.history.back();
				</script>";
			}
		}
		else
		{
			echo"barang tidak ditemukan!";
		}

	}

	elseif ($mulya == "pembelian" AND $act == "batal") {
		mysql_query("DELETE FROM tb_detail_pembelian_tmp 
					WHERE kode_barang = '$_GET[id]' 
					AND petugas = '$_SESSION[login_id]'") or die(mysql_error());

		echo"<script>
			window.history.back();
		</script>";	
	}

	elseif ($mulya == "pembelian" AND $act == "simpan") {
		$sql_tmp = mysql_query("SELECT * FROM tb_detail_pembelian_tmp ORDER BY timestmp ASC");
		$temukan = mysql_num_rows($sql_tmp);

		if ($temukan > 0) {
			$qsup = mysql_query("SELECT * FROM tb_supplier 
								WHERE kode_supplier = '$_POST[supplier]'") or die(mysql_error());
			if(mysql_num_rows($qsup) > 0)
			{
				$sup = mysql_fetch_assoc($qsup);
				$kodesup = $sup['kode_supplier'];
				$nama_toko = $sup['nama_toko'];
			}
			else
			{
				$kodesup = "";
				$nama_toko = "";
			}

			start_transaction();

			$resPembelian = mysql_query("INSERT INTO tb_pembelian(no_faktur, 
																	kode_supplier, 
																	nama_toko, 
																	tgl_beli, 
																	nama_kasir, 
																	petugas, 
																	timestmp)
															VALUES('$_POST[no_faktur]', 
																	'$kodesup', 
																	'$nama_toko', 
																	'$_POST[tglbeli]', 
																	'$_POST[kasir]', 
																	'$_SESSION[login_id]', 
																	NOW())");

			while ($b = mysql_fetch_assoc($sql_tmp)) {
				$resDetail = mysql_query("INSERT INTO tb_detail_pembelian(no_faktur, 
																			kode_barang, 
																			harga_beli, 
																			qty, 
																			petugas, 
																			timestmp)
																	VALUES('$_POST[no_faktur]', 
																			'$b[kode_barang]', 
																			'$b[harga_beli]', 
																			'$b[qty]', 
																			'$_SESSION[login_id]', 
																			NOW())");
				if (!$resDetail) {
					rollback();
					echo"Gagal transaksi";
					exit();
				}
			}

			if (!$resPembelian) {
				rollback();
				echo"Gagal transaksi";
				exit();
			}
			else
			{
				commit();
				echo"Berhasil transaksi!";
			}
		}
		else
		{
			echo "Tidak data barang yang di beli!";
		}
	}

	elseif ($mulya == "pembelian" AND $act == "hapus") {
		mysql_query("DELETE FROM tb_pembelian WHERE no_faktur = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data transaksi.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}

?>