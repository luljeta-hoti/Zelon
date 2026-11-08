<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","zelon_shop");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);


if(isset($_SESSION['success_message'])){
    $msg = $_SESSION['success_message'];
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['success_message']);
}

if(isset($_SESSION['login_message'])){
    $msg = $_SESSION['login_message'];
    echo "<script>alert('$msg');</script>";
    unset($_SESSION['login_message']);
}


if(isset($_POST['add_product'])){

    $emri_produktit = trim($_POST['emri_produktit']);
    $materiali = trim($_POST['materiali']);
    $kategoria = trim($_POST['kategoria']);
    $cmimi = trim($_POST['cmimi']);
    $sasia = intval($_POST['sasia']);

    
    $disponueshmeria = ($_POST['disponueshmeria'] == "1") ? "1" : "0";

   
    $available_date = !empty($_POST['available_date']) ? $_POST['available_date'] : NULL;

    if($disponueshmeria == "1"){
        $available_date = NULL; 
    }

    $pershkrim = !empty($_POST['pershkrim']) ? $_POST['pershkrim'] : NULL;

    
    if(empty($emri_produktit)){
        echo "<script>alert('Shkruaj emrin e produktit');</script>";
    } elseif(empty($materiali)){
        echo "<script>alert('Zgjidh materialin');</script>";
    } elseif(empty($kategoria)){
        echo "<script>alert('Zgjidh kategorinë');</script>";
    } elseif($cmimi <= 0){
        echo "<script>alert('Çmimi duhet të jetë mbi 0');</script>";
    } elseif($sasia < 1){
        echo "<script>alert('Sasia duhet të jetë mbi 0');</script>";
    } else {

        $stmt = $conn->prepare("INSERT INTO produktet 
        (emri_produktit, materiali, kategoria, cmimi, sasia, disponueshmeria, available_date, pershkrim) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        
        $stmt->bind_param("sssdisss",
            $emri_produktit,
            $materiali,
            $kategoria,
            $cmimi,
            $sasia,
            $disponueshmeria,
            $available_date,
            $pershkrim
        );

        $stmt->execute();
        $stmt->close();

        $_SESSION['success_message'] = "Produkti u regjistrua me sukses!";
        header("Location: dashboard.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <title>ZELÓN - Dashboard</title>
    <link rel="stylesheet" href="Dashboard.css">
</head>

<body>

    <div class="bg-image"></div>

    <div class="sidebar">
        <div class="brand">
            <h1>ZELÓN</h1>
            <p class="welcome">Mirësevjen,
                <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>!
            </p>
        </div>

        <nav class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php" class="active">Paneli Kryesor</a></li>
                <li><a href="raporti.php">Raporti i Produkteve</a></li>
                <li><a href="profili.php">Profili</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="#">Kontakti</a></li>
            </ul>
        </nav>

        <button class="logout-btn" onclick="confirmLogout()">Shkyçu</button>
    </div>

    <div class="main-content">

        <div class="form-intro">
            <h3>Regjistro produktin e ri për koleksionin tuaj</h3>
        </div>

        <form action="dashboard.php" method="POST">

            <h2 class="form-title">Regjistro Produktin</h2>

            <div class="row">
                <input type="text" name="emri_produktit" placeholder="Emri i produktit" required>

                <select name="materiali" required>
                    <option value="">Materiali</option>
                    <option value="Lekure natyrale">Lëkurë natyrale</option>
                    <option value="Lekure ekologjike">Lëkurë ekologjike</option>
                    <option value="Tekstil premium">Tekstil premium</option>
                    <option value="Kanavacë e përforcuar">Kanavacë e përforcuar</option>
                    <option value="Poliester cilësor">Poliester cilësor</option>
                    <option value="Material sintetik premium">Material sintetik premium</option>
                </select>
            </div>

            <div class="row">
                <select name="kategoria" required>
                    <option value="">Kategoria</option>
                    <option value="Cante shpine urbane">Çantë shpine urbane</option>
                    <option value="Cante elegante dore">Çantë elegante dore</option>
                    <option value="Cante per udhetim">Çantë për udhëtim</option>
                    <option value="Cante biznesi">Çantë biznesi</option>
                    <option value="Cante per laptop">Çantë për laptop</option>
                    <option value="Cante casual moderne">Çantë casual moderne</option>
                </select>
                <input type="number" name="cmimi" placeholder="Çmimi (€)" min="0" step="0.01" required>
            </div>

            <div class="range-row">
                <input type="range" name="sasia" min="1" max="20" value="1" oninput="this.nextElementSibling.value = this.value">
                <output>1</output>
            </div>

            <div class="bottom-row">
                <div class="stock-field">
                    <label>Gjendja</label>
                    <div class="radio-vertical">
                        <label>
                            <input type="radio" name="disponueshmeria" value="1" checked onclick="toggleDate()"> Në stok
                        </label>
                        <label>
                            <input type="radio" name="disponueshmeria" value="0" onclick="toggleDate()"> Jo në stok
                        </label>
                    </div>
                </div>

                <div class="date-field">
                    <label>Data e furnizimit</label>
                    <input type="date" name="available_date" id="available_date" disabled>
                </div>
            </div>

            <textarea name="pershkrim" placeholder="Përshkrimi i produktit"></textarea>

            <button type="submit" name="add_product">Shto Produktin</button>

        </form>

    </div>

    <script>
        function toggleDate() {
            let stock = document.querySelector('input[name="disponueshmeria"]:checked').value;
            let dateField = document.getElementById("available_date");

            if (stock === "0") {
                dateField.disabled = false;
                dateField.required = true;
            } else {
                dateField.disabled = true;
                dateField.required = false;
                dateField.value = "";
            }
        }

        function confirmLogout() {
            if (confirm("A jeni i sigurt që doni të shkyqeni?")) {
                window.location.href = "login.php";
            }
        }
    </script>

</body>
</html>