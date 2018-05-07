<?php
	session_start();
	include"../../lib/conn.php";
	include"../../lib/all_function.php";


	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['barang']) AND $_SESSION['barang'] <> 'TRUE')
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

	if($mulya == "barang" AND $act == "simpan")
	{
		//variable input
		$kode_barang = $_POST['id'];
		$nama_barang= anti_inject($_POST['nama_barang']);
		$deskripsi= anti_inject($_POST['deskripsi']);
		$tgl_input= anti_inject($_POST['tgl_input']);
		$harga_beli= anti_inject($_POST['harga_beli2']);
		$harga_jual= anti_inject($_POST['harga_jual2']);
		$kategori_id= anti_inject($_POST['kategori_id']);
		$jml_stok= anti_inject($_POST['jml_stok']);
		$satuan= anti_inject($_POST['satuan']);


		if(empty($kode_barang)) {
			$q = mysql_query("SELECT MAX(RIGHT(kode_barang,10)) AS kodebrg
				FROM tb_barang") or die(mysql_error());
			$kode = mysql_fetch_assoc($q);

			if($kode['kodebrg'] <> NULL)
			{
				$nilaikode = $kode['kodebrg'];
	            $kode = (int) $nilaikode;

	            $kode = $kode + 1;

	            $kode_barang = 'BRG' . str_pad($kode, 10, "0", STR_PAD_LEFT);
			}
			else
			{
				$kode_barang = "BRG0000000001";
			}
		}

		$lokasi_file = $_FILES['foto']['tmp_name'];
		$image_name	= $_FILES['foto']['name'];
		$image_size = $_FILES['foto']['size'];
		$tipe_file = $_FILES['foto']['type'];

		$vdir_upload = "../../foto_barang/";
  		$vfile_upload = $vdir_upload . $image_name;

  		if(empty($lokasi_file))
		{

			mysql_query("INSERT INTO tb_barang(kode_barang, 
												nama_barang, 
												deskripsi, 
												tgl_input, 
												harga_beli, 
												harga_jual, 
												kategori_id, 
												jml_stok, 
												satuan)
											VALUES ('$kode_barang', 
												'$nama_barang', 
												'$deskripsi', 
												'$tgl_input', 
												'$harga_beli', 
												'$harga_jual', 
												'$kategori_id', 
												'$jml_stok', 
												'$satuan')") or die(mysql_error());
				flash('example_message', '<p>Berhasil menambah data biaya.</p>' );

				echo"<script>
					window.history.go(-2);
				</script>";

		}
		else
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg"){
				echo "<script>window.alert('Upload Gagal, Pastikan File yang di Upload bertipe *.JPG');
        			window.location=('../../m.php?mulya=barang')</script>";
			}
			else
			{
				mysql_query("INSERT INTO tb_barang(kode_barang, 
												nama_barang, 
												deskripsi, 
												tgl_input, 
												harga_beli, 
												harga_jual, 
												kategori_id, 
												jml_stok, 
												satuan,
												foto)
											VALUES ('$kode_barang', 
												'$nama_barang', 
												'$deskripsi', 
												'$tgl_input', 
												'$harga_beli', 
												'$harga_jual', 
												'$kategori_id', 
												'$jml_stok', 
												'$satuan',
												'$image_name')") or die(mysql_error());
				flash('example_message', '<p>Berhasil menambah data biaya.</p>' );

				move_uploaded_file($lokasi_file, $vfile_upload);

				echo"<script>
					window.history.go(-2);
				</script>";
			}
		}
	}

	elseif ($mulya == "barang" AND $act == "edit") 
	{
		//variable input
		$kode_barang = trim($_POST['id']);
		$nama_barang= anti_inject($_POST['nama_barang']);
		$deskripsi= anti_inject($_POST['deskripsi']);
		$tgl_input= anti_inject($_POST['tgl_input']);
		$harga_beli= anti_inject($_POST['harga_beli2']);
		$harga_jual= anti_inject($_POST['harga_jual2']);
		$kategori_id= anti_inject($_POST['kategori_id']);
		$jml_stok= anti_inject($_POST['jml_stok']);
		$satuan= anti_inject($_POST['satuan']);


		$lokasi_file = $_FILES['foto']['tmp_name'];
		$image_name	= $_FILES['foto']['name'];
		$image_size = $_FILES['foto']['size'];
		$tipe_file = $_FILES['foto']['type'];

		$vdir_upload = "../../foto_barang/";
  		$vfile_upload = $vdir_upload . $image_name;

  		if(empty($lokasi_file))
		{

			mysql_query("UPDATE tb_barang SET nama_barang= '$nama_barang', 
											deskripsi= '$deskripsi', 
											tgl_input= '$tgl_input', 
											harga_beli= '$harga_beli', 
											harga_jual= '$harga_jual', 
											kategori_id= '$kategori_id', 
											jml_stok= '$jml_stok', 
											satuan= '$satuan' 
						WHERE kode_barang = '$_POST[id]'") or die(mysql_error());

			flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

			echo"<script>
				window.history.go(-2);
			</script>";

		}
		else
		{
			if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg"){
				echo "<script>window.alert('Upload Gagal, Pastikan File yang di Upload bertipe *.JPG');
        			window.location=('../../m.php?mulya=barang')</script>";
			}
			else
			{
				mysql_query("UPDATE tb_barang SET nama_barang= '$nama_barang', 
												deskripsi= '$deskripsi', 
												tgl_input= '$tgl_input', 
												harga_beli= '$harga_beli', 
												harga_jual= '$harga_jual', 
												kategori_id= '$kategori_id', 
												jml_stok= '$jml_stok', 
												satuan= '$satuan',
												foto = '$image_name' 
							WHERE kode_barang = '$_POST[id]'") or die(mysql_error());

				move_uploaded_file($lokasi_file, $vfile_upload);

				flash('example_message', '<p>Berhasil mengubah data biaya.</p>');

				echo"<script>
					window.history.go(-2);
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