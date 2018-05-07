<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['barang']) AND $_SESSION['barang'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'm.php?mulya=barang';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mulya/barang/act_barang.php';

	switch ($act) {
		case 'form':
			if(!empty($_GET['id']))
			{
				$act = "$aksi?mulya=barang&act=edit";
				$query = mysql_query("SELECT * FROM tb_barang WHERE kode_barang = '$_GET[id]'");
				$temukan = mysql_num_rows($query);
				if($temukan > 0)
				{
					$c = mysql_fetch_assoc($query);
				}
				else
				{
					header("location:m.php?mulya=barang");
				}

			}
			else
			{
				$act = "$aksi?mulya=barang&act=simpan";
			}

			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Form Data Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Form Input Data Barang</i></p>
			</div>";

			echo"<form class='w3-small' method='POST' action='$act' enctype='multipart/form-data'>
				<input type='hidden' name='harga_beli2' id='harga_beli2' value='"?><?php echo isset($c['harga_beli']) ? $c['harga_beli'] : '';?><?php echo"'>

				<input type='hidden' name='harga_jual2' id='harga_jual2' value='"?><?php echo isset($c['harga_jual']) ? $c['harga_jual'] : '';?><?php echo"'>

				<table>
					<tr>
						<td width='220px'><label class='w3-label'>KODE BARANG</label></td>
						<td width='10px'>:</td>
						<td>
							<div class='w3-row'>
								<div class='w3-col s6 m6 l4'>
									<input type='text' name='id' class='w3-input' placeholder='kode_barang' value='"?><?php echo isset($c['kode_barang']) ? $c['kode_barang'] : '';?><?php echo"'"?> <?php echo isset($c['kode_barang']) ? ' readonly' : ' ';?><?php echo">
								</div>
								<div class='w3-col s6 m6 l8'>
									<i class='w3-text-red'>* Jika kosong maka kode barang akan automatis</i>
								</div>
							</div>
						</td>
						
					</tr>
					<tr>
						<td><label class='w3-label'>NAMA BARANG</label></td>
						<td>:</td>
						<td><input type='text' name='nama_barang' class='w3-input' placeholder='nama_barang' value='"?><?php echo isset($c['nama_barang']) ? $c['nama_barang'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>DESKRIPSI</label></td>
						<td>:</td>
						<td><textarea name='deskripsi' class='w3-input'>"?><?php echo isset($c['deskripsi']) ? $c['deskripsi'] : '';?><?php echo"</textarea>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>TGL INPUT</label></td>
						<td>:</td>
						<td><input type='text' name='tgl_input' class='w3-input dp' placeholder='tgl_input' value='"?><?php echo isset($c['tgl_input']) ? $c['tgl_input'] : date('Y-m-d');?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>HARGA BELI</label></td>
						<td>:</td>
						<td><input type='text' name='harga_beli' id='harga_beli' class='w3-input mny' placeholder='harga_beli' value='"?><?php echo isset($c['harga_beli']) ? $c['harga_beli'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>HARGA JUAL</label></td>
						<td>:</td>
						<td><input type='text' name='harga_jual' id='harga_jual' class='w3-input mny' placeholder='harga_jual' value='"?><?php echo isset($c['harga_jual']) ? $c['harga_jual'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>KATEGORI</label></td>
						<td>:</td>
						<td><div class='w3-row'>
							<div class='w3-col s4'>
								<select name='kategori_id' class='w3-select'>";
								$sql_kategori = mysql_query("SELECT * FROM tb_kategori_barang");
								while($k = mysql_fetch_assoc($sql_kategori))
								{
									if(isset($c['kategori_id']) && $k['kategori_id'] == $c['kategori_id'])
									{
										echo"<option value='$k[kategori_id]' selected>$k[nama_kategori]</option>";	
									}
									else
									{
										echo"<option value='$k[kategori_id]'>$k[nama_kategori]</option>";
									}
									
								}
								echo"</select>
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>JML STOK</label></td>
						<td>:</td>
						<td><input type='text' name='jml_stok' class='w3-input' placeholder='jml_stok' value='"?><?php echo isset($c['jml_stok']) ? $c['jml_stok'] : '';?><?php echo"' required>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>SATUAN</label></td>
						<td>:</td>
						<td><div class='w3-row'>
							<div class='w3-col s4'>
								<select name='satuan' class='w3-select'>";
								$sqlsatuan = mysql_query("SELECT * FROM tb_barang_satuan");
								while($s = mysql_fetch_assoc($sqlsatuan))
								{
									if(isset($c['satuan']) AND $s['nama_satuan'] == $c['satuan'])
									{
										echo"<option value='$s[nama_satuan]' selected>$s[nama_satuan]</option>";	
									}
									else
									{
										echo"<option value='$s[nama_satuan]'>$s[nama_satuan]</option>";
									}
									
								}
								echo"</select>
							</div>
							</div>
						</td>
					</tr>
					<tr>
						<td><label class='w3-label'>FOTO</label></td>
						<td>:</td>
						<td><input type='file' name='foto'>
						<p>"?>
						<?php
							if(!empty($c['foto']))
							{
								echo"<img src='foto_barang/$c[foto]' class='w3-border w3-padding-4' width='150px'>";
							}
							?>
						<?php echo"</p></td>
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
						$(".dp").datepicker({
							dateFormat : "yy-mm-dd",
							showAnim : "fold"
						});
					});
				</script>
			<?php
		break;

		default :
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Data Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Data Semua Barang</i></p>
			</div>";

			flash('example_message');

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='barang'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s3'>
									<select name='kat' class='w3-select w3-padding'>
										<option value='all'>- Semua Kategori -</option>";
									$sqlkat = mysql_query("SELECT * FROM tb_kategori_barang");
									while ($k = mysql_fetch_assoc($sqlkat)) {
										echo"<option value='$k[kategori_id]'>$k[nama_kategori]</option>";
									}

									echo"</select>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value='nama_barang'>NAMA BARANG</option>
										<option value='tgl_input'>TGL INPUT</option>
										<option value='harga_beli'>HARGA BELI</option>
										<option value='harga_jual'>HARGA JUAL</option>
										<option value='jml_stok'>JML STOK</option>
									</select>
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
					<td align='right'><a href='m.php?mulya=barang' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					<a href='m.php?mulya=barang&act=form' class='w3-btn w3-small w3-blue'><i class='fa fa-file'></i> Tambah</a>

					<a href='popup/popup.php?mulya=cetakbarang' class='w3-btn w3-small w3-teal' target='_blank'><i class='fa fa-print'></i> Cetak</a></td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>KODE BARANG</th>
						<th>NAMA BARANG</th>
						<th>TGL INPUT</th>
						<th>HARGA BELI</th>
						<th>KATEGORI</th>
						<th>STOK</th>
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

				$query = "SELECT * FROM tb_barang ";

				$q 	= "SELECT * FROM tb_barang";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='kat' value='$_GET[kat]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&kat=$_GET[kat]&cari=$_GET[cari]";

					if ($_GET['kat'] == 'all') {
						$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
						$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					} else {
						$query .= " WHERE kategori_id = '$_GET[kat]' AND $_GET[field] LIKE '%$_GET[cari]%'";
						$q .= " WHERE kategori_id = '$_GET[kat]' AND $_GET[field] LIKE '%$_GET[cari]%'";
					}

					
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
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[tgl_input]</td>
							<td>Rp. ".number_format($m['harga_beli'])."</td>
							<td>".nama_kategori($m['kategori_id'])."</td>
							<td>$m[jml_stok] $m[satuan]</td>
							<td>
							<a href='javascript:void(0);' alt='$m[kode_barang]' class='harga'><i class='fa fa-bars w3-large w3-text-green'></i></a>
							<a href='m.php?mulya=barang&act=form&id=$m[kode_barang]'><i class='fa fa-pencil-square w3-large w3-text-blue'></i></a> 
							<a href='$aksi?mulya=barang&act=hapus&id=$m[kode_barang]' onclick=\"return confirm('Yakin ingin menghapus data?');\"><i class='fa fa-trash w3-large w3-text-red'></i></a></td>
						
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
						<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
					</tr>";
				}
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mulya' value='barang'>";
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


			?>
			<!-- The modal -->
			<div id="id01" class="w3-modal">
			  <div class="w3-modal-content" style="width: 50%;">
			    <div class="w3-container">
			      <span onclick="document.getElementById('id01').style.display='none'" 
			      class="w3-closebtn">&times;</span>

			      	<div id="data"></div>

			    </div>
			  </div>
			</div>

			<script type="text/javascript">
				
				$(document).ready(function(){
					$('.harga').click(function(){
						var id = $(this).attr('alt');

						$.ajax({
							type:'POST',
							url:'popup/harga/harga.php',
							data:'id='+id,
							success:function(data) {
								$("#data").html(data);

							}
						});

						document.getElementById("id01").style.display = "block";
						
					});
				});
			</script>

			<?php
		break;
	}

	
?>

<script type="text/javascript">
	function myAlert(x)
	{
		var href = $(this).attr('href');
		switch(x) {
			case 0 :
				swal({
				  title: 'Are you sure?',
				  text: "You won't be able to revert this!",
				  type: 'warning',
				  showCancelButton: true,
				  confirmButtonColor: '#3085d6',
				  cancelButtonColor: '#d33',
				  confirmButtonText: 'Yes, delete it!',
				  allowOutsideClick:false,
				  closeOnConfirm: true,
  					closeOnCancel: false
				},
				function(isConfirm) {
					alert(isConfirm);
					 if (isConfirm) {
					    return true;
					  } else {
					    return false;
					  }
				});

				return false;	
			break;
		}
		
	
	}

	$(function(){
		$(".mny").number(true);

		$('#harga_jual').keyup(function(){
			var harga = $('#harga_jual').val();
			$('#harga_jual2').val(harga);
			//console.log(bayar);
		});

		$('#harga_beli').keyup(function(){
			var harga = $('#harga_beli').val();
			$('#harga_beli2').val(harga);
			//console.log(bayar);
		});

	});
</script>