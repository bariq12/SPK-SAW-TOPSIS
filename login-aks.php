<?php
 include "koneksi.php";

 $user =$_POST['username'];
 $pass = $_POST['password'];

$query = mysqli_query($koneksi,"select * from user where username='$user' and password='$pass'");
$count = mysqli_num_rows($query);
if($count > 0){
    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['status'] = "login";
    header("location:index.php");
} else{
    $_SESSION['gagallogin'] = "Username dan password salah";
    header("location:login.php");
}

?>