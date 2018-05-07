<?php
    include"class/paging.php";
    include"lib/fungsi_indotgl.php";
    include"lib/all_function.php";
    
    if(isset($_GET['mulya']))
    {
        $mulya = $_GET['mulya']; //mulyaul yang akan ditampilkan
        if ($mulya == "home") {
            include"dashboard.php";
        } elseif($mulya == "user")
        {
            include"mulya/user/user.php";
        } elseif($mulya == "menu")
        {
            include"mulya/menu/menu.php";
        } elseif($mulya == "mulyaul")
        {
            include"mulya/mulyaul/mulyaul.php";
        }

        elseif($mulya == "kategori")
        {
            include"mulya/kategori/kategori.php";
        } elseif ($mulya == "satuan")
        {
            include"mulya/satuan/satuan.php";
        } elseif($mulya == "barang")
        {
            include"mulya/barang/barang.php";
        }
        elseif($mulya == "pelanggan")
        {
            include"mulya/pelanggan/pelanggan.php";
        }
        elseif($mulya == "supplier")
        {
            include"mulya/supplier/supplier.php";
        }
        elseif($mulya == "penjualan")
        {
            include"mulya/penjualan/penjualan.php";
        }
        elseif($mulya == "pembelian")
        {
            include"mulya/pembelian/pembelian.php";
        }
        elseif($mulya == "returpenjualan")
        {
            include"mulya/retur/retur_penjualan.php";
        }
        elseif($mulya == "returpembelian")
        {
            include"mulya/retur/retur_pembelian.php";
        }
        elseif($mulya == "laporan")
        {
            include"mulya/laporan/laporan.php";
        }
        elseif($mulya == "hutang")
        {
            include"mulya/hutang/hutang.php";
        } elseif($mulya == "pengaturan") {
            include"mulya/pengaturan/pengaturan.php";
        }
        

    }
    else
    {
        header("location:index.php");
    }
?>