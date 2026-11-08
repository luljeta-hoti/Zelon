<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "zelon_shop");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if(!isset($_GET['id'])){
    header("Location: raporti.php");
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
    $emri_produktit = trim($_POST['emri_produktit']);
    $cmimi = floatval($_POST['cmimi']);
    $kategoria = trim($_POST['kategoria']);
    $materiali = trim($_POST['materiali']);
    $sasia = intval($_POST['sasia']);
    $pershkrim = !empty($_POST['pershkrim']) ? $_POST['pershkrim'] : NULL;

    $disponueshmeria = (isset($_POST['disponueshmeria']) && $_POST['disponueshmeria'] === "1") ? "1" : "0";
if($disponueshmeria === "1"){
    $available_date = NULL;
} else {
    $available_date = !empty($_POST['available_date']) ? $_POST['available_date'] : NULL;
    if(empty($available_date)){
        echo "<script>alert('Zgjidh datën për produktin jo në stok');</script>";
        exit();
    }
}

    $stmt = $conn->prepare("UPDATE produktet SET emri_produktit=?, materiali=?, kategoria=?, cmimi=?, sasia=?, disponueshmeria=?, available_date=?, pershkrim=? WHERE id=?");
    $stmt->bind_param("sssdisssi", $emri_produktit, $materiali, $kategoria, $cmimi, $sasia, $disponueshmeria, $available_date, $pershkrim, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success_message'] = "Produkti u ndryshua me sukses!";
    header("Location: raporti.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="sq">
<head>
    <meta charset="UTF-8">
    <title>Ndrysho Produkt - ZELÓN</title>
    <link rel="stylesheet" href="dashboard.css">
    <style>
        .edit-form { background-color:#fff; padding:20px; border-radius:10px; margin:20px auto; width:400px; box-shadow:0 0 10px rgba(0,0,0,0.1);}
        .edit-form label { font-weight:bold; margin-top:10px; display:block;}
        .edit-form input, .edit-form select, .edit-form textarea { width:100%; padding:8px; margin-top:5px; border-radius:5px; border:1px solid #ccc;}
        .update-button { margin-top:15px; width:100%; padding:12px; background-color:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;}
        .update-button:hover { background-color:#218838;}
        .radio-vertical label { display:block; margin-top:5px; font-weight:normal;}
    </style>
</head>
<body>

<div class="edit-form">
    <h3>Ndrysho Produkt</h3>
    <form method="post">
        <label>ID (nuk mund të ndryshohet)</label>
        <input type="text" value="<?php echo $product['id']; ?>" readonly>

        <label>Emri i Produktit</label>
        <input type="text" name="emri_produktit" value="<?php echo htmlspecialchars($product['emri_produktit']); ?>" required>

        <label>Çmimi (€)</label>
        <input type="number" name="cmimi" value="<?php echo $product['cmimi']; ?>" step="0.01" required>

        <label>Materiali</label>
        <select name="materiali" required>
            <option value="Lekure natyrale" <?php if($product['materiali']=="Lekure natyrale") echo "selected"; ?>>Lëkurë natyrale</option>
            <option value="Lekure ekologjike" <?php if($product['materiali']=="Lekure ekologjike") echo "selected"; ?>>Lëkurë ekologjike</option>
            <option value="Tekstil premium" <?php if($product['materiali']=="Tekstil premium") echo "selected"; ?>>Tekstil premium</option>
            <option value="Kanavacë e përforcuar" <?php if($product['materiali']=="Kanavacë e përforcuar") echo "selected"; ?>>Kanavacë e përforcuar</option>
            <option value="Poliester cilësor" <?php if($product['materiali']=="Poliester cilësor") echo "selected"; ?>>Poliester cilësor</option>
        </select>

        <label>Kategoria</label>
        <select name="kategoria" required>
            <option value="Cante shpine urbane" <?php if($product['kategoria']=="Cante shpine urbane") echo "selected"; ?>>Çantë shpine urbane</option>
            <option value="Cante elegante dore" <?php if($product['kategoria']=="Cante elegante dore") echo "selected"; ?>>Çantë elegante dore</option>
            <option value="Cante per udhetim" <?php if($product['kategoria']=="Cante per udhetim") echo "selected"; ?>>Çantë për udhëtim</option>
            <option value="Cante biznesi" <?php if($product['kategoria']=="Cante biznesi") echo "selected"; ?>>Çantë biznesi</option>
            <option value="Cante per laptop" <?php if($product['kategoria']=="Cante per laptop") echo "selected"; ?>>Çantë për laptop</option>
            <option value="Cante casual moderne" <?php if($product['kategoria']=="Cante casual moderne") echo "selected"; ?>>Çantë casual moderne</option>
        </select>

        <label>Sasia</label>
        <input type="number" name="sasia" value="<?php echo $product['sasia']; ?>" min="1" required>

        <label>Disponueshmëria</label>
        <div class="radio-vertical">
            <label><input type="radio" name="disponueshmeria" value="1" <?php if($product['disponueshmeria']=="1") echo "checked"; ?> onclick="toggleDate()"> Në stok</label>
            <label><input type="radio" name="disponueshmeria" value="0" <?php if($product['disponueshmeria']=="0") echo "checked"; ?> onclick="toggleDate()"> Jo në stok</label>
        </div>

        <label>Data e disponueshmërisë</label>
        <input type="date" name="available_date" id="available_date" value="<?php echo $product['available_date']; ?>" <?php if($product['disponueshmeria']=="1") echo "disabled"; ?>>

        <label>Përshkrim</label>
        <textarea name="pershkrim"><?php echo htmlspecialchars($product['pershkrim']); ?></textarea>

        <button type="submit" name="update_product" class="update-button">Përditëso Produktin</button>
    </form>
</div>

<script>
function toggleDate() {
    let stock = document.querySelector('input[name="disponueshmeria"]:checked').value;
    let dateField = document.getElementById("available_date");
    if(stock === "0"){ // Jo në stok
        dateField.disabled = false;
        dateField.required = true;
    } else { // Në stok
        dateField.disabled = true;
        dateField.required = false;
        dateField.value = "";
    }
}
</script>

</body>
</html>