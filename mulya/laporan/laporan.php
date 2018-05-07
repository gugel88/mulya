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
	$linkaksi = 'm.php?mulya=laporan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	switch ($act) {
		case 'stokbarang':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Stok Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan sisa stok seluruh barang</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='laporan'>
							<input type='hidden' name='act' value='stokbarang'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='kode_barang'>KODE BARANG</option>
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
					
					<td align='right'><a href='m.php?mulya=laporan&act=stokbarang' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					
					</td>
					
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th></th>
						<th colspan='4'><center>STOK</center></th>
						<th colspan='2'><center>RETUR</center></th>
						<th></th>
						<th></th>
					</tr>
					<tr class='w3-yellow'>
						<th rowspan='2'>NO</th>
						<th rowspan='2'>KODE BARANG</th>
						<th rowspan='2'>NAMA BARANG</th>
						<th rowspan='2'>SATUAN</th>
						<th rowspan='2'>KATEGORI</th>
						<th>AWAL</th>
						<th>MASUK</th>
						<th>KELUAR</th>
						<th>TOTAL</th>
						<th>JUAL</th>
						<th>BELI</th>
						<th>SISA</th>
						<th>HARGA BELI</th>
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
						$stok_masuk = stok_masuk($m['kode_barang']);
						$stok_keluar = stok_keluar($m['kode_barang']);
						$total_stok = ($m['jml_stok'] + $stok_masuk) - $stok_keluar;

						$retur_jual = stok_retur_jual($m['kode_barang']);
						$retur_beli = stok_retur_beli($m['kode_barang']);

						$sisa = ($total_stok + $retur_jual) - $retur_beli;
						echo"<tr>
							<td>$no</td>
							<td>$m[kode_barang]</td>
							<td>$m[nama_barang]</td>
							<td>$m[satuan]</td>
							<td>".nama_kategori($m['kategori_id'])."</td>
							<td><center>$m[jml_stok]</center></td>
							<td><center>".$stok_masuk."</center></td>
							<td><center>".$stok_keluar."</center></td>
							<td><center>".$total_stok."</center></td>
							<td><center>".$retur_jual."</center></td>
							<td><center>".$retur_beli."</center></td>
							<td><center>".$sisa."</center></td>
							<td>$m[harga_beli]</td>
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
						<input type='hidden' name='mulya' value='laporan'>
						<input type='hidden' name='act' value='stokbarang'>";
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
					<br />
					
					<form action='popup/popup.php?mulya=cetakstok' method='post' target='_blank'>
							<table>
								
								<tr>
									<td colspan='4'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
				</div>


				
				<div class='w3-col s11'>
					<ul class='w3-pagination w3-right w3-tiny'>
						$linkHalaman
					</ul>
					
				</div>
			</div>";
			
			
			break;
			
			
			
			

		case 'laris':
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Barang Laris</h4>
				<p style='margin-top:0;padding-top:0;'><i>Laporan seluruh barang yang laris</i></p>
			</div>";

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='laporan'>
							<input type='hidden' name='act' value='laris'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='nama_barang'>NAMA BARANG</option>
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
					<td align='right'><a href='m.php?mulya=laporan&act=laris' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>KODE BARANG</th>
						<th>NAMA BARANG</th>
						<th>SATUAN</th>
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

				$query = "SELECT * FROM barang_laris ";

				$q 	= "SELECT * FROM barang_laris";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " ORDER BY jumlah DESC LIMIT $posisi, $batas";
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
							<td>$m[jumlah] $m[satuan]</td>
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
						<input type='hidden' name='mulya' value='laporan'>
						<input type='hidden' name='act' value='laris'>";
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
			

			case 'penjualan':
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Penjualan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Rekap laporan penjualan</i></p>
				</div>";

				echo"<div class='w3-row-padding'>
					<div class='w3-col s12 m6 l6'>
						<div class='w3-container w3-blue'>
							<h4>Rekap Penjualan Rangkuman Per-Periode</h4>
						</div>

						<form action='popup/popup.php?mulya=lappjperiode' method='post' target='_blank'>
							<table>
								<tr>
									<td>Tanggal Dari :</td>
									<td><input type='text' name='tgl1' id='tgl1' class='w3-input w3-small' placeholder='yyyy-mm-dd' readonly></td>
									<td>s/d</td>
									<td><input type='text' name='tgl2' id='tgl2' class='w3-input w3-small' placeholder='yyyy-mm-dd' readonly></td>
								</tr>
								<tr>
									<td colspan='4'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
					</div>
					<div class='w3-col s12 m6 l6'>
						<div class='w3-container w3-grey'>
							<h4>Rekap penjualan Per-Hari konsumen </h4>
						</div>

						<form action='popup/popup.php?mulya=lapksperhari' method='post' target='_blank'>
							<table>
								<tr>
									<td>Tanggal :</td>
									<td><input type='text' name='tgl' class='w3-input w3-small dp' placeholder='yyyy-mm-dd' readonly></td>
								</tr>
								<tr>
									<td colspan='2'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
					</div>
				</div>";

				

				?>
					<script type="text/javascript">
						$(function()
						{
							$("#tgl1, #tgl2").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange,
								showAnim : "fold"
							});

							$("#tgl3, #tgl4").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange2,
								showAnim : "fold"
							});

							$("#tgl5, #tgl6").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange3,
								showAnim : "fold"
							});

							$(".dp").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								showAnim : "fold"
							});
						});

						function customRange(input)
						{
							if (input.id == 'tgl2') {
								var minDate = new Date($("#tgl1").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}

						function customRange2(input)
						{
							if (input.id == 'tgl4') {
								var minDate = new Date($("#tgl3").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}

						function customRange3(input)
						{
							if (input.id == 'tgl5') {
								var minDate = new Date($("#tgl6").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}
					</script>
				<?php

				break;


			case 'pembelian':
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Pembelian</h4>
					<p style='margin-top:0;padding-top:0;'><i>Rekap laporan pembelian</i></p>
				</div>";

				echo"<div class='w3-row-padding'>
					<div class='w5-col s12 m6 l6'>
						<div class='w3-container w3-blue'>
							<h4>Rekap Pembelian Rangkuman Per-Periode</h4>
						</div>

						<form action='popup/popup.php?mulya=lapblperiode' method='post' target='_blank'>
							<table>
								<tr>
									<td>Tanggal Dari :</td>
									<td><input type='text' name='tgl1' id='tgl1' class='w3-input w3-small' placeholder='yyyy-mm-dd' readonly></td>
									<td>s/d</td>
									<td><input type='text' name='tgl2' id='tgl2' class='w3-input w3-small' placeholder='yyyy-mm-dd' readonly></td>
								</tr>
								<tr>
									<td colspan='4'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
					</div>
					
				</div>";

				

				?>
					<script type="text/javascript">
						$(function()
						{
							$("#tgl1, #tgl2").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange,
								showAnim : "fold"
							});

							$("#tgl3, #tgl4").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange2,
								showAnim : "fold"
							});

							$("#tgl5, #tgl6").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								beforeShow: customRange3,
								showAnim : "fold"
							});

							$(".dp").datepicker({
								showOn:"button",
								buttonImage:"images/calendar.gif",
								buttonImageOnly : true,
								dateFormat : "yy-mm-dd",
								showAnim : "fold"
							});
						});

						function customRange(input)
						{
							if (input.id == 'tgl2') {
								var minDate = new Date($("#tgl1").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}

						function customRange2(input)
						{
							if (input.id == 'tgl4') {
								var minDate = new Date($("#tgl3").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}

						function customRange3(input)
						{
							if (input.id == 'tgl5') {
								var minDate = new Date($("#tgl6").val());
								minDate.setDate(minDate.getDate() + 1)

								return {
									minDate: minDate
								}
							}
						}
					</script>
				<?php

				break;



			case 'barang':
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Laporan Data Barang</h4>
					<p style='margin-top:0;padding-top:0;'><i>Rekap laporan data barang</i></p>
				</div>";

				echo"<div class='w3-row-padding'>
					<div class='w3-col s12 m6 l6'>
						<div class='w3-container w3-red'>
							<h4>Rekap Stok Barang Terlaris Per-Bulan</h4>
						</div>

						<form action='popup/popup.php?mulya=laplarisbrgbln' method='post' target='_blank'>
							<table>
								<tr>
									<td>Pilih Bulan :</td>
									<td><select name='bln' class='w3-select' required>
											<option value=''>- Pilih Bulan -</option>";

											for($i=1; $i<=12; $i++) {
												echo"<option value='$i'>".getBulan($i)."</option>";
											}

										echo"</select>
									</td>
									<td><select name='thn' class='w3-select'>";

											for($y=date('Y'); $y >= 2010; $y--) {
												echo"<option value='$y'>".$y."</option>";
											}

										echo"</select>
									</td>
								</tr>
								<tr>
									<td colspan='4'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
					</div>
					<div class='w3-col s12 m6 l6'>
						<div class='w3-container w3-blue'>
							<h4>Rekap Pelanggan Sudah Jatuh Tempo</h4>
						</div>

						<form action='popup/popup.php?mulya=lapjthtempo' method='post' target='_blank'>
							<table>
								<tr>
									<td>Tanggal :</td>
									<td><input type='text' name='tgl' class='w3-input w3-small dp' placeholder='yyyy-mm-dd' readonly></td>
								</tr>
								<tr>
									<td colspan='2'><button type='submit' class='w3-btn' name='submit' value='Cetak'>Cetak</button>
								</tr>
							</table>
						</form>
					</div>
				</div>";
				

			?>
			<script type="text/javascript">
				$(function()
				{
					$(".dp").datepicker({
						showOn:"button",
						buttonImage:"images/calendar.gif",
						buttonImageOnly : true,
						dateFormat : "yy-mm-dd",
						showAnim : "fold"
					});
				});
			</script>

			<?php
				break;
				

	}
	
?>