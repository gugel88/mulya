<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['kategori']) AND $_SESSION['kategori'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'm.php?mulya=hutang';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mulya/hutang/act_hutang.php';

	switch ($act) {
		default : 
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Pembayaran Hutang Penjualan</h4>
				<p style='margin-top:0;padding-top:0;'><i>Form pembayaran hutang pelanggan</i></p>
			</div>";

			echo"<div class='w3-row-padding'>
					<div class='w3-col s12 m12 l12'>
						<div class='w3-container w3-blue'>
							<h4>NAMA PELANGGAN</h4>
						</div>

						<form action='m.php?mulya=lappjperiode' method='get'>
							<input type='hidden' name='mulya' value='hutang'>
							<input type='hidden' name='act' value='view'>
							<table>
								<tr>
									<td width='220px'>Pelanggan :</td>
									<td><select name='pelanggan' class='w3-select'>
											<option value=''>- Pilih Pelanggan -</option>";
									$query = "SELECT * FROM tb_pelanggan";
									$result = mysql_query($query) or die(mysql_error());

									while ($p = mysql_fetch_assoc($result)) {
										echo"<option value='$p[kode_pelanggan]'>$p[nama_pelanggan]</option>";
									}

									echo"</select></td>
								</tr>
								<tr>
									<td colspan='4'><button type='submit' class='w3-btn' name='submit' value='cari'>Cari Pelaggan</button>
								</tr>
							</table>
						</form>
					</div>
				</div>";
		break;

		case "view" :
			if (!empty($_GET['pelanggan'])) {
				$linkaksi .= "&pelanggan=".$_GET['pelanggan'];
				$hideinp = '<input type="hidden" name="pelanggan" value="'.$_GET['pelanggan'].'">';

				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Data Hutang Pelanggan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Semua hutang pelanggan</i></p>
				</div>";

				flash('example_message');

				$qpel = "SELECT * FROM tb_pelanggan WHERE kode_pelanggan = '$_GET[pelanggan]'";
				$rpel = mysql_query($qpel);
				$temukan = mysql_num_rows($rpel);

				if ($temukan > 0) {
					$pel = mysql_fetch_assoc($rpel);

					//total semua hutang
					$qtotal = mysql_query("SELECT SUM(a.harga) AS total 
											FROM tb_detail_penjualan a, tb_penjualan b 
											WHERE a.no_transaksi = b.no_transaksi 
											AND b.status = 'HUTANG' 
											AND b.kode_pelanggan = '$_GET[pelanggan]'") or die(mysql_error());
					$rtotal = mysql_fetch_assoc($qtotal);

					//total sudah dibayar
					$qtotal2 = mysql_query("SELECT SUM(jumlah) AS totalbayar 
											FROM tb_bayar_hutang 
											WHERE kode_pelanggan = '$_GET[pelanggan]'") or die(mysql_error());
					$rtotal2 = mysql_fetch_assoc($qtotal2);

					$sisahutang = $rtotal['total'] - $rtotal2['totalbayar'];

					echo"<div style='margin-top:12px;margin-bottom:12px;'>

					<div class='w3-row'>
						<div class='w3-col s6'>
							<table class='w3-table w3-tiny'>
								<tr>
									<td width='150px'>Kode Pelanggan</td>
									<td width='10px'>:</td>
									<td><b>$pel[kode_pelanggan]</b></td>
								</tr>
								<tr>
									<td>Nama Pelanggan</td>
									<td>:</td>
									<td><b>$pel[nama_pelanggan]</b></td>
								</tr>
								<tr>
									<td>Nomor Telepon</td>
									<td>:</td>
									<td><b>$pel[nomor_telp]</b></td>
								</tr>
								<tr>
									<td>Alamat</td>
									<td>:</td>
									<td><b>$pel[alamat]</b></td>
								</tr>
							</table>
						</div>

						<div class='w3-col s6'>
							<h5>Total Hutang : <b>Rp. ".number_format($rtotal['total'])."</b></h5>
							<h5>Total Dibayar : <b>Rp. ".number_format($rtotal2['totalbayar'])."</b></h5><hr>
							<h5>Sisa Hutang : <b class='w3-text-red'>Rp. ".number_format($sisahutang)."</b></h5>
						</div>
					</div>

						

					<hr>
					<p>
						<a href='m.php?mulya=hutang' class='w3-btn w3-red w3-tiny'><i class='fa fa-caret-square-o-left'></i> Kembali</a>

						<a href='m.php?mulya=hutang&act=bayar&pelanggan=$_GET[pelanggan]' class='w3-btn w3-blue w3-tiny'><i class='fa fa-file'></i> Bayar Hutang</a>
					</p>

					<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
						<thead>
							<tr class='w3-blue'>
								<th>#</th>
								<th>NO. TRANSAKSI</th>
								<th>TGL. TRANSAKSI</th>
								<th>PETUGAS</th>
								<th>TOTAL</th>
								<th>DIBAYAR</th>
								<th>POTONGAN</th>
								<th>STATUS</th>
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

						$query = "SELECT * FROM tb_penjualan 
									WHERE kode_pelanggan = '$_GET[pelanggan]' 
									AND status = 'HUTANG' 
									ORDER BY timestmp DESC LIMIT $posisi, $batas";

						$q 	= "SELECT * FROM tb_penjualan 
								WHERE kode_pelanggan = '$_GET[pelanggan]' 
								AND status = 'HUTANG' 
								ORDER BY timestmp DESC";


						$sql_kul = mysql_query($query);
						$fd_kul = mysql_num_rows($sql_kul);

						if($fd_kul > 0)
						{
							$no = $posisi + 1;
							while ($m = mysql_fetch_assoc($sql_kul)) {
								$totalpj = total_penjualan($m['no_transaksi']);
								echo"<tr>
									<td>$no</td>
									<td><a class='w3-text-blue w3-hover-text-red' href='m.php?mulya=penjualan&act=printout&id=$m[no_transaksi]'>$m[no_transaksi]</a></td>
									<td>$m[timestmp]</td>
									<td>".nama_petugas($m['petugas'])."</td>
									<td>Rp. ".number_format($totalpj)."</td>
									<td>Rp. ".number_format($m['bayar'])."</td>
									<td>Rp. ".number_format($m['potongan'])."</td>
									<td>$m[status]</td>
								
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
								<td colspan='8'><div class='w3-center'><i>Data Transaksi Not Found.</i></div></td>
							</tr>";
						}
						

						echo"</tbody>

					</table></div>";

					echo"<div class='w3-row'>
						<div class='w3-col s1'>
							<form class='w3-tiny' action='' method='GET'>
								<input type='hidden' name='mulya' value='hutang'>
								<input type='hidden' name='act' value='view'>";
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

				} else {
					echo"<script>
						window.location.href = 'm.php?mulya=hutang';
					</script>";
				}

			} else {
				echo"<script>
					window.location.href = 'm.php?mulya=hutang';
				</script>";
			}

				
		break;
			
		case "bayar" :
			if (!empty($_GET['pelanggan'])) {
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Bayar Hutang Pelanggan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Form Bayar hutang pelanggan</i></p>
				</div>";

				$qpel = "SELECT * FROM tb_pelanggan WHERE kode_pelanggan = '$_GET[pelanggan]'";
				$rpel = mysql_query($qpel);
				$temukan = mysql_num_rows($rpel);

				if ($temukan > 0) {
					flash('example_message');

					$pel = mysql_fetch_assoc($rpel);

					echo"<div class='w3-row-padding'>
						<div class='w3-col s4 w3-card'>
							Input Pembayaran
							<div style='border-bottom:1px dashed #ccc;'></div><br>

							<div class='w3-card-2 w3-light-green'>
								<form action='$aksi?mulya=hutang&act=simpan' method='POST' class='w3-container'>
									<input type='hidden' name='kode_pelanggan' value='$pel[kode_pelanggan]'>

									<label class='w3-label w3-text-black'>Kode Pelanggan :<br> 
									<b>$pel[kode_pelanggan]</b></label><br>

									<label class='w3-label w3-text-black'>Nama Pelanggan :<br> 
									<b>$pel[nama_pelanggan]</b></label>

									<hr>

									<label class='w3-label w3-text-black'>Nama Penyetor :</label>
									<input type='text' name='nama_penyetor' id='nama_penyetor' placeholder='nama penyetor ...' class='w3-input w3-tiny w3-border-0' required>

									<label class='w3-label w3-text-black'>Jumlah Bayar :</label>
									<input type='text' name='jumlah' id='jumlah' placeholder='0' class='w3-input w3-tiny w3-border-0' required>
									<input type='hidden' name='jumlah2' id='jumlah2'>

									<p>
										<a href='m.php?mulya=hutang&act=view&pelanggan=$pel[kode_pelanggan]' class='w3-btn w3-orange'><i class='fa fa-caret-square-o-left'></i> Kembali</a> 

										<button class='w3-btn w3-pink' onclick=\"return confirm('Klik OK untuk melanjutkan');\"><i class='fa fa-save'></i> Simpan</button></p>
								</form>
							</div>

							<br>
						</div>
						<div class='w3-col s8'>
							Data Pembayaran Hutang
							<div style='border-bottom:1px dashed #ccc;'></div>";


							echo"<table style='margin-top:12px;'>
								<tr>
									<td>
										<form class='w3-tiny' action='' method='GET'>	
											<input type='hidden' name='mulya' value='hutang'>
											<input type='hidden' name='act' value='bayar'>
											<input type='hidden' name='pelanggan' value='$pel[kode_pelanggan]'>
											<div class='w3-row'>
												<div class='w3-col s1'>
													<label class='w3-label'>Search</label>
												</div>
												<div class='w3-col s2'>
													<select name='field' class='w3-select w3-padding'>
														<option value=''>- Pilih -</option>
														<option value='nama_penyetor'>NAMA PENYETOR</option>
													</select>
												</div>
												<div class='w3-col s6'>
													<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
												</div>
												<div class='w3-col s1'>
													<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
												</div>
												<div class='w3-col s1'>
													<a href='m.php?mulya=hutang&act=bayar&pelanggan=$pel[kode_pelanggan]' class='w3-btn w3-dark-grey w3-tiny'><i class='fa fa-refresh'></i> REFRESH</a>
												</div>
											</div>
										</form>
									</td>
								</tr>
								
							</table>";

							echo"<div style='margin-top:12px;margin-bottom:12px;'>
							<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
								<thead>
									<tr class='w3-yellow'>
										<th>NO</th>
										<th>NAMA PENYETOR</th>
										<th>TGL. BAYAR</th>
										<th>JML. BAYAR</th>
										<th>PETUGAS</th>
										<th>#</th>
									</tr>
								</thead>
								<tbody>";

								$p      = new Paging;
								$batas  = 5;
							    if(isset($_GET['show']) && is_numeric($_GET['show']))
								{
									$batas = (int)$_GET['show'];
									$linkaksi .="&show=$_GET[show]";
								}

								$posisi = $p->cariPosisi($batas);

								$query = "SELECT * FROM tb_bayar_hutang WHERE kode_pelanggan = '$pel[kode_pelanggan]'";

								$q 	= "SELECT * FROM tb_bayar_hutang WHERE kode_pelanggan = '$pel[kode_pelanggan]'";

								if(!empty($_GET['field']))
								{
									$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
												<input type='hidden' name='cari' value='$_GET[cari]'>";

									$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

									$query .= " AND $_GET[field] LIKE '%$_GET[cari]%'";
									$q .= " AND $_GET[field] LIKE '%$_GET[cari]%'";
								}

								$query .= " ORDER BY id_bayar DESC LIMIT $posisi, $batas";
								$q 	.= " ORDER BY id_bayar DESC";
								

								$sql_kul = mysql_query($query) or die(mysql_error());
								$fd_kul = mysql_num_rows($sql_kul);

								if($fd_kul > 0)
								{

									$no = $posisi + 1;
									while ($m = mysql_fetch_assoc($sql_kul)) {
										echo"<tr>
											<td>$no</td>
											<td>$m[nama_penyetor]</td>
											<td>".tglindo($m['tgl_bayar'])."</td>
											<td>Rp. ".number_format($m['jumlah'])."</td>
											<td>".nama_petugas($m['petugas'])."</td>
											<td><a href='$aksi?mulya=hutang&act=hapus&id=$m[id_bayar]' onclick=\"return confirm('Yakin hapus data');\"><i class='fa fa-trash w3-large w3-text-red'></i></a>
											</td>
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
										<td colspan='7'><div class='w3-center'><i>Data Pembayaran Not Found.</i></div></td>
									</tr>";
								}
								

								echo"</tbody>

							</table></div>";

							echo"<div class='w3-row'>
								<div class='w3-col s2'>
									<form class='w3-tiny' action='' method='GET'>
										<input type='hidden' name='mulya' value='hutang'>
										<input type='hidden' name='act' value='bayar'>
										<input type='hidden' name='pelanggan' value='$pel[kode_pelanggan]'>";
										if(!empty($hideinp))
										{
											echo $hideinp;
										}
										echo"<select class='w3-select w3-border' name='show' onchange='submit()'>
											<option value=''>- Show -</option>
											<option value='5'>5</option>";
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
								<div class='w3-col s10'>
									<ul class='w3-pagination w3-right w3-tiny'>";
										echo isset($linkHalaman) ? $linkHalaman : '';
									echo"</ul>
								</div>
							</div>

						</div>
					</div>";

					?>
						<script type="text/javascript">
							$(function()
							{
								$(".dp").datepicker({
									dateFormat : "yy-mm-dd",
									showAnim : "fold"
								});

								$("#jumlah").number(true);

								$('#jumlah').keyup(function(){
									var jumlah = $('#jumlah').val();
									$('#jumlah2').val(jumlah);
								});
							});
						</script>
					<?php
				} else {
				
					echo"<script>
						window.location.href = 'm.php?mulya=hutang';
					</script>";	
				
				}
			} else {
				echo"<script>
					window.location.href = 'm.php?mulya=hutang';
				</script>";
			}
		break;

	}

?>