<?php
session_start();
include('server/connection.php');

// Eğer kullanıcı oturum açmamışsa, login.php'ye yönlendir
if(!isset($_SESSION['logged_in'])){
  header('location: login.php');
  exit;
}
if(isset($_GET['logout'])){
  if(isset($_SESSION['logged_in'])){
    unset($_SESSION['logged_in']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_name']);
    header('location: login.php');
    exit;
  }
}

if(isset($_POST['change_password'])){
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirmPassword'];
  $user_email = $_SESSION['user_email'];
  
  // sifreler uyusmuyorsa
  if($password !== $confirmPassword){
    header('location: account.php?error=Sifreler Uyusmuyor');
  //6 karakter
  }else if(strlen($password) < 6){
    header('location: account.php?error=Sifreniz En Az 6 Karakter Olmalı');
  //hata yoksa
  }else{
    $stmt = $conn->prepare("UPDATE users SET user_password = ? WHERE user_email = ?");
    $stmt->bind_param('ss', md5($password), $user_email);
    if($stmt->execute()){
      header('location: account.php?message=Sifre Degistirme Basarili');
    }else{
      header('location: account.php?error=Sifre Degistirirken Bir Hata Oldu');
    }
  }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <title>The Diary</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="main.css">
    <script src="https://kit.fontawesome.com/cbfbaecab8.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="scrolltop.js"></script>
  </head>
  <body id="top">
    <!---Navbar-->
    <nav class="navbar navbar-expand-md nav-color">
      <div class="container">
        <a href="index.php" class="navbar-brand"> The Diary</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#myNav" aria-controls="navbar-collapse" aria-expanded="false" aria-label="toggle navigation">
          <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="myNav">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a href="index.php" class="nav-link">Anasayfa</a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">Hesap</a>
            </li>
            <li class="nav-item dropdown dropdown-sm">
              <a href="#" class="nav-link dropdown-toggle" id="archiveDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Arşivler</a>
              <div class="dropdown-menu" aria-labelledby="archiveDropdown">
                <h5 class="dropdown-header">2024</h5>
                <a href="#" class="dropdown-item">Ocak</a>
                <a href="#" class="dropdown-item">Şubat</a>
                <a href="mart.html" class="dropdown-item">Mart</a>
                <a href="#" class="dropdown-item">Nisan</a>
                <a href="mayıs.html" class="dropdown-item">Mayıs</a>
              </div>
            </li>
          </ul>
          <form class="form-inline mt-2 mt-md-0">
            <input type="text" name="search" class="form-control mr-sm-2" placeholder="Ara" aria-label="search">
            <button type="submit" class="btn btn-search my-2 my-sm-0">
              <i class="fas fa-search" aria-hidden="true"></i>
              <span class="sr-only">Ara</span>
            </button>
          </form>
        </div>
      </div> 
    </nav>
    <!--navEND-->
    <!--Body-->
    <!--Account-->
    <section class="my-5 py-5">
      <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12 ">
          <p class="text-center" style="color: green"><?php if(isset($_GET['register_success'])) {echo $_GET['register_success'];} ?></p>
          <p class="text-center" style="color: green"><?php if(isset($_GET['login_success'])) {echo $_GET['login_success'];} ?></p>
          <h3 class="font-weight-bold">Hesap Bilgileri</h3>
          <hr class="mx-auto">
          <div class="account-info">
            <p>İsim: <span><?php if(isset($_SESSION['user_name'])){ echo $_SESSION['user_name'];} ?></span></p>
            <p>Email: <span><?php if(isset($_SESSION['user_email'])){ echo $_SESSION['user_email'];} ?></span></p>
            <p><a href="account.php?logout=1" id="logout-btn">Çıkış</a></p> 
          </div>
        </div>
        <div class="col-lg-1 d-none d-lg-block">
          <div class="vertical-line"></div>
        </div>
        <div class="col-lg-5 col-md-12 col-sm-12">
          <form id="account-form" method="POST" action="account.php">
            <p class="text-center" style="color: red"><?php if(isset($_GET['error'])) {echo $_GET['error'];} ?></p>
            <p class="text-center" style="color: green"><?php if(isset($_GET['message'])) {echo $_GET['message'];} ?></p>
            <h3>Şifre Değiştirme</h3>
            <hr class="mx-auto">
            <div class="form-group">
              <label>Şifre</label>
              <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
              <label>Şifrenizi Doğrulayın</label>
              <input type="password" class="form-control" id="account-password-confirm" name="confirmPassword" placeholder="Password" required>
            </div>
            <div class="form-group">
              <input type="submit" value="Şifrenizi Değiştirin" name="change_password" class="btn" id="change-pass-btn">
            </div>
          </form>
        </div>
      </div>
    </section>
    <!--Footer-->
    <footer class="small back">
      <div class="container py-3 py-sm-5">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-3">
            <h6 class="text-white"> Kısa Linkler</h6>
            <ul class="list-unstyled">
              <li><a href="#top" onclick="scrollToTop();return false">En Üste Dönün</a></li>
              <li><a href="cateYemek.html">Yemek</a></li>
              <li><a href="cateTekn.html">Teknoloji</a></li>
              <li><a href="cateSey.html">Seyahat</a></li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <h6> Bizi Takip Edin</h6>
            <ul class="list-unstyled">
              <li><i class="fab fa-facebook"></i><a href="https://www.facebook.com/" target="_blank">facebook</a></li>
              <li><i class="fab fa-twitter"></i><a href="https://x.com/home?lang=tr" target="_blank">Twitter</a></li>
              <li><i class="fab fa-instagram"></i><a href="https://www.instagram.com/" target="_blank">instagram</a></li>
              <li><i class="fab fa-youtube"></i><a href="https://www.youtube.com/?hl=tr&gl=TR" target="_blank">Youtube</a></li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <h6> Bilgi</h6>
            <ul class="list-unstyled">
              <li><a href="">Bu Site Bir Proje Ödevidir.</a></li>
            </ul>
          </div>
          <div class="col-12 col-sm-6 col-md-3">
            <h6> İletişim Bilgileri</h6>
            <address class="text-white">
              <strong class="text-white">The Diary</strong><br>
              Address<br>
              <abbr title="Telephone"></abbr><a href="" class="text-white">+55 123 456 789</a><br>
              <abbr title="Mail"><a href="" class="text-white">test@example.com</a></abbr>
            </address>
          </div>
        </div>
        <ul class="list-inline text-center">
          <li class="list-inline-item text-white">&copy; 2024 The Diary</li>
          <li class="list-inline-item text-white">Her Hakkı Saklıdır</li>
          <li class="list-inline-item text-white">Koşullar & Gizlilik Politikamız</li>
        </ul>
      </div>
    </footer>
    <!--FooterEND-->
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>
