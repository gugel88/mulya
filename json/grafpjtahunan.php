<?php
include"../session_popup.php";
include"../lib/fungsi_indotgl.php";
include"../lib/fungsi_terbilang.php";
include"../lib/all_function.php";

$tahun = trim($_GET['tahun']);
$dataGrap = array();
for($i=1;$i <= 12; $i++)
{		
	$query = "SELECT * FROM tb_penjualan 
				WHERE month(tgl_transaksi) = '$i'
				AND year(tgl_transaksi) = '$tahun' 
				ORDER BY timestmp ASC";
	
	$sql_kul = mysql_query($query);
	$fd_kul = mysql_num_rows($sql_kul);

	$jumlah = 0;
	while ($m = mysql_fetch_assoc($sql_kul)) {
		$total_bayar = total_penjualan($m['no_transaksi']);
		$total = $total_bayar - $m['potongan'];

		$jumlah = $jumlah + $total;
	}
	$dataGrap[] = array('legendText' => getBulan($i), 'y' => $jumlah, "indexLabel" => getBulan($i));
}

echo json_encode($dataGrap, JSON_NUMERIC_CHECK);



?>