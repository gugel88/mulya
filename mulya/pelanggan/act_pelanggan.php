<?php
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['pelanggan']) AND $_SESSION['pelanggan'] <> 'TRUE')
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

	if($mulya == "pelanggan" AND $act == "simpan")
	{
		//variable input
		$kode_pelanggan = trim($_POST['id']);
		$nama_pelanggan= anti_inject($_POST['nama_pelanggan']);
		$nomor_telp= anti_inject($_POST['nomor_telp']);
		$alamat= anti_inject($_POST['alamat']);

		mysql_query("INSERT INTO tb_pelanggan(kode_pelanggan, 
										nama_pelanggan, 
										nomor_telp, 
										alamat)
									VALUES ('$kode_pelanggan', 
										'$nama_pelanggan', 
										'$nomor_telp', 
										'$alamat')") or die(mysql_error());
		flash('example_message', '<p>Berhasil menambah data biaya.</p>' );

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "pelanggan" AND $act == "edit") 
	{
		//variable input
		$kode_pelanggan = trim($_POST['id']);
		$nama_pelanggan= anti_inject($_POST['nama_pelanggan']);
		$nomor_telp= anti_inject($_POST['nomor_telp']);
		$alamat= anti_inject($_POST['alamat']);

		mysql_query("UPDATE tb_pelanggan SET 
										nama_pelanggan= '$nama_pelanggan', 
										nomor_telp= '$nomor_telp', 
										alamat= '$alamat' 
					WHERE kode_pelanggan = '$_POST[id]'") or die(mysql_error());

		flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "pelanggan" AND $act == "hapus") 
	{
		mysql_query("DELETE FROM tb_pelanggan WHERE kode_pelanggan = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data biaya kuliah.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}

?>