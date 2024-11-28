<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
  session_start();
}

$authonticated = false;

if (isset($_SESSION["email"])) {
  $authonticated = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login and registration form </title>
    <link rel="stylesheet" href="public/bootstrap.min.css">
    <link rel="icon" href="assets/logo.png">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary" class="w-100 p-3" style="background-color: #A6B1A2;">


  <div class="container" >
    <a class="navbar-brand" href="index.php">
    <img src="assets/logo.png" width="30" height="30" class="d-inline-block align-top">  store
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent" >
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link active text-dark" href="login.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Link</a>
        </li>
        
      </ul>

      <?php
if ($authonticated ){
  ?>

      <ul class="navbar-nav text-dark">
      <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Admin
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="profile.php">profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
<?php
    }else{ ?>
      <ul class="navbar-nav text-dark">

            <li><a class="btn btn-secondary me-2" href="register.php">register</a></li>
            <li><a class="btn btn-secondary" href="login.php">login</a></li>
          
      </ul>
    <?php }?>

     
    </div>
  </div>
</nav>


