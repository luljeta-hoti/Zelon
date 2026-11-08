<?php
session_start();
$conn = new mysqli("localhost","root","","zelon_shop");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

$errors = [];

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if(empty($email)) $errors[] = "Email duhet të plotësohet";
    if(empty($password)) $errors[] = "Password duhet të plotësohet";
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[] = "Email nuk është valid";

    $stmt = $conn->prepare("SELECT id, emri, password FROM klientet WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 0) $errors[] = "Email nuk ekziston";
    else {
        $user = $result->fetch_assoc();
        if(!password_verify($password,$user['password'])) $errors[] = "Password i gabuar";
    }

    if(empty($errors)){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['emri'];
        $_SESSION['user_email'] = $email;
        $_SESSION['success_message'] = "Ju u kyqet me sukses, mirësevini në ZELÓN ".$user['emri']."!";

        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['login_errors'] = $errors;
        header("Location: login.php");
        exit();
    }
}

if(isset($_SESSION['login_message'])){
    $msg = $_SESSION['login_message'];
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['login_message']);
}

if(isset($_SESSION['login_errors'])){
    foreach($_SESSION['login_errors'] as $err){
        echo "<script>alert('$err');</script>";
    }
    unset($_SESSION['login_errors']);
}

if(isset($_SESSION['login_errors'])){
    foreach($_SESSION['login_errors'] as $error){
        echo "alert('".addslashes($error)."');";
    }
    unset($_SESSION['login_errors']);
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <title>ZELÓN Login</title>
    <link rel="stylesheet" href="LogIn-and-Register.css">
</head>

<body>

    <img src="foto/background.png" alt="Background" class="bg-img">

    <div class="container">
        <div class="image-side">
            <img src="foto/zelon.PNG">
        </div>

        <div class="form-side login-side">
            <h2>Kyçu</h2>
            <p class="login-intro">Hyni dhe zbuloni çantën tuaj.</p>

            <form method="post">
                <input type="email" name="email" placeholder="Email" required class="login-input">
                <input type="password" name="password" placeholder="Password" required class="login-input">
                <button type="submit" name="login" class="button-kyqu">Kyçu</button>
            </form>

            <p class="p-kyqu">Nuk ke llogari? <a href="register.php">Regjistrohu këtu</a></p>
        </div>
    </div>
</body>

</html>