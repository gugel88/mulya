<?php
	date_default_timezone_set('Asia/Jakarta');

	function nama_m($id)
	{
		$sql = mysql_query("SELECT * FROM menu WHERE id_menu = '$id'") or die(mysql_error());
		$m = mysql_fetch_assoc($sql);

		return $m['nama_menu'];
	}

	function total_pembelian($no_faktur)
	{
		$sqlbeli = mysql_query("SELECT * FROM tb_detail_pembelian WHERE no_faktur = '$no_faktur'");
		$total = 0;
		while ($b = mysql_fetch_assoc($sqlbeli)) {
			$sub_total = $b['harga_beli'] * $b['qty'];

			$total = $total + $sub_total;
		}

		$rtotal = "Rp. ".number_format($total,0);
		return $rtotal;
	}

	function total_penjualan($no_transaksi)
	{
		$sqljual = mysql_query("SELECT * FROM tb_detail_penjualan WHERE no_transaksi = '$no_transaksi'");
		$total = 0;
		while ($b = mysql_fetch_assoc($sqljual)) {
			$harga_disc = $b['harga'] - (($b['harga'] * $b['disc']) / 100);
			$sub_total = $harga_disc * $b['qty'];

			$total = $total + $sub_total;
		}

		return $total;
	}

	function nama_petugas($id)
	{
		$sql = mysql_query("SELECT * FROM user WHERE id_user = '$id'") or die(mysql_error());
		$m = mysql_fetch_assoc($sql);

		return $m['nama_lengkap'];
	}

	function nama_kategori($id)
	{
		$sql = mysql_query("SELECT nama_kategori FROM tb_kategori_barang 
							WHERE kategori_id = '$id'") or die(mysql_error());
		$m = mysql_fetch_assoc($sql);

		return $m['nama_kategori'];
	}

	function no_kwitansi_auto()
	{
		$sql = mysql_query("SELECT MAX(RIGHT(no_transaksi,5)) AS notrans 
							FROM tb_penjualan WHERE MONTH(tgl_transaksi) = '".date('m')."' 
							AND YEAR(tgl_transaksi) = '".date('Y')."'");
		$m = mysql_fetch_assoc($sql);

		$no = 0;
		if($m['notrans'] <> NULL)
		{

			$nilaikode = $m['notrans'];
            $kode = (int) $nilaikode;

            $kode = $kode + 1;

            $no = 'TR' . date('ym') . str_pad($kode, 5, "0", STR_PAD_LEFT);
		}
		else
		{
			$no = "TR".date('ym')."00001";
		}

		return $no;
	}

	function no_struk_auto()
	{
		$sql = mysql_query("SELECT MAX(RIGHT(no_struk,5)) AS notstruk 
							FROM tb_penjualan WHERE MONTH(tgl_transaksi) = '".date('m')."' 
							AND YEAR(tgl_transaksi) = '".date('Y')."'");
		$m = mysql_fetch_assoc($sql);

		$no = 0;
		if($m['notstruk'] <> NULL)
		{

			$nilaikode = $m['notstruk'];
            $kode = (int) $nilaikode;

            $kode = $kode + 1;

            $no_struk = "TR/".date('y')."/".date('m')."/".str_pad($kode, 5, "0", STR_PAD_LEFT);
		}
		else
		{
			$no_struk = "TR/".date('y')."/".date('m')."/00001";
		}

		return $no_struk;
	}

	function stok_masuk($id)
	{
		$sql = mysql_query("SELECT qty FROM tb_detail_pembelian WHERE kode_barang = '$id'");
		$total = 0;
		while ($q = mysql_fetch_assoc($sql)) {
			$total = $total + $q['qty'];
		}
		return $total;
	}

	function stok_keluar($id)
	{
		$sql = mysql_query("SELECT qty FROM tb_detail_penjualan WHERE kode_barang = '$id'");
		$total = 0;
		while ($q = mysql_fetch_assoc($sql)) {
			$total = $total + $q['qty'];
		}
		return $total;
	}

	function stok_retur_jual($id)
	{
		$sql = mysql_query("SELECT qty FROM tb_retur_penjualan WHERE kode_barang = '$id'");
		$total = 0;
		while ($q = mysql_fetch_assoc($sql)) {
			$total = $total + $q['qty'];
		}
		return $total;
	}

	function stok_retur_beli($id)
	{
		$sql = mysql_query("SELECT qty FROM tb_retur_pembelian WHERE kode_barang = '$id'");
		$total = 0;
		while ($q = mysql_fetch_assoc($sql)) {
			$total = $total + $q['qty'];
		}
		return $total;
	}

	function anti_inject($data)
	{
		$filter_sql = stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES)));
		return $filter_sql;
	}



?>