<?php
	include"../../session.php";

	$mulya = isset($_GET['mulya']) ? $_GET['mulya'] : '';

	if ($mulya == 'listharga') {
		echo"<div style='margin-top:12px;margin-bottom:12px;'>
		<table id='tbl' class='w3-table w3-striped w3-bordered w3-tiny w3-hoverable tbl'>
			<thead>
				<tr class='w3-yellow'>
					<th>#</th>
					<th>HARGA BARANG</th>
					<th>INPUT</th>
					<th>STATUS</th>
					<th>AKSI</th>
				</tr>
			</thead>
			<tbody>";

		
			$id = isset($_GET['id']) ? $_GET['id'] : '';
			$sqlHarga = mysql_query("SELECT * FROM tb_barang_harga 
									WHERE kode_barang = '$id' 
									ORDER BY tipe ASC");
			$numRow = mysql_num_rows($sqlHarga);

			if ($numRow > 0) {
				while ($a = mysql_fetch_assoc($sqlHarga)) {
					if ($a['status'] == 'Y') {
						$alt = 'N';
						$ket = 'Disabled';
					} else {
						$alt = 'Y';
						$ket = 'Enabled';
					}

					echo"<tr>
						<td>HARGA ".$a['tipe']."</td>
						<td>Rp. ".number_format($a['harga'])."</td>
						<td>".$a['tgl_input']."</td>
						<td>".$a['status']." <a href='javascript:void(0);' alt='$alt;$a[id_harga]' class='w3-text-blue edtsts'>$ket</a></td>
						<td><a href='javascript:void(0);' alt='$a[id_harga]' class='hpsharga'><i class='fa fa-close w3-tiny w3-text-grey'></i></a></td>
					</tr>";
				}
			} else {
				echo"<tr>
					<td colspan=6>Tidak ditemukan harga...</td>
				</tr>";
			}

		echo"</tbody>
			</table>
		</div>";


		?>
		<script type="text/javascript">
			$(document).ready(function(){
				$(".hpsharga").click(function(){
					var r = confirm("Yaking ingin menghapus data ini?");
					if (r == true) {
						var id = $(this).attr('alt');
						$.ajax({
							url:'popup/harga/ajax_harga.php?mulya=hpsharga',
							type:'POST',
							data: 'id='+id,
							success:function(data) {
								return false;
							}
						});

						$.ajax({
							url:'popup/harga/ajax_harga.php?mulya=listharga',
							type:'GET',
							data:'id=<?php echo $id; ?>',
							success:function(data) {
								$("#dataharga").html(data);
								return false;
							}

						});


					} else {
						return false;
					}
					
				});

				$('.edtsts').click(function(){
					var id = $(this).attr('alt');

					$.ajax({
						url:'popup/harga/ajax_harga.php?mulya=edtstatus',
						type:'POST',
						data: 'id='+id,
						success:function(data) {
							return false;
						}
					});

					$.ajax({
						url:'popup/harga/ajax_harga.php?mulya=listharga',
						type:'GET',
						data:'id=<?php echo $id; ?>',
						success:function(data) {
							$("#dataharga").html(data);
							return false;
						}

					});
				});
			});
		</script>

		<?php

	} elseif ($mulya == 'addharga') {
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$harga = isset($_POST['harga']) ? $_POST['harga'] : '';
		$tipe = isset($_POST['tipe']) ? $_POST['tipe'] : '';


		mysql_query("INSERT INTO tb_barang_harga(kode_barang, 
												tipe,
												harga,
												tgl_input,
												status) 
										VALUES('$id', 
												'$tipe',
												'$harga',
												NOW(),
												'Y')") or die(mysql_error());
	} elseif ($mulya == 'hpsharga') {
		mysql_query("DELETE FROM tb_barang_harga 
					WHERE id_harga = '$_POST[id]'") or die(mysql_error());
	} elseif ($mulya == 'edtstatus') {
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$data = explode(";", $id);

		mysql_query("UPDATE tb_barang_harga 
					SET status = '$data[0]'
					WHERE id_harga = '$data[1]'") or die(mysql_error());
	}

?>