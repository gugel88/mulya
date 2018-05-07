<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['pengaturan']) AND $_SESSION['pengaturan'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'm.php?mod=pengaturan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mod/pengaturan/act_pengaturan.php';

	switch ($act) {
		case 'aps':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Pengaturan Aplikasi</h4>
				<p style='margin-top:0;padding-top:0;'><i>Untuk melakukan konfigurasi aplikasi</i></p>
			</div>";

			$peng = array();
			$sql = mysql_query("SELECT * FROM tb_pengaturan");
			while ($p = mysql_fetch_assoc($sql)) {
				$peng[$p['nama_peng']] = $p['val_peng'];
			}

			//print_r($peng);
			flash('example_message');

			echo"<form class='w3-small' method='POST' action='$aksi?mod=pengaturan&act=simpan'>
				<table>
					<tr>
						<td width='220px'><label class='w3-label'>Nama Usaha</label></td>
						<td width='10px'>:</td>
						<td><input type='text' name='peng[AD_NAMA_USAHA]' class='w3-input' placeholder='nama toko...' value='"?><?php echo isset($peng['AD_NAMA_USAHA']) ? $peng['AD_NAMA_USAHA'] : '';?><?php echo"'"?><?php echo" required>
						</td>
						
					</tr>
					<tr>
						<td><label class='w3-label'>Alamat Usaha</label></td>
						<td>:</td>
						<td><input type='text' name='peng[AD_ALAMAT_USAHA]' class='w3-input' placeholder='alamat usaha...' value='"?><?php echo isset($peng['AD_ALAMAT_USAHA']) ? $peng['AD_ALAMAT_USAHA'] : '';?><?php echo"'"?><?php echo" required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>Nomor Telp./HP</label></td>
						<td>:</td>
						<td><input type='text' name='peng[AD_NOMOR_HP]' class='w3-input' placeholder='nomor telp/hp...' value='"?><?php echo isset($peng['AD_NOMOR_HP']) ? $peng['AD_NOMOR_HP'] : '';?><?php echo"'"?><?php echo" required>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><button type='submit' name='submit' value='simpan' class='w3-btn w3-blue'><i class='fa fa-save'></i> Simpan Pengaturan</button>
					</tr>
				</table>
					
			</form>";
		break;

		default :
			
		break;
	}

	
?>