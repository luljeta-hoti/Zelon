<?php
session_start();

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","zelon_shop");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if(!isset($_GET['id'])){
    echo "ID mungon!";
    exit();
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM produktet WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if(!$product){
    echo "Produkti nuk u gjet!";
    exit();
}

if(isset($_POST['update_product'])){
    $emri = trim($_POST['emri_produktit']);
    $materiali = trim($_POST['materiali']);
    $kategoria = trim($_POST['kategoria']);
    $cmimi = trim($_POST['cmimi']);
    $sasia = trim($_POST['sasia']);
    $disponueshmeria = trim($_POST['disponueshmeria']);
    $available_date = !empty($_POST['available_date']) ? $_POST['available_date'] : NULL;
    $pershkrim = trim($_POST['pershkrim']);

    if(empty($emri)){
        echo "<script>alert('Shkruaj emrin e produktit');</script>";
    } elseif(empty($materiali)){
        echo "<script>alert('Zgjidh materialin');</script>";
    } elseif(empty($kategoria)){
        echo "<script>alert('Zgjidh kategorinë');</script>";
    } elseif($cmimi <= 0){
        echo "<script>alert('Çmimi duhet të jetë mbi 0');</script>";
    } elseif($sasia < 1){
        echo "<script>alert('Sasia duhet të jetë mbi 0');</script>";
    } elseif($disponueshmeria == "Out of Stock" && empty($available_date)){
        echo "<script>alert('Ju duhet të zgjidhni një datë për produktet që nuk janë në stok');</script>";
    } else {
        $stmt = $conn->prepare("UPDATE produktet SET emri_produktit=?, materiali=?, kategoria=?, cmimi=?, sasia=?, disponueshmeria=?, available_date=?, pershkrim=? WHERE id=?");
        $stmt->bind_param("sssdiissi", $emri, $materiali, $kategoria, $cmimi, $sasia, $disponueshmeria, $available_date, $pershkrim, $id);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Produkti u editua me sukses!'); window.location='raporti.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <title>ZELÓN - Ndrysho produktin</title>
    <link rel="stylesheet" href="Dashboard.css">
</head>

<body>

    <div class="bg-image"></div>

    <div class="sidebar">
        <div class="brand">
            <h1>ZELÓN</h1>
            <p class="welcome">Mirësevjen, <span class="user-name"><?php echo $_SESSION['user_name']; ?></span>!</p>
        </div>

        <nav class="sidebar-menu">
            <ul>
                <li><a href="dashboard.php">Paneli Kryesor</a></li>
                <li><a href="raporti.php" class="active">Raporti i Produkteve</a></li>
                <li><a href="profili.php">Profili</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="#">Kontakti</a></li>
            </ul>
        </nav>

        <button class="logout-btn" onclick="confirmLogout()">Shkyçu</button>
    </div>

    <div class="main-content">

        <div class="form-intro">
            <h3>Edito produktin tuaj</h3>
            <p>Ndryshoni të dhënat e produktit dhe ruani përditësimet.</p>
        </div>

        <form method="POST">

            <h2 class="form-title">Edit Produktin</h2>

            <div class="row">
                <input type="text" name="emri_produktit" value="<?php echo htmlspecialchars($product['emri_produktit']); ?>" required>

                <select name="materiali" required>
                    <option value="Lekure natyrale" <?php if($product['materiali']=="Lekure natyrale") echo "selected"; ?>>Lëkurë natyrale</option>
                    <option value="Lekure ekologjike" <?php if($product['materiali']=="Lekure ekologjike") echo "selected"; ?>>Lëkurë ekologjike</option>
                    <option value="Tekstil premium" <?php if($product['materiali']=="Tekstil premium") echo "selected"; ?>>Tekstil premium</option>
                    <option value="Kanavacë e përforcuar" <?php if($product['materiali']=="Kanavacë e përforcuar") echo "selected"; ?>>Kanavacë e përforcuar</option>
                    <option value="Poliester cilësor" <?php if($product['materiali']=="Poliester cilësor") echo "selected"; ?>>Poliester cilësor</option>
                </select>
            </div>

            <div class="row">
                <select name="kategoria" required>
                    <option value="Cante shpine urbane" <?php if($product['kategoria']=="Cante shpine urbane") echo "selected"; ?>>Çantë shpine urbane</option>
                    <option value="Cante elegante dore" <?php if($product['kategoria']=="Cante elegante dore") echo "selected"; ?>>Çantë elegante dore</option>
                    <option value="Cante per udhetim" <?php if($product['kategoria']=="Cante per udhetim") echo "selected"; ?>>Çantë për udhëtim</option>
                    <option value="Cante biznesi" <?php if($product['kategoria']=="Cante biznesi") echo "selected"; ?>>Çantë biznesi</option>
                    <option value="Cante per laptop" <?php if($product['kategoria']=="Cante per laptop") echo "selected"; ?>>Çantë për laptop</option>
                    <option value="Cante casual moderne" <?php if($product['kategoria']=="Cante casual moderne") echo "selected"; ?>>Çantë casual moderne</option>
                </select>

                <input type="number" name="cmimi" value="<?php echo $product['cmimi']; ?>" step="0.01" required>
            </div>

            <div class="range-row">
                <input type="range" name="sasia" min="1" max="20" value="<?php echo $product['sasia']; ?>" oninput="this.nextElementSibling.value = this.value">
                <output><?php echo $product['sasia']; ?></output>
            </div>

            <div class="bottom-row">

                <div class="stock-field">
                    <label>Disponueshmëria</label>
                    <div class="radio-vertical">
                        <label>
                            <input type="radio" name="disponueshmeria" value="1" <?php if($product['disponueshmeria']=="1") echo "checked"; ?> onclick="toggleDate()"> Në stok
                        </label>

                        <label>
                            <input type="radio" name="disponueshmeria" value="0" <?php if($product['disponueshmeria']=="0") echo "checked"; ?> onclick="toggleDate()"> Jo në stok
                        </label>
                    </div>
                </div>

                <div class="date-field">
                    <label>Data e disponueshmërisë</label>
                    <input type="date" name="available_date" id="available_date" value="<?php echo $product['available_date']; ?>">
                </div>

            </div>

            <textarea name="pershkrim"><?php echo htmlspecialchars($product['pershkrim']); ?></textarea>

            <button type="submit" name="update_product">Ruaj Ndryshimet</button>

        </form>

    </div>

    <script>
        function toggleDate() {
            let stock = document.querySelector('input[name="disponueshmeria"]:checked').value;
            let dateField = document.getElementById("available_date");

            if (stock === "Out of Stock") {
                dateField.disabled = false;
            } else {
                dateField.disabled = true;
                dateField.value = "";
            }
        }

        function confirmLogout() {
            if (confirm("A jeni i sigurt që doni të shkyqeni?")) {
                window.location.href = "login.php";
            }
        }

        window.onload = toggleDate;
    </script>

</body>
</html>