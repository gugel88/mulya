<?php
include"lib/conn.php";
session_start(); // Memulai Session
$error=''; // Variabel untuk menyimpan pesan error
if (isset($_POST['submit'])) {
	if (empty($_POST['username']) || empty($_POST['password'])) {
			$error = "Username or Password is invalid";
	}
	else
	{
		// Variabel username dan password
		$username=$_POST['username'];
		$password=$_POST['password'];

		$pass = md5($password);
		
		// Mencegah MySQL injection 
		$username = stripslashes($username);
		$password = stripslashes($password);

		$username = mysql_real_escape_string($username);
		$password = mysql_real_escape_string($password);
		// SQL query untuk memeriksa apakah karyawan terdapat di database?
		$query = mysql_query("SELECT * FROM user WHERE passwd='$pass' AND usernm='$username'", $conn) or die(mysql_error());
		$rows = mysql_num_rows($query);

		if ($rows == 1) {
			$a = mysql_fetch_assoc($query);

			$akses_master = explode(", ", $a['akses_master']);

			$_SESSION['login_user']=$username; // Membuat Sesi/session
			$_SESSION['login_id'] = $a['id_user'];
			$_SESSION['level'] = $a['level'];

			if($a['level'] == "user")
			{
				
				$_SESSION['pelanggan'] = in_array("pelanggan", $akses_master) ? "TRUE" : "FALSE";
				$_SESSION['supplier'] = in_array("supplier", $akses_master) ? "TRUE" : "FALSE";
				$_SESSION['kategori'] = in_array("kategori", $akses_master) ? "TRUE" : "FALSE";
				$_SESSION['barang'] = in_array("barang", $akses_master) ? "TRUE" : "FALSE";	
				$_SESSION['hapuspenjualan'] = in_array("hapuspenjualan", $akses_master) ? "TRUE" : "FALSE";	

				$_SESSION['pembelian'] = in_array("pembelian", $akses_master) ? "TRUE" : "FALSE";	
				$_SESSION['returpj'] = in_array("returpj", $akses_master) ? "TRUE" : "FALSE";	
				$_SESSION['returpemb'] = in_array("returpemb", $akses_master) ? "TRUE" : "FALSE";	
				$_SESSION['satuan'] = in_array("satuan", $akses_master) ? "TRUE" : "FALSE";	
				$_SESSION['pengaturan'] = in_array("pengaturan", $akses_master) ? "TRUE" : "FALSE";	
			}
			else
			{
				$_SESSION['pelanggan'] = "TRUE";
				$_SESSION['supplier'] = "TRUE";
				$_SESSION['kategori'] = "TRUE";
				$_SESSION['barang'] = "TRUE";
				$_SESSION['hapuspenjualan'] = "TRUE";

				$_SESSION['pembelian'] = "TRUE";
				$_SESSION['returpj'] = "TRUE";
				$_SESSION['returpemb'] = "TRUE";
				$_SESSION['satuan'] = "TRUE";
				$_SESSION['pengaturan'] = "TRUE";

			}
			

			mysql_query("UPDATE user SET last_login = NOW() WHERE id_user = '$a[id_user]'");

			header("location: index.php"); // Mengarahkan ke halaman profil
		} else {
			$error = "Username atau Password salah.";
		}
		mysql_close($conn); // Menutup koneksi
	}
}
?>