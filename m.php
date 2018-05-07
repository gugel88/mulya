<?php
    date_default_timezone_set('Asia/Jakarta');
    include"session.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php include"title_adm.php"; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="favicon.ico" />

    <link rel="stylesheet" href="css/w3.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="js/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/pace.css">
    <style type="text/css" title="currentStyle"> 
        @import "css/ui/demo_table_jui.css";
        @import "css/ui/jquery-ui-1.10.4.custom.min.css";
        @import "css/perfect-scrollbar.css";
        @import "css/sweetalert2.css";
    </style>

    <style>
    .w3-theme {color:#fff !important;background-color:#4CAF50 !important;}
    .w3-btn {background-color:#4CAF50 ;margin-bottom:4px;}
    .w3-code{border-left:4px solid #4CAF50}
    @media only screen and (max-width: 601px) {.w3-top{position:static;} #main{margin-top:0px !important}}


    .tbl th.header { 
        background-image: url(js/table.sorter/themes/blue/bg.gif);
        cursor: pointer; 
        font-weight: bold; 
        background-repeat: no-repeat; 
        background-position: center left; 
        padding-left: 20px; 
        margin-left: -1px; 
    }

    .tbl th.headerSortUp { 
      background-image: url(js/table.sorter/themes/blue/asc.gif);
      cursor: pointer; 
        font-weight: bold; 
        background-repeat: no-repeat; 
        background-position: center left; 
        padding-left: 20px; 
        margin-left: -1px; 

    } 
    .tbl th.headerSortDown { 
      background-image: url(js/table.sorter/themes/blue/desc.gif);
      cursor: pointer; 
        font-weight: bold; 
        background-repeat: no-repeat; 
        background-position: center left; 
        padding-left: 20px; 
        margin-left: -1px; 
    }
    #flash {
        position:absolute;
        top:0px;
        left:0px;
        z-index:5000;
        width:100%;
        height:500px;
        background-color:#c00;
        display:none;
    }

    #left-nav {
        overflow: hidden;
    }
 
    .ui-datepicker {
        font-family: "Trebuchet MS", "Helvetica", "Arial",  "Verdana", "sans-serif";
        font-size: 80.5%;
    }
    .ui-tooltip-content {
        font-size: 80.5%;
    }
    ui-autocomplete {
        font-size: 80%;
    }
    </style>
    <script src="js/jquery-1.12.2.min.js"></script>
    <script src="js/jquery-ui/jquery-ui.min.js"></script>
    <script src="js/perfect-scrollbar.min.js"></script>
    <script src="js/sweetalert2.min.js"></script>
    <script src="js/jquery.number.js"></script>
    <script src="js/table.sorter/jquery.tablesorter.js"></script>
    <script src="js/w3codecolors.js"></script>
    <script src="js/pace.min.js"></script>
    <script src="js/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" language="javascript" src="js/jquery.dataTables.js"></script>

    <script type="text/javascript">
        $(document).ready( function () {
            oTable = $('#example').dataTable({
                "bJQueryUI": true,
                "sPaginationType": "full_numbers",
                "oLanguage": {
                       "sLengthMenu": 'Tampil <select>'+
                         '<option value="10">10</option>'+
                         '<option value="20">20</option>'+
                         '<option value="30">30</option>'+
                         '<option value="40">40</option>'+
                         '<option value="50">50</option>'+
                         '<option value="100">100</option>'+
                         '<option value="-1">Semua Data</option>'+
                         '</select> records'
                }
            });
        } );
    </script>

</head>
<body>

<div class="w3-top">

<div class="w3-row w3-blue-grey w3-padding">
  <div class="w3-half" style="margin:15px 0 2px 0;"><b class='w3-text-shadow w3-text-bold w3-opacity'><?php echo isset($HEAD['AD_NAMA_USAHA']) ? $HEAD['AD_NAMA_USAHA'] : 'NAMA USAHA ANDA'; ?></b><br><i style="font-size:12px;"><?php echo isset($HEAD['AD_ALAMAT_USAHA']) ? $HEAD['AD_ALAMAT_USAHA'] : 'ALAMAT USAHA ANDA'; ?></i></div>
  <div class="w3-half w3-margin-top w3-wide w3-hide-medium w3-hide-small"><div class="w3-right"><?php echo date('d-m-Y H:i:s'); ?></div></div>
</div>

<nav id="left-nav" class="w3-sidenav w3-small w3-light-grey w3-card-4" style="width:250px;">
  <div id="menuTut" class="myMenu w3-accordion">
  <!--onmouseout="w3_hide_scroll()" onmouseover="w3_show_scroll()"-->
    <?php
        $sql_menu = mysql_query("SELECT * FROM menu ORDER BY posisi ASC") or die(mysql_error());
        while ($mn = mysql_fetch_assoc($sql_menu)) {
            echo"<button onclick=\"myFunction('".$mn['id_menu']."')\" class=\"w3-padding-hor-16 w3-btn-block w3-left-align w3-blue\"> $mn[nama_menu] <i class=\"fa fa-caret-down\"></i></button>
            <div id=\"$mn[id_menu]\" class=\"w3-accordion-content w3-pale-blue w3-show\">";

            $sql_sub = mysql_query("SELECT * FROM modul 
                WHERE id_menu = '$mn[id_menu]' ORDER BY posisi ASC") or die(mysql_error());
            while ($sm = mysql_fetch_assoc($sql_sub)) {
                echo"<a href=\"$sm[link_menu]\"><i class=\"$sm[icon_menu]\"></i> $sm[nama_modul]</a>";
            }

            echo"</div>";

        }
    ?>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
  </div>
  
</nav>

    <div style="margin-left:250px;">

        <ul class="w3-navbar w3-light-grey w3-small w3-card-4">
          <li><a class="w3-hover-black w3-padding-10" href="index.php"><i class="fa fa-dashboard"></i> Dashboard</a></li>
          
          <li><a class="w3-hover-black w3-padding-10" href="m.php?mulya=penjualan"><i class="fa fa-shopping-cart"></i> Transaksi Penjualan</a></li>
          <li><a class="w3-hover-black w3-padding-10" href="m.php?mulya=pembelian&act=form"><i class="fa fa-shopping-cart"></i> Transaksi Pembelian</a></li>
          
          
          <li class="w3-right"><a class="w3-hover-black w3-padding-10" href="logout.php" onclick="return confirm('Yakin ingin keluar');"><i class="fa fa-power-off"></i> Keluar (<?php echo $_SESSION['login_user']; ?>)</a></li>
          
        </ul>
    </div>

</div>

<div style="margin-left:250px;">
    <div id="main" class="w3-container" style="margin-top:110px">
        <?php include"content.php"; ?>
        <br>
    </div>
</div>

<script>
/*function w3_show_scroll()
{
    document.getElementsByClassName("w3-sidenav")[0].style.overflow="scroll";
}
function w3_hide_scroll()
{
    document.getElementsByClassName("w3-sidenav")[0].style.overflow="hidden";
}
w3_hide_scroll();


function w3_open() {
    document.getElementsByClassName("w3-sidenav")[0].style.width = "250px";
    document.getElementsByClassName("w3-sidenav")[0].style.display = "block";
    document.getElementById("main").style.marginLeft = "250px";
}
function w3_close() {
    document.getElementsByClassName("w3-sidenav")[0].style.display = "none";
    document.getElementById("main").style.marginLeft = 0;
}
*/
function w3_open() {
    document.getElementsByClassName("w3-sidenav")[0].style.display = "block";
}
function w3_close() {
    document.getElementsByClassName("w3-sidenav")[0].style.display = "none";
}

function w3_show_nav(name) {
    var id = document.getElementById("menuTut").style.display = "none";
    document.getElementById(name).style.display = "block";
    //w3_open();
}

function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
    } else { 
        x.className = x.className.replace("w3-show", "");
    }
}
</script>

<script type="text/javascript">
    $(document).ready(function() { 
        //document.getElementsByClassName("w3-sidenav")[0].style.overflow="scroll";
        // Perfect Scrollbarslide-out
        Ps.initialize(document.getElementById('left-nav'));
        $(".tbl").tablesorter();

        $("#msg-flash").delay(2000).fadeOut();

        $("html").niceScroll({
            styler: "fb",
            cursorcolor: "#e74c3c",
        });  // The document page (body)
    });

    /*$(function() {
        $(document).tooltip();

        window.addEventListener("load", function() {

            jQuery("body").append("<div id=\"flash\"></div>");

            var canvas = document.getElementById("canvas");

            var pageSize = getPageSize();
            jQuery("#flash").css({ height: pageSize[1] + "px" });

        }, false);
    });*/
</script>
</body>
</html> 