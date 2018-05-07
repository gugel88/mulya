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

	if($mulya == "hutang" AND $act == "simpan")
	{

		//variable input
		$kode_pelanggan = trim($_POST['kode_pelanggan']);
		$nama_penyetor= anti_inject($_POST['nama_penyetor']);
		$jumlah = trim($_POST['jumlah2']);

		$tgl_bayar = date('Y-m-d');

		mysql_query("INSERT INTO tb_bayar_hutang(kode_pelanggan, 
										tgl_bayar,
										jumlah,
										petugas,
										nama_penyetor,
										timestmp)
									VALUES ('$kode_pelanggan', 
										'$tgl_bayar',
										'$jumlah',
										'$_SESSION[login_id]',
										'$nama_penyetor',
										NOW())") or die(mysql_error());

		flash('example_message', '<p>Berhasil menambah pembayaran.</p>' );
		
		echo"<script>
			window.history.back();
		</script>";	

	}

	elseif ($mulya == "hutang" AND $act == "hapus") {
		mysql_query("DELETE FROM tb_bayar_hutang 
			WHERE id_bayar = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data pembayaran hutang.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}
?>