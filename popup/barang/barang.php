<h4 class="w3-text-blue">Add Barang</h4>

<?php

	include"../../session.php";
	$aksi = 'mulya/penjualan/act_penjualan.php';

	$id = isset($_POST['id']) ? $_POST['id'] : '';

	$sqlB = mysql_query("SELECT * FROM tb_barang WHERE kode_barang = '$id'");
	$numB = mysql_num_rows($sqlB);
	if ($numB > 0) {
		$b = mysql_fetch_assoc($sqlB);
		echo"<table>
			<tr>
				<td width='120px''>Kode Barang</td>
				<td>: $b[kode_barang]</td>
			</tr>
			<tr>
				<td>Nama Barang</td>
				<td>: $b[nama_barang]</td>
			</tr>
		</table>";

		echo'
		<form action="'.$aksi.'?mulya=penjualan&act=add" method="POST" class="w3-container">
			<input type="hidden" name="id" value="'.$id.'">
			<label class="w3-label">Discount (%) :</label>
			<input class="w3-input" type="text" name="disc" id="disc" placeholder="0" maxlength="2">

			<label class="w3-label">Pilih Harga :</label>
			<select name="harga" class="w3-select" required>
				<option value="">- Pilih Harga -</option>';

				$sqlHarga = mysql_query("SELECT * FROM tb_barang_harga 
					WHERE kode_barang = '$id' 
					AND status = 'Y' ORDER BY tipe ASC") or die(mysql_error());
				while ($h = mysql_fetch_assoc($sqlHarga)) {
					echo"<option value='$h[harga]'>HARGA ".$h['tipe']." - Rp.".number_format($h['harga'])."</option>";
				}

			echo'</select>
			<button type="submit" class="w3-btn w3-blue">Add</button>
		</form><br>';

		

	} else {
		header("location:m.php?mulya=home");
	}

		
?>
