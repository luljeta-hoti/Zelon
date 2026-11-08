<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost","root","","zelon_shop");
if($conn->connect_error) die("Connection failed: ".$conn->connect_error);

if(isset($_GET['delete_id'])){
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM produktet WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Produkti u fshi me sukses!'); window.location='raporti.php';</script>";
}



$result = $conn->query("SELECT * FROM produktet ORDER BY id ASC");
$products = [];
while($row = $result->fetch_assoc()){
    $products[] = $row;
}
?>

<!DOCTYPE html>
<html lang="sq">

<head>
    <meta charset="UTF-8">
    <title>ZELÓN - Raporti i Produkteve</title>
    <link rel="stylesheet" href="Dashboard.css?v=2">
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
            <h3>Lista e Produkteve të Regjistruara</h3>
            <p>Këtu mund të shikoni të gjithë produktet, të i editoni ose të i fshini sipas ID-së. ID nuk mund të ndryshohet.</p>
        </div>

        <div class="product-grid">
            <table class="product-table">
                <thead>
                    <tr>
                        <th class="col-id">ID</th>
                        <th class="col-name">Emri</th>
                        <th class="col-material">Materiali</th>
                        <th class="col-category">Kategoria</th>
                        <th class="col-price">Çmimi (€)</th>
                        <th class="col-qty">Sasia</th>
                        <th class="col-availability">Gjendja</th>
                        <th class="col-date">Data e furnizimit</th>
                        <th class="col-desc">Përshkrim</th>
                        <th class="col-actions">Veprime</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($products as $p): ?>
                    <tr>
                        <td class="col-id"><?php echo $p['id']; ?></td>
                        <td class="col-name"><?php echo htmlspecialchars($p['emri_produktit']); ?></td>
                        <td class="col-material"><?php echo htmlspecialchars($p['materiali']); ?></td>
                        <td class="col-category"><?php echo htmlspecialchars($p['kategoria']); ?></td>
                        <td class="col-price"><?php echo $p['cmimi']; ?></td>
                        <td class="col-qty"><?php echo $p['sasia']; ?></td>
                        <td class="col-availability"><?php echo (trim($p['disponueshmeria']) == "1") ? 'Në stok' : 'Jo në stok'; ?></td>
                        <td class="col-date"><?php echo $p['available_date']; ?></td>
                        <td class="col-desc"><?php echo htmlspecialchars($p['pershkrim']); ?></td>
                        <td class="col-actions">
                            <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-edit">NDRYSHO</a>
                            <a href="raporti.php?delete_id=<?php echo $p['id']; ?>" class="btn btn-delete">FSHI</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

    <script>
        function confirmLogout() {
            if (confirm("A jeni i sigurt që doni të shkyqeni?")) {
                window.location.href = "login.php";
            }
        }

        document.addEventListener("DOMContentLoaded", function() {

            const editLinks = document.querySelectorAll(".btn-edit");
            editLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    alert("Po ridrejtoheni për të edituar produktin.");
                    window.location.href = this.href;
                });
            });

            const deleteLinks = document.querySelectorAll(".btn-delete");
            deleteLinks.forEach(link => {
                link.addEventListener("click", function(e) {
                    e.preventDefault();
                    if (confirm("A jeni i sigurt që doni ta fshini këtë produkt?")) {
                        window.location.href = this.href;
                    }
                });
            });

        });
    </script>
</body>

</html>