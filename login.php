<!DOCTYPE html>
<html>
<title>Toko Mulya - Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/w3.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" type="text/css" href="css/pace.css">
<link rel="shortcut icon" href="favicon.ico" />

<style>
body {
  background: url(images/bg4.jpg) no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
.w3-theme {color:#fff !important;background-color:#4CAF50 !important;}
.w3-btn {background-color:#4CAF50 ;margin-bottom:4px;}
.w3-code{border-left:4px solid #4CAF50}
@media only screen and (max-width: 601px) {.w3-top{position:static;} #main{margin-top:0px !important}}
</style>

<script src="js/pace.min.js"></script>
<body class='w3-grey' onload="document.login.username.focus();">

</div>

<div style="margin-top:200px;"></div>

<div class="w3-row-padding">
  <div class="w3-col s9">&nbsp;</div>

  <div class="w3-col s3 w3-card-2 w3-light-grey">
    <?php
      include"login_check.php";

      if(isset($_SESSION['login_user'])){
        header("location: index.php");
      }
    if(!empty($error)) :
    ?>
    <div class="w3-container w3-red">
      <span onclick="this.parentElement.style.display='none'" class="w3-closebtn">x</span> 
      <p><?php echo $error; ?></p>
    </div>
    <?php endif; ?>

    <form id="form-login" name="login" class="w3-container" method="POST">
    <div class="w3-row w3-blue-grey w3-padding">
      <div class="w3"><b class='w3-text-shadow w3-text-bold w3-opacity'><h4>MASUK KE SISTEM<h4></b></div>
    </div>
    <div class="w3-navbar w3-light-grey w3-small w3-card-4" style="height:3px;"></div>
    <br>
      <p>
        <input class="w3-input w3-border" type="text" name="username" placeholder="Nama Pengguna" required>
      </p>
      <p>
        <input class="w3-input w3-border" type="password" name="password" placeholder="Kata Sandi"required>
      </p>
      <p><button class="w3-btn w3-blue" name="submit" value="submit">Masuk</button>
        <button class="w3-btn w3-red" type="reset">Ulangi</button></p>
    </form>
  </div>
</div>
</body>
</html> 