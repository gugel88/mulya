<h4 class="w3-text-red">Data Harga</h4>

<?php

	include"../../session.php";

	$id = isset($_POST['id']) ? $_POST['id'] : '';

	$sqlB = mysql_query("SELECT * FROM tb_barang WHERE kode_barang = '$id'");
	$numB = mysql_num_rows($sqlB);
	if ($numB > 0) {
		$b = mysql_fetch_assoc($sqlB);
		echo"<table>
			<tr>
				<td>Kode Barang</td>
				<td>$b[kode_barang]</td>
			</tr>
			<tr>
				<td>Nama Barang</td>
				<td>$b[nama_barang]</td>
			</tr>
			<tr>
				<td>Harga Beli</td>
				<td>Rp. ".number_format($b['harga_beli']).",-</td>
			</tr>
		</table>";

		echo'<p>Tambahkan data harga</p><hr>
		<form class="w3-container">
			<input class="w3-input" type="text" name="tipe" id="tipe" placeholder="0" maxlength="2">
			<input class="w3-input" type="text" name="harga" id="harga" placeholder="Rp.0,-">
			<button type="button" id="addharga" class="w3-btn w3-blue">Add</button>
		</form>';

		echo"<a href='javascript:void(0);' id='refresh'>Refresh</a>";

		echo"<div id='dataharga'></div>";

	} else {
		header("location:m.php?mulya=home");
	}

		
?>

<script type="text/javascript">
	
	$(document).ready(function(){
		$("#harga").number(true);
		$("#tipe").number(true);


		function harga() {
			$.ajax({
				url:'popup/harga/ajax_harga.php?mulya=listharga',
				type:'GET',
				data:'id=<?php echo $id; ?>',
				success:function(data) {
					$("#dataharga").html(data);
					return false;
				}


			});
		}

		harga();

		$("#addharga").click(function(){
			var harga = $("#harga").val();
			var tipe = $("#tipe").val();

			$.ajax({
				url:'popup/harga/ajax_harga.php?mulya=addharga',
				type:'POST',
				data:'id=<?php echo $id; ?>&harga='+harga+"&tipe="+tipe,
				success:function(data) {
					$("#harga").val('');
					$("#harga").focus();
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

		$("#refresh").click(function(){
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