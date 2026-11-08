<?php
session_start();
$conn = new mysqli("localhost","root","","zelon_shop");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if(isset($_POST['register'])){
    $emri = trim($_POST['emri']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    $errors = [];

    if(empty($emri)) $errors[] = "Emri duhet të plotësohet";
    if(empty($email)) $errors[] = "Email duhet të plotësohet";
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)) $errors[] = "Email nuk është valid";
    if(empty($password)) $errors[] = "Password duhet të plotësohet";
    if($password !== $confirm_password) $errors[] = "Password-at nuk përputhen";

    $stmt = $conn->prepare("SELECT id FROM klientet WHERE email=?");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0) $errors[] = "Ky email është regjistruar tashmë";

    if(!empty($errors)){
        $_SESSION['register_errors'] = $errors;
        header("Location: register.php");
        exit();
    }

    $password_hashed = password_hash($password,PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO klientet (emri,email,password) VALUES (?,?,?)");
    $stmt->bind_param("sss",$emri,$email,$password_hashed);
    $stmt->execute();

    $_SESSION['user_id'] = $conn->insert_id;
    $_SESSION['user_name'] = $emri;
    $_SESSION['user_email'] = $email;
    $_SESSION['success_message'] = "Ju u regjistruat me sukses, mirësevini në ZELÓN $emri!";

    header("Location: dashboard.php");
    exit();
}

if(isset($_SESSION['register_errors'])){
    foreach($_SESSION['register_errors'] as $error){
        echo "alert('".addslashes($error)."');";
    }
    unset($_SESSION['register_errors']);
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <title>ZELÓN Register</title>
    <link rel="stylesheet" href="LogIn-and-Register.css">
</head>

<body>

    <img src="foto/background.png" alt="Background" class="bg-img">

    <div class="container">
        <div class="image-side">
            <img src="foto/zelon.PNG">
        </div>

        <div class="form-side register-side">
            <h2>Regjistrohu</h2>
            <p class="register-intro">Bëhu pjesë e botës së stilit të ZELÓN.</p>

            <form method="post">
                <input type="text" name="emri" placeholder="Emri dhe Mbiemri" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="confirm_password" placeholder="Confirm Password" required>
                <button type="submit" name="register">Regjistrohu</button>
            </form>

            <p>Ke llogari? <a href="login.php">Kyçu këtu</a></p>
        </div>
    </div>
</body>

</html>