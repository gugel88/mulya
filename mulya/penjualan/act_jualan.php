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

		
	if ($mulya == "jualan" AND $act == "edit") 
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

		
	}

	elseif ($mulya == "barang" AND $act == "hapus") 
	{
		mysql_query("DELETE FROM tb_barang WHERE kode_barang = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data biaya kuliah.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}

?>