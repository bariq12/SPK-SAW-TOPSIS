<?php
  // session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/logincss.css"> 
    <link rel="shortcut icon" href="assets/image/favicon.png" type="image/x-icon">
</head>
<body>
    <div class="container-fluid">
    <form class="mx-auto" action="login-aks.php" method="post">
      <h4 class="text-center"> Form Login</h4>
     
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">username</label>
    <input type="text" class="form-control"  name="username" >
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" class="form-control "  name="password">
  </div>
  <button type="submit" class="btn btn-primary mt-3">login</button>
</form>
    </div>
    <?php  require_once "layout/js.php"?>
</body>
</html>