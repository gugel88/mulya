<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['level']) AND $_SESSION['level'] <> 'admin')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = "m.php?mulya=user";

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= "&act=".$act;
	}
	else
	{
		$act = "";
	}

	$aksi = "mulya/user/act_user.php";

	switch ($act) {
		case 'form':
			$akses_master = array();
			if(!empty($_GET['id']))
			{
				$act = "$aksi?mulya=user&act=edit";
				$query = mysql_query("SELECT * FROM user WHERE id_user = '$_GET[id]'");
				$temukan = mysql_num_rows($query);

				if($temukan > 0)
				{
					$c = mysql_fetch_assoc($query);

					$akses_master = explode(", ", $c['akses_master']);

					//echo print_r($akses_master);
				}
				else
				{
					header("location:m.php?mulya=user");
				}

			}
			else
			{
				$act = "$aksi?mulya=user&act=simpan";	
			}

			echo"<div class=\"w3-container w3-small w3-pale-yellow w3-leftbar w3-border-yellow\">
				<h4 style=\"margin-bottom:0;padding-bottom:0;\">Form User</h4>
				<p style=\"margin-top:0;padding-top:0;\"><i>Form untuk pengguna sistem.</i></p>
			</div>";

			flash('example_class');

			echo"<form class='w3-small' method='POST' action='$act'>
				<input type='hidden' name='id' value='"?><?php echo isset($c['id_user']) ? $c['id_user'] : '';?><?php echo"'>
				<table>
					<tr>
						<td width='220px'><label class='w3-label'>Nama Lengkap</label></td>
						<td width='10px'>:</td>
						<td><input type='text' name='nama_lengkap' class='w3-input' placeholder='nama lengkap' value='"?><?php echo isset($c['nama_lengkap']) ? $c['nama_lengkap'] : '';?><?php echo"' required></td>
					</tr>
					<tr>
						<td><label class='w3-label'>Username</label></td>
						<td>:</td>
						<td><input type='text' name='usernm' class='w3-input' placeholder='username' value='"?><?php echo isset($c['usernm']) ? $c['usernm'] : '';?><?php echo"' required></td>
					</tr>
					<tr>
						<td><label class='w3-label'>Password</label></td>
						<td>:</td>
						<td><input type='password' name='passwd' class='w3-input' placeholder='password'></td>
					</tr>
					<tr>
						<td><label class='w3-label'>Repeat Password</label></td>
						<td>:</td>
						<td><input type='password' name='rpasswd' class='w3-input' placeholder='repeat password'></td>
					</tr>
					<tr>
						<td><label class='w3-label'>Level</label></td>
						<td>:</td>
						<td><div class='w3-row'>
							<div class='w3-col s4'>
								<select name='level' class='w3-select' required>
									<option value='user' "?><?php echo (isset($c['level']) AND $c['level'] == "user") ? 'selected' : ''; ?><?php echo">User</option>
									<option value='admin' "?><?php echo (isset($c['level']) AND $c['level'] == "admin") ? 'selected' : ''; ?><?php echo">Administrator</option>
								</select>
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>Akses Data Master</label></td>
						<td>:</td>
						<td>
							<input type='checkbox' class='w3-check' name='master[]' value='pelanggan' "?><?php echo !empty(in_array("pelanggan", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Data Pelanggan</label>

							<input type='checkbox' class='w3-check' name='master[]' value='supplier' "?><?php echo !empty(in_array("supplier", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Data Supplier</label>

							<input type='checkbox' class='w3-check' name='master[]' value='kategori' "?><?php echo !empty(in_array("kategori", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Data Kategori</label>

							<input type='checkbox' class='w3-check' name='master[]' value='satuan' "?><?php echo !empty(in_array("satuan", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Data Satuan</label>

							<input type='checkbox' class='w3-check' name='master[]' value='barang' "?><?php echo !empty(in_array("barang", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Data Barang</label>
						</td>
					</tr>

					<tr>
						<td><label class='w3-label'>Akses Transaksi Penjualan</label></td>
						<td>:</td>
						<td>
							<input type='checkbox' class='w3-check' name='master[]' value='hapuspenjualan' "?><?php echo !empty(in_array("hapuspenjualan", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Hapus Transaksi</label>
						</td>
					</tr>

					<tr>
						<td><label class='w3-label'>Akses Transaksi Pembelian</label></td>
						<td>:</td>
						<td>
							<input type='checkbox' class='w3-check' name='master[]' value='pembelian' "?><?php echo !empty(in_array("pembelian", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Transaksi Pemblian</label>
						</td>
					</tr>

					<tr>
						<td><label class='w3-label'>Retur</label></td>
						<td>:</td>
						<td>
							<input type='checkbox' class='w3-check' name='master[]' value='returpj' "?><?php echo !empty(in_array("returpj", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Retur Penjualan</label>

							<input type='checkbox' class='w3-check' name='master[]' value='returpemb' "?><?php echo !empty(in_array("returpemb", $akses_master)) ? 'checked' : '';?><?php echo"> 
							<label class='w3-validate'>Retur Pembelian</label>
						</td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><button type='submit' name='submit' value='simpan' class='w3-btn'>Simpan Data</button>&nbsp;

						<button type='button' class='w3-btn w3-orange' onclick='history.back()'>Kembali</button></td>
					</tr>
				</table>
					
					
				</p>

			</form>";
			?>
				<script type="text/javascript">
					$(function()
					{
						$('.mny').number(true);
						$('#jumlah').keyup(function(){
							var biaya = $('#jumlah').val();
							$('#jumlah2').val(biaya);
						});

						$( ".dp" ).datepicker({
							dateFormat : "yy-mm-dd",
							showAnim : "fold"
						});
					});
				</script>
			<?php
		break;
		
		default :
			echo"<div class=\"w3-container w3-small w3-pale-green w3-leftbar w3-border-green\">
				<h4 style=\"margin-bottom:0;padding-bottom:0;\">Data User</h4>
				<p style=\"margin-top:0;padding-top:0;\"><i>Semua data pengguna dari sistem.</i></p>
			</div>";

			flash('example_message');

			echo"<table style=\"margin-top:12px;\">
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='user'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Cari</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding w3-border'>
										<option value='1'>Nama</option>
										<option value='2'>Username</option>
										<option value='3'>Level</option>
									</select>
								</div>
								<div class='w3-col s4'>
									<input type='text' name='cari' class='w3-input w3-border' placeholder='cari ...'>
								</div>
								<div class='w3-col s1'>
									<button type='submit' class='w3-btn w3-tiny'>GO</button>
								</div>
							</div>
						</form>
					</td>
					<td align='right'><a href='m.php?mulya=user' class='w3-btn w3-small'><i class='fa fa-refresh'></i></a>
					<a href='m.php?mulya=user&act=form' class='w3-btn w3-small w3-blue'><i class='fa fa-user-plus'></i></a></td>
				</tr>
				
			</table>";

			echo"<div style=\"margin-top:12px;margin-bottom:12px;\">
			<table class=\"w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl\">
				<thead>
					<tr class=\"w3-yellow\">
						<th>ID</th>
						<th>Nama</th>
						<th>Username</th>
						<th>Level</th>
						<th>Last Login</th>
						<th>Aksi</th>
					</tr>
				</thead>
				<tbody>";

				$p      = new Paging;
				$batas  = 10;
			    if(isset($_GET['show']) && is_numeric($_GET['show']))
				{
					$batas = (int)$_GET['show'];
					$linkaksi .="&show=$_GET[show]";
				}

				$posisi = $p->cariPosisi($batas);

				$query = "SELECT * FROM user";

				$q 	= "SELECT * FROM user";

				if(isset($_GET['cari']))
				{
					$fld = array('','nama_lengkap', 'usernm', 'level');
					if(is_numeric($_GET['field']))
					{
						$indx = (int)$_GET['field'];	
					}
					else
					{
						$indx = 1;
					}
					

					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $fld[$indx] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $fld[$indx] LIKE '%$_GET[cari]%'";
				}

				$query .= " ORDER BY id_user ASC LIMIT $posisi, $batas";
				$q 	.= " ORDER BY id_user ASC";
				

				$sql_kel = mysql_query($query) or die(mysql_error());
				$fd_kel = mysql_num_rows($sql_kel);

				if($fd_kel > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kel)) {
						echo"<tr>
							<td>$m[id_user]</td>
							<td>$m[nama_lengkap]</td>
							<td>$m[usernm]</td>
							<td>".strtoupper($m['level'])."</td>
							<td>$m[last_login]</td>
							<td><a href='m.php?mulya=user&act=form&id=$m[id_user]'><i class='fa fa-pencil-square w3-large w3-text-blue'></i></a></td>
						</tr>";

						$no++;
					}
	

					$jmldata = mysql_num_rows(mysql_query($q));

					$jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
		    		$linkHalaman = $p->navHalaman($_GET['halaman'], $jmlhalaman, $linkaksi);
				}
				else
				{
					echo"<tr>
						<td colspan='10'><div class='w3-center'><i>Data User Not found.</i></div></td>
					</tr>";
				}
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mulya' value='user'>";
						if(!empty($hideinp))
						{
							echo $hideinp;
						}
						echo"<select class='w3-select w3-border' name='show' onchange='submit()'>
							<option value=''>- Show -</option>";
							$i=10;
							while($i <= 100)
							{
								if(isset($_GET['show']) AND (int)$_GET['show'] == $i)
								{
									echo"<option value='$i' selected>$i</option>";	
								}
								else
								{
									echo"<option value='$i'>$i</option>";
								}

								$i+=10;
							}
						echo"</select>
					</form>
				</div>
				<div class='w3-col s11'>
					<ul class=\"w3-pagination w3-right w3-tiny\">
						$linkHalaman
					</ul>
				</div>
			</div>";
		break;
	}

	
?>