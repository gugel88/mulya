<?php
	if(!isset($_SESSION['login_user'])){
		header('location: ../../login.php'); // Mengarahkan ke Home Page
	}

	if(isset($_SESSION['returpemb']) AND $_SESSION['returpemb'] <> 'TRUE')
	{
		echo"<div class='w3-container w3-red'><p>Dilarang mengakses file ini.</p></div>";
		die();
	}

	//link buat paging
	$linkaksi = 'm.php?mulya=returpembelian';

	if(isset($_GET['act']))
	{
		$act = $_GET['act'];
		$linkaksi .= '&act='.$act;
	}
	else
	{
		$act = '';
	}

	$aksi = 'mulya/retur/act_retur_pembelian.php';

	switch ($act) {
		default :
			echo"<div class='w3-container w3-small w3-pale-green w3-leftbar w3-border-green'>
				<h4 style='margin-bottom:0;padding-bottom:0;'>Retur Pembelian Barang</h4>
				<p style='margin-top:0;padding-top:0;'><i>Semua Data Retur Pembelian Barang</i></p>
			</div>";

			flash('example_message');

			echo"<div class='w3-row-padding'>
				<div class='w3-col s3 w3-card'>
					Input Retur Barang
					<div style='border-bottom:1px dashed #ccc;'></div><br>

					<div class='w3-card-2 w3-grey'>
						<form action='$aksi?mulya=returpembelian&act=simpan' method='POST' class='w3-container'>

							<label class='w3-label w3-text-black'>Nomor Faktur</label>
							<input type='text' name='no_faktur' id='no_faktur' placeholder='ketik nomor transaksi ...' class='w3-input w3-tiny w3-border-0' required>

							<label class='w3-label w3-text-black'>Barang</label>
							<input type='text' name='barang' id='barang' placeholder='ketik nama barang ...' class='w3-input w3-tiny w3-border-0' required>

							<label class='w3-label w3-text-black'>Keterangan Retur:</label>
							<textarea name='keterangan' id='keterangan' class='w3-input w3-border-0 w3-tiny'></textarea>
					
							<p>
							<div class='w3-row'>
								<div class='w3-col s8'><label class='w3-label w3-text-black w3-right'>QTY:</label></div>
								<div class='w3-col s4'>
									<input type='text' name='qty' id='qty' placeholder='0' class='w3-input w3-tiny w3-border-0' required>
								</div>
							</div>
							</p>

							<p><button class='w3-btn w3-red' style='width:100%;' onclick=\"return confirm('Klik OK untuk melanjutkan');\"><i class='fa fa-save'></i> Simpan Retur</button></p>
						</form>
					</div>

					<br>
				</div>
				<div class='w3-col s9'>
					Data Retur Pembelian Barang
					<div style='border-bottom:1px dashed #ccc;'></div>";


					echo"<table style='margin-top:12px;'>
						<tr>
							<td>
								<form class='w3-tiny' action='' method='GET'>	
									<input type='hidden' name='mulya' value='returpembelian'>
									<div class='w3-row'>
										<div class='w3-col s1'>
											<label class='w3-label'>Search</label>
										</div>
										<div class='w3-col s2'>
											<select name='field' class='w3-select w3-padding'>
												<option value=''>- Pilih -</option>
												<option value='a.no_faktur'>NO. FAKTUR</option>
												<option value='a.kode_barang'>KODE BARANG</option>
												<option value='b.nama_barang'>NAMA BARANG</option>
											</select>
										</div>
										<div class='w3-col s6'>
											<input type='text' name='cari' class='w3-input' placeholder='cari ...'>
										</div>
										<div class='w3-col s1'>
											<button type='submit' class='w3-btn w3-tiny'><i class='fa fa-paper-plane'></i> GO</button>
										</div>
										<div class='w3-col s1'>
											<a href='m.php?mulya=returpembelian' class='w3-btn w3-dark-grey w3-tiny'><i class='fa fa-refresh'></i> REFRESH</a>
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
								<th>NO. FAKTUR</th>
								<th>KODE BRG.</th>
								<th>NAMA BRG.</th>
								<th>HARGA</th>
								<th>TOTAL</th>
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

						$query = "SELECT a.*, b.nama_barang FROM tb_retur_pembelian a LEFT JOIN tb_barang b 
								ON a.kode_barang = b.kode_barang";

						$q 	= "SELECT a.*, b.nama_barang FROM tb_retur_pembelian a LEFT JOIN tb_barang b 
								ON a.kode_barang = b.kode_barang";

						if(!empty($_GET['field']))
						{
							$hideinp = "<input type='hidden' name='field' value='$_GET[field]'>
										<input type='hidden' name='cari' value='$_GET[cari]'>";

							$linkaksi .= "&field=$_GET[field]&cari=$_GET[cari]";

							$query .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
							$q .= " WHERE $_GET[field] LIKE '%$_GET[cari]%'";
						}

						$query .= " ORDER BY a.timestmp DESC LIMIT $posisi, $batas";
						$q 	.= " ORDER BY a.timestmp DESC";
						

						$sql_kul = mysql_query($query);
						$fd_kul = mysql_num_rows($sql_kul);

						if($fd_kul > 0)
						{
							$total_harga = 0;

							$no = $posisi + 1;
							while ($m = mysql_fetch_assoc($sql_kul)) {
								$sub_total = $m['harga_beli'] * $m['qty'];
								$total_harga = $total_harga + $sub_total;

								echo"<tr>
									<td>$no</td>
									<td><a class='w3-hover-text-red w3-text-blue' href='m.php?mulya=pembelian&act=detail&id=$m[no_faktur]'>$m[no_faktur]</a></td>
									<td>$m[kode_barang]</td>
									<td>$m[nama_barang]</td>
									<td>Rp. ".number_format($m['harga_beli'])." X $m[qty]</td>
									<td>Rp. ".number_format($total_harga)."</td>
									<td><a href='$aksi?mulya=returpembelian&act=hapus&id=$m[no_faktur]&kode=$m[kode_barang]' onclick=\"return confirm('Yakin hapus data');\"><i class='fa fa-trash w3-large w3-text-red'></i></a>
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
								<td colspan='7'><div class='w3-center'><i>Data Barang Not Found.</i></div></td>
							</tr>";
						}
						

						echo"</tbody>

					</table></div>";

					echo"<div class='w3-row'>
						<div class='w3-col s2'>
							<form class='w3-tiny' action='' method='GET'>
								<input type='hidden' name='mulya' value='returpembelian'>";
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
					});
				</script>
			<?php
		break;

	}
?>

<script type="text/javascript">
	$(function(){
		$("#bayar").number(true);
		
		<?php
			$sqlTags = mysql_query("SELECT no_faktur FROM tb_pembelian 
								ORDER BY no_faktur ASC") or die(mysql_error());

			$tags = array();
			while($t = mysql_fetch_assoc($sqlTags))
			{
				$tags[] = '"'.$t['no_faktur'].'"';
			}
		?>
		var availableTags = [<?php echo implode(", \n\t\t\t", $tags); ?>];
	    $( "#no_faktur" ).autocomplete({
	    	source: availableTags,
	    	autoFocus:true, 
	    	select:function(event, ui) {
	    		$("#barang").focus();
	    		console.log(ui.item.label);
	    	}
	    });

	    <?php
			$sqlTagsb = mysql_query("SELECT * FROM tb_barang 
								ORDER BY kode_barang ASC") or die(mysql_error());

			$tagsb = array();
			while($b = mysql_fetch_assoc($sqlTagsb))
			{
				$tagsb[] = '{label : "'.$b['nama_barang'].'", value : "'.$b['kode_barang'].'"}';
			}
		?>
		var availableTagsb = [<?php echo implode(", \n\t\t\t", $tagsb); ?>];
	    $( "#barang" ).autocomplete({
	    	source: availableTagsb,
	    	autoFocus:true, 
	    	select:function(event, ui) {
	    		$("#qty").focus();
	    		console.log(ui.item.label);
	    	}
	    });
	});
</script>