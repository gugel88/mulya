<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	//link buat paging
	$linkaksi = 'm.php?mulya=penjualan';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mulya/penjualan/act_penjualan.php';

	switch ($act) {
		default:
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Transaksi Penjualan</h4>
				<p style='margin-top:0;padding-top:0;'><i>Menu Transaksi Penjualan Barang</i></p>
			</div>";

			flash('example_message');

			echo"<div class='w3-row-padding'>
				<div class='w3-col s7'>Data Barang
				<div style='border-bottom:1px dashed #ccc;'></div>";

					echo"<table style='margin-top:12px;'>
						<tr>
							<td>
								<form class='w3-tiny' action='' method='GET'>	
									<input type='hidden' name='mulya' value='penjualan'>
									<div class='w3-row'>
										<div class='w3-col s1'>
											<label class='w3-label'>Search</label>
										</div>
										<div class='w3-col s2'>
											<select name='field' class='w3-select w3-padding'>
												<option value=''>- Pilih -</option>
												<option value='kode_barang'>KODE BARANG</option>
												<option value='nama_barang'>NAMA BARANG</option>
												<option value='harga_jual'>HARGA</option>
											</select>
										</div>
										<div class='w3-col s6'>
											<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
										</div>
										<div class='w3-col s1'>
											<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
										</div>
										<div class='w3-col s1'>
											<a href='m.php?mulya=penjualan' class='w3-btn w3-dark-grey w3-tiny'><i class='fa fa-refresh'></i> REFRESH</a>
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
								<th>KODE</th>
								<th>NAMA BARANG</th>
								<th width='130px'>ADD</th>
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
								echo"<tr>
									<td>$no</td>
									<td>$m[kode_barang]</td>
									<td>$m[nama_barang]</td>
									<td>
										<button type='button' value='$m[kode_barang]' class='w3-btn w3-red w3-tiny addbrg'><i class='fa fa-cart-plus'></i> ADD</button>
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
								<td colspan='10'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
							</tr>";
						}
						

						echo"</tbody>

					</table></div>";

					echo"<div class='w3-row'>
						<div class='w3-col s2'>
							<form class='w3-tiny' action='' method='GET'>
								<input type='hidden' name='mulya' value='penjualan'>";
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
							<ul class='w3-pagination w3-right w3-tiny'>
								$linkHalaman
							</ul>
						</div>
					</div>


				</div>";


				echo"<div class='w3-col s5 w3-card'>Keranjang Penjualan
				<div style='border-bottom:1px dashed #ccc;'></div><br>";
					echo"<table class='w3-table w3-tiny w3-hoverable tbl'>
						<thead>
						<tr class='w3-blue'>
							<th>#</th>
							<th>BARANG</th>
							<th>HARGA</th>
							<th>DISC.</th>
							<th colspan='2'>SUB TOTAL</th>
						</tr>
						</thead>

						<tbody>";

						$sql_tmp = mysql_query("SELECT a.kode_barang, a.qty, a.harga, b.nama_barang, a.disc 
												FROM tb_detail_penjualan_tmp a, tb_barang b
												WHERE a.kode_barang = b.kode_barang 
												AND a.petugas = '$_SESSION[login_id]' 
												ORDER BY a.timestmp ASC") or die(mysql_error());
						$no = 1;

						$sub_total = 0;
						$total_harga = 0;

						if(mysql_num_rows($sql_tmp) > 0)
						{
							while ($b = mysql_fetch_assoc($sql_tmp)) {
								$harga_disc = $b['harga'] - (($b['harga'] * $b['disc']) / 100);
								$sub_total = $harga_disc * $b['qty'];
								$total_harga = $total_harga + $sub_total;

								echo"<tr style='border-bottom:1px dashed #ccc;'>
									<td>$no</td>
									<td>$b[nama_barang]</td>
									<td>Rp. ".number_format($b['harga'])." X $b[qty]</td>
									<td><center>".number_format($b['disc'])."%</center></td>
									<td>Rp. ".number_format($sub_total)."</td>
									<td><a href='$aksi?mulya=penjualan&act=batal&id=$b[kode_barang]' onclick=\"return confirm('Yakin ingin membatalkan?');\"><i class='fa fa-close w3-tiny w3-text-grey'></i></a></td>
								</tr>";

								$no++;
							}
						}
							
						else
						{
							echo"<tr>
								<td colspan='5'><center><i>Keranjang Kosong</i></center></td>
							</tr>";
						}

						echo"<tr class='w3-light-grey'>
							<td colspan='6'><center>
							<a href='#' class='w3-text-blue' onclick=\"document.getElementById('id01').style.display='block'\">Edit Transaksi</a> | 
							<a href='$aksi?mulya=penjualan&act=bataltransaksi' class='w3-text-red' onclick=\"return confirm('Yakin ingin membatalkan semua transaksi?');\">Hapus Semua</a></center>
							</td>
						</tr>
						</tbody>

						<tfoot>
						<tr>
							<td colspan='2'><b>TOTAL</b></td>
							<td colspan='4'><b class='w3-text-red w3-small w3-right'>Rp. ".number_format($total_harga)."</b></td>
						</tr>
						<tr>
							<td colspan='3'><b>POTONGAN HARGA (Rp.)<b></td>
							<td colspan='3'><input type='text' name='potongan' id='potongan' class='w3-input w3-border w3-tiny w3-right' value='0'></td>
						</tr>
						<tr style='border-top:1px dashed #ccc;'>
							<td colspan='2'><b class='w3-text-blue'>TOTAL BAYAR</b><td>
							<td colspan='4'><b class='w3-text-red w3-small w3-right'>Rp. <span id='tot'>0</span></b></td>
						</tr>
						</tfoot>


					</table><hr>

					<div class='w3-card-2 w3-light-blue'>
						<form action='$aksi?mulya=penjualan&act=simpan' method='POST' class='w3-container'>
							<input type='hidden' name='potongan2' id='potongan2' value='0'>
							<input type='hidden' name='total' id='total' value='"?><?php echo isset($total_harga) ? $total_harga : 0; ?><?php echo"'>
							<input type='hidden' name='totalbyr' id='totalbyr'>

							<input type='hidden' name='jmlbayar2' id='bayar2'>
							<label class='w3-label w3-text-black'>Nama Pelanggan :$m[nama_pelanggan]</label>
							<input type='text' name='nama' id='nama' class='w3-input w3-tiny w3-border-0' required>

							<label class='w3-label w3-text-black'>Bayar (Rp):</label>
							<input type='text' name='jmlbayar' id='bayar' class='w3-input w3-tiny w3-border-0' required>

							
							<label class='w3-label w3-text-black'>Status Pembayaran:</label>
							<div class='w3-row'>
								<div class='w3-col s6'>
									<input type='radio' class='w3-radio' name='status' value='LUNAS' checked>
									<label class='w3-validate'>LUNAS</label>
								</div>
								<div class='w3-col s6'>
									<input type='radio' class='w3-radio' name='status' value='HUTANG'>
									<label class='w3-validate'>HUTANG</label>
								</div>
							</div>

							<hr>
							<label class='w3-label w3-text-black'>Kembali:</label>
							<div class='w3-right w3-red w3-padding-4 w3-card-4'><b>Rp. <span id='kembali'>0</span></b></div>

							<p><button class='w3-btn w3-green' onclick=\"return confirm('Klik OK untuk melanjutkan');\"><i class='fa fa-save'></i> Simpan Transaksi</button></p>
						</form>
					</div><br>";

				echo"</div>
			</div>";


			?>
			<!-- The Modal -->
			<div id="id01" class="w3-modal">
			  <div class="w3-modal-content">
			    <div class="w3-container">
			      <span onclick="document.getElementById('id01').style.display='none'" 
			      class="w3-closebtn">&times;</span>

			      <h3>Edit Qty</h3>
			      <form action="<?php echo $aksi; ?>?mulya=penjualan&act=editqty" method="POST">
			      <?php
			      echo"<table class='w3-table w3-tiny w3-hoverable tbl'>
						<thead>
						<tr class='w3-blue'>
							<th>#</th>
							<th>BARANG</th>
							<th>HARGA</th>
							<th>DISC.</th>
							<th>QTY</th>
						</tr>
						</thead>

						<tbody>";

						$sql_tmp = mysql_query("SELECT a.kode_barang, a.qty, a.harga, b.nama_barang, a.disc 
												FROM tb_detail_penjualan_tmp a, tb_barang b
												WHERE a.kode_barang = b.kode_barang 
												AND a.petugas = '$_SESSION[login_id]' 
												ORDER BY a.timestmp ASC") or die(mysql_error());
						$no = 1;
						
						if(mysql_num_rows($sql_tmp) > 0)
						{
							$i = 0;
							while ($b = mysql_fetch_assoc($sql_tmp)) {

								echo"<input type='hidden' name='rowNums[]' value='$i'>
									<input type='hidden' name='kode_".$i."' value='$b[kode_barang]'>
								<tr style='border-bottom:1px dashed #ccc;'>
									<td>$no</td>
									<td>$b[nama_barang]</td>
									<td>Rp. ".number_format($b['harga'])."</td>
									<td width='150px'><input type='text' class='w3-input w3-border w3-tiny' name='disc_".$i."' value='".$b['disc']."'> %</td>
									<td width='150px'><input type='text' class='w3-input w3-border w3-tiny' name='qty_".$i."' value='".$b['qty']."'></td>
								</tr>";

								$no++;
								$i++;
							}
						}
							
						else
						{
							echo"<tr>
								<td colspan='5'><center><i>Keranjang Kosong</i></center></td>
							</tr>";
						}

						echo"</tbody></table>";

			      ?>
			      <center><input type="submit" name="submit" value="Simpan" class="w3-btn w3-blue"></center>
			      </form>

			    </div>
			  </div>
			</div>


			<!-- The Modal -->
			<div id="id02" class="w3-modal">
			  <div class="w3-modal-content" style="width: 35%;">
			    <div class="w3-container">
			      <span onclick="document.getElementById('id02').style.display='none'" 
			      class="w3-closebtn">&times;</span>

			      	<div id="data"></div>

			    </div>
			  </div>
			</div>


			<script type="text/javascript">
				$(document).ready(function(){
					$(".addbrg").click(function() {
						var id = $(this).val();

						$.ajax({
							type:'POST',
							url:'popup/barang/barang.php',
							
							data:'id='+id,
							success:function(data) {
								$("#data").html(data);
							}
						})

						document.getElementById("id02").style.display = "block";
					});


				});

			</script>
			<?php

		break;
		


		case "printout" :
				
			if(isset($_GET['id']))
			{
				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Printout Penjualan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Data Penjualan Barang</i></p>
				</div><br>
				
				

				<div class='w3-container w3-padding-4 w3-tiny w3-pale-red'>
					<p><i>Jika terjadi kesalahan harap lapor Administrator.</i></p>
				</div>";

				$sqltrans = mysql_query("SELECT * FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
				$tra = mysql_fetch_assoc($sqltrans);

				echo"<table class='w3-table w3-tiny'>
					<tr style='border-bottom:1px dashed #ccc;'>
						<td width='150px'>No. Transaksi</td>
						<td width='10px'>:</td>
						<td><b>$tra[no_transaksi]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>No. Struk</td>
						<td>:</td>
						<td><b>$tra[no_struk]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Nama / Kode</td>
						<td>:</td>
						<td><b>$tra[nama_pelanggan] / "?><?php echo !empty($tra['kode_pelanggan']) ? $tra['kode_pelanggan'] : "-"; ?><?php echo"</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Tanggal Transaksi</td>
						<td>:</td>
						<td><b>$tra[timestmp]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Tanggal Jatuh Tempo</td>
						<td>:</td>
						<td><b>$tra[tgl_tempo]</b></td>
					</tr>

					<tr style='border-bottom:1px dashed #ccc;'>
						<td>Status</td>
						<td>:</td>
						<td><b>$tra[status]</b></td>
					</tr>
				</table>
				<div style='height:10px;'></div>";

				echo"<h4 style='float:right'>Detail Barang

					<button class='w3-btn w3-tiny' onclick=\"window.history.back()\"><i class='fa fa-mail-reply-all'></i> Back</button>
					<a href='m.php?mulya=penjualan' class='w3-btn w3-red w3-tiny'><i class='fa fa-cart-plus'></i> Transaksi Baru</a>
					<a href='popup/popup.php?mulya=cetakkwitansi&id=$_GET[id]' class='w3-btn w3-dark-grey w3-tiny' target='_blank'><i class='fa fa-print'></i> Cetak Kwitansi</a>
				    </h4>
					
				
				<table class='w3-table w3-tiny w3-hoverable w3-bordered tbl'>
					<thead>
					<tr class='w3-blue'>
						<th>#</th>
						<th>KODE</th>
						<th>BARANG</th>
						<th>HARGA</th>
						<th>DISC.</th>
						<th colspan='2'>SUB TOTAL</th>
					</tr>
					</thead>

					<tbody>";

				$sql = mysql_query("SELECT a.*, b.nama_barang, b.satuan 
									FROM tb_detail_penjualan a LEFT JOIN tb_barang b 
									ON a.kode_barang = b.kode_barang
									WHERE a.no_transaksi = '$_GET[id]'") or die(mysql_error());
				$sub_total = 0;
				$total = 0;
				$no = 1;
				while($p = mysql_fetch_assoc($sql))
				{
					$harga_disc = $p['harga'] - (($p['harga'] * $p['disc']) / 100);
					$sub_total = $harga_disc * $p['qty'];

					$total = $total + $sub_total;
					echo"<tr>
						<td>$no</td>
						<td>$p[kode_barang]</td>
						<td>$p[nama_barang]</td>
						<td>Rp. ".number_format($p['harga'],0)." X $p[qty] $p[satuan]</td>
						<td>".number_format($p['disc'],0)."%</td>
						<td>Rp. ".number_format($sub_total)."</td>
					</tr>";

					$no++;
				}
				$total_bayar = $total - $tra['potongan'];
				$sisa = $tra['bayar'] - $total_bayar;

				echo"</tbody>
					<tfoot>
					<tr class='w3-light-grey'>
						<td colspan='5'>Total Harga</b></td>
						<td>Rp. ".number_format($total)."</td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'>Potongan Harga</td>
						<td>Rp. ".number_format($tra['potongan'])."</td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Total Bayar</b></td>
						<td><b>Rp. ".number_format($total_bayar)."</b></td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Pembayaran</b></td>
						<td><b>Rp. ".number_format($tra['bayar'])."</b></td>
					</tr>
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Kembali</b></td>
						<td><b>Rp. "?><?php echo ($sisa > 0 ) ? number_format($sisa) : '0'; ?><?php echo"</b></td>
					</tr>
					</tfoot>
				</table>

				";

			}
		break;


		case "edit" :
		   
			 
				
			if(isset($_GET['id']))
			{

				echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
					<h4 style='margin-bottom:0;padding-bottom:0;'>Edit Penjualan</h4>
					<p style='margin-top:0;padding-top:0;'><i>Data Penjualan Barang</i></p>
				</div>";

				
				$sqltrans = mysql_query("SELECT * FROM tb_penjualan WHERE no_transaksi = '$_GET[id]'") or die(mysql_error());
				$tra = mysql_fetch_assoc($sqltrans);


				echo"<table class='w3-table w3-tiny'>
					<tr style='border-bottom:1px dashed #ccc;'>
						<td width='150px'>No. Transaksi</td>
						<td width='10px'>:</td>
						<td><b>$tra[no_transaksi]</b></td>
					</tr>
				</table>

				<div style='height:10px;'></div>";


				echo"<form class='w3-small' method='POST' action='$aksi?mulya=penjualan&act=editqty1' >
				<h4>Detail Barang</h4>
				<table class='w3-table w3-tiny w3-hoverable w3-bordered tbl'>
					<thead>
					<tr class='w3-blue'>
						<th>#</th>
						<th>KODE</th>
						<th>BARANG</th>
						<th>HARGA</th>
						<th>DISC.</th>
						<th colspan='2'>SUB TOTAL</th>
					</tr>
					</thead>

					<tbody>";



				$sql = mysql_query("SELECT a.*, b.nama_barang, b.satuan 
									FROM tb_detail_penjualan a LEFT JOIN tb_barang b 
									ON a.kode_barang = b.kode_barang
									WHERE a.no_transaksi = '$_GET[id]'") or die(mysql_error());
				$sub_total = 0;
				$total = 0;
				$no = 1;
				while($p = mysql_fetch_assoc($sql))
				{
					$harga_disc = $p['harga'] - (($p['harga'] * $p['disc']) / 100);
					$sub_total = $harga_disc * $p['qty'];

					$total = $total + $sub_total;
					echo"<tr>
						<td>$no</td>
						<td>$p[kode_barang]</td>
						<td>$p[nama_barang]</td>
						
						<td width='150px'>
						Rp. ".number_format($p['harga'],0)." </td>
						<td width='150px'><input type='text' class='w3-input w3-border w3-tiny' name='qty".$p['qty']."' value='".$p['qty']."'></td>
						
						<td>Rp. ".number_format($sub_total)."</td>
					</tr>";

					$no++;
				}
				$total_bayar = $total - $tra['potongan'];
				$sisa = $tra['bayar'] - $total_bayar;

				echo"</tbody>
					<tfoot>
					
					<tr class='w3-light-grey'>
						<td colspan='5'><b>Total Bayar</b></td>
						<td><b>Rp. ".number_format($total_bayar)."</b></td>
					</tr>
					
					<tr>
						<td>&nbsp;</td>
						<td>&nbsp;</td>
						<td align='right'><button type='submit' name='submit' value='simpan' class='w3-btn'><i class='fa fa-save'></i> Simpan Data</button>&nbsp;

						<button type='button' class='w3-btn w3-orange' onclick='history.back()'><i class='fa fa-rotate-left'></i> Kembali</button></td>
					</tr>
					</tfoot>
					</form>
				</table>

				";



			}
			
		break;


		case "list":
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Data Transaksi Penjualan</h4>
				<p style='margin-top:0;padding-top:0;'><i>Data Semua Transaksi Penjualan</i></p>
			</div>";

			flash('example_message');

			echo"<table style='margin-top:12px;'>
				<tr>
					<td>
						<form class='w3-tiny' action='' method='GET'>	
							<input type='hidden' name='mulya' value='penjualan'>
							<input type='hidden' name='act' value='list'>
							<div class='w3-row'>
								<div class='w3-col s1'>
									<label class='w3-label'>Search</label>
								</div>
								<div class='w3-col s2'>
									<select name='field' class='w3-select w3-padding'>
										<option value=''>- Pilih -</option>
										<option value='no_transaksi'>NO. TRANSAKSI</option>
										<option value='nama_pelanggan'>NAMA PELANGGAN</option>
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
					<td align='right'><a href='m.php?mulya=penjualan&act=list' class='w3-btn w3-dark-grey w3-small'><i class='fa fa-refresh'></i> Refresh</a>
					</td>
				</tr>
				
			</table>";

			echo"<div style='margin-top:12px;margin-bottom:12px;'>
			<table class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
				<thead>
					<tr class='w3-yellow'>
						<th>NO</th>
						<th>NO. TRANSAKSI 2</th>
						<th>KODE PEL.</th>
						<th>NAMA PELANGGAN</th>
						<th>TGL. TRANSAKSI</th>
						<th>PETUGAS</th>
						<th>TOTAL</th>
						<th>POTONGAN</th>
						<th>STATUS</th>
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

				$query = "SELECT * FROM tb_penjualan ";

				$q 	= "SELECT * FROM tb_penjualan";

				if(!empty($_GET['field']))
				{
					$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
								<input type='hidden' name='cari' value='$_GET[cari]'>";

					$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

					$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
					$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
				}

				$query .= " ORDER BY timestmp DESC LIMIT $posisi, $batas";
				$q 	.= " ORDER BY timestmp DESC";
				

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
							<td>$m[kode_pelanggan]</td>
							<td>$m[nama_pelanggan]</td>
							<td>$m[timestmp]</td>
							<td>".nama_petugas($m['petugas'])."</td>
							<td>Rp. ".number_format($totalpj)."</td>
							<td>Rp. ".number_format($m['potongan'])."</td>
							<td>$m[status]</td>
							<td><a href='$aksi?mulya=penjualan&act=hapus&id=$m[no_transaksi]' onclick=\"return confirm('Yakin hapus data');\"><i class='fa fa-trash w3-large w3-text-red'></i></a>&nbsp; &nbsp; &nbsp; 
							<a class='w3-text-blue w3-hover-text-blue' href='m.php?mulya=penjualan&act=edit&id=$m[no_transaksi]'><i class='fa fa-pencil-square w3-large w3-text-blue'></i></a>
							<!--<a href='$aksi?mulya=penjualan&act=editqty&id=$m[no_transaksi]' class='w3-text-blue' onclick=\"document.getElementById('id01').style.display='block'\">Edit Transaksi</a>
							<a href='$aksi?mulya=penjualan&act=editqty&id=$m[no_transaksi]';\"><i class='fa fa-pencil-square w3-large w3-text-red'></i></a>-->
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
						<td colspan='8'><div class='w3-center'><i>Data Transaksi Not Found.</i></div></td>
					</tr>";
				}
				

				echo"</tbody>

			</table></div>";

			echo"<div class='w3-row'>
				<div class='w3-col s1'>
					<form class='w3-tiny' action='' method='GET'>
						<input type='hidden' name='mulya' value='penjualan'>
						<input type='hidden' name='act' value='list'>";
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

			$gtot = 0;
			$ptot = 0;
			$sqltotal = mysql_query("SELECT no_transaksi, potongan FROM tb_penjualan");
			$c = mysql_num_rows($sqltotal);
			if ($c > 0) {

				while($tot = mysql_fetch_assoc($sqltotal)) {
					$gtot = $gtot + total_penjualan($tot['no_transaksi']);
					$ptot = $ptot + $tot['potongan'];
				}
			}

			echo"<div class='w3-row'>
				<div class='w3-col s12'>
				<h4>Total Penjualan : <b>Rp. ".number_format($gtot, 0).",-</b><br>
				Total Potongan : <b>Rp. ".number_format($ptot, 0).",-</b>
				</h4>
				</div>
			</div>";
		break;

	}

?>

<script type="text/javascript">
	$(function(){
		$("#bayar").number(true);
		$("#potongan").number(true);

		$('#bayar').keyup(function(){
			var bayar = $('#bayar').val();
			$('#bayar2').val(bayar);

			hitung_kembali();

		});

		hitung_kembali();
		function hitung_kembali()
		{
			var bayar = $('#bayar').val();

			var potongan = $('#potongan').val();
			$('#potongan2').val(potongan);

			var total = $("#total").val();
			var pot = $("#potongan2").val();
			
			var tot_bayar = total - pot;
			if (tot_bayar > 0) {
				$("#tot").text(tot_bayar).number(true);
			}
			else
			{
				$("#tot").text(0);
			}

			var kembali = bayar - tot_bayar;
			$("#kembali").text(kembali).number(true);
		}

		$('#potongan').keyup(function(){
			hitung_kembali();
		});
		
		<?php
			$sqlTags = mysql_query("SELECT * FROM tb_pelanggan 
								ORDER BY kode_pelanggan ASC") or die(mysql_error());

			$tags = array();
			while($t = mysql_fetch_assoc($sqlTags))
			{
				$tags[] = '{label : "'.$t['nama_pelanggan'].'", value : "'.$t['kode_pelanggan'].'"}';
			}
		?>
		var availableTags = [<?php echo implode(", \n\t\t\t", $tags); ?>];
	    $( "#nama" ).autocomplete({
	    	source: availableTags,
	    	select:function(event, ui) {
	    		$("#bayar").focus();
	    		console.log(ui.item.label);
	    	}
	    });
	});
</script>