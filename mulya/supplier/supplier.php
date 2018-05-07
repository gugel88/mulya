<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['supplier']) AND $_SESSION['supplier'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'm.php?mulya=supplier';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mulya/supplier/act_supplier.php';

	switch ($act) {
		case 'form':
			if(!empty($_GET['id']))
			{
				$act = "$aksi?mulya=supplier&act=edit";
				$query = mysql_query("SELECT * FROM tb_supplier WHERE kode_supplier = '$_GET[id]'");
				$temukan = mysql_num_rows($query);
				if($temukan > 0)
				{
					$c = mysql_fetch_assoc($query);
				}
				else
				{
					header("location:m.php?mulya=supplier");
				}

			}
			else
			{
				$act = "$aksi?mulya=supplier&act=simpan";
			}

			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Form Data Supplier</h4>
				<p style='margin-top:0;padding-top:0;'><i>Form Input Data Supplier</i></p>
			</div>";

			echo"<form class='w3-small' method='POST' action='$act'>
				<table>
					<tr>
						<td width='220px'><label class='w3-label'>KODE SUPPLIER</label></td>
						<td width='10px'>:</td>
						<td><input type='text' name='id' class='w3-input' placeholder='kode_supplier' value='"?><?php echo isset($c['kode_supplier']) ? $c['kode_supplier'] : '';?><?php echo"'"?> <?php echo isset($c['kode_supplier']) ? ' readonly' : ' ';?><?php echo" required>
						</td>
						
					</tr>
					<tr>
						<td><label class='w3-label'>NAMA TOKO</label></td>
						<td>:</td>
						<td><input type='text' name='nama_toko' class='w3-input' placeholder='nama_toko' value='"?><?php echo isset($c['nama_toko']) ? $c['nama_toko'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>ALAMAT</label></td>
						<td>:</td>
						<td><input type='text' name='alamat' class='w3-input' placeholder='alamat' value='"?><?php echo isset($c['alamat']) ? $c['alamat'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>TELEPON</label></td>
						<td>:</td>
						<td><input type='text' name='telepon' class='w3-input' placeholder='telepon' value='"?><?php echo isset($c['telepon']) ? $c['telepon'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>EMAIL</label></td>
						<td>:</td>
						<td><input type='text' name='email' class='w3-input' placeholder='email' value='"?><?php echo isset($c['email']) ? $c['email'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><button type='submit' name='submit' value='simpan' class='w3-btn'><i class='fa fa-save'></i> Simpan Data</button>&nbsp;

						<button type='button' class='w3-btn w3-orange' onclick='history.back()'><i class='fa fa-rotate-left'></i> Kembali</button></td>
					</tr>
				</table>

			</form>";
			?>
				<script type="text/javascript">
					$(function()
					{
						$( ".dp" ).datepicker({
							dateFormat : "yy-mm-dd",
							showAnim : "fold"
						});
					});
				</script>
			<?php
		break;

		default :
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Data Supplier</h4>
				<p style='margin-top:0;padding-top:0;'><i>Semua Data Supplier</i></p>
			</div>";

			flash('example_message');

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='supplier'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='nama_toko'>NAMA TOKO</option>
										<option value='alamat'>ALAMAT</option>
										<option value='telepon'>TELEPON</option>
										<option value='email'>EMAIL</option></select>
								</div>
								<div class='w3-col s4'>
									<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
								</div>
								<div class='w3-col s1'>
									<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
								</div>
							</div>
						</form>
					</td>
					<td align='right'><a href='m.php?mulya=supplier' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					<a href='m.php?mulya=supplier&act=form' class='w3-btn w3-small w3-blue'><i class='fa fa-file'></i> Tambah</a></td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>KODE SUPPLIER</th>
						<th>NAMA TOKO</th>
						<th>ALAMAT</th>
						<th>TELEPON</th>
						<th>EMAIL</th>
						<th>AKSI</th>
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

				$query = "SELECT * FROM tb_supplier ";

				$q 	= "SELECT * FROM tb_supplier";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " LIMIT $posisi, $batas";
				$q 	.= " ";
				

				$sql_kul = mysql_query($query);
				$fd_kul = mysql_num_rows($sql_kul);

				if($fd_kul > 0)
				{
					$no = $posisi + 1;
					while ($m = mysql_fetch_assoc($sql_kul)) {
						echo"<tr>
						
							<td>$no</td>
							<td>$m[kode_supplier]</td>
							<td>$m[nama_toko]</td>
							<td>$m[alamat]</td>
							<td>$m[telepon]</td>
							<td>$m[email]</td>
							<td><a href='m.php?mulya=supplier&act=form&id=$m[kode_supplier]'><i class='fa fa-pencil-square w3-large w3-text-blue'></i></a> 
							<a href='$aksi?mulya=supplier&act=hapus&id=$m[kode_supplier]' onclick=\"return confirm('Yakin hapus data');\"><i class='fa fa-trash w3-large w3-text-red'></i></a></td>
						
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
						<td colspan='6'><div class='w3-center'><i>Data Supplier Not Found.</i></div></td>
					</tr>";
				}
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mulya' value='supplier'>";
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
					<ul class='w3-pagination w3-right w3-tiny'>
						$linkHalaman
					</ul>
				</div>
			</div>";
		break;
	}

	
?>