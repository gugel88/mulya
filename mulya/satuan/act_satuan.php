<?php
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['satuan']) AND $_SESSION['satuan'] <> 'TRUE')
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

	if($mulya == "satuan" AND $act == "simpan")
	{
		//variable input
		$nama_satuan = anti_inject($_POST['nama_satuan']);

		mysql_query("INSERT INTO tb_barang_satuan(nama_satuan, 
												tgl_input)
									VALUES ('$nama_satuan',
									NOW())") or die(mysql_error());
		flash('example_message', '<p>Berhasil menambah data satuan.</p>' );

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "satuan" AND $act == "edit") 
	{
		//variable input
		$nama_satuan = anti_inject($_POST['nama_satuan']);

		mysql_query("UPDATE tb_barang_satuan SET 
										nama_satuan = '$nama_satuan' 
					WHERE id_satuan = '$_POST[id]'") or die(mysql_error());

		flash('example_message', '<p>Berhasil mengubah data satuan.</p>');

		echo"<script>
			window.history.go(-2);
		</script>";
	}

	elseif ($mulya == "satuan" AND $act == "hapus") 
	{
		mysql_query("DELETE FROM tb_barang_satuan WHERE id_satuan = '$_GET[id]'") or die(mysql_error());
		flash('example_message', '<p>Berhasil menghapus data satuan.</p>' );
		echo"<script>
			window.history.back();
		</script>";	
	}

?>