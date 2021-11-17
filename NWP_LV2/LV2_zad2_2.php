<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "nwp_lv";

include "./encrypt.php";

$conn = new mysqli($servername, $username, $password, $databasename);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM lv2_zad2;";
$result = $conn->query($sql);
$ids = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $picture_data = openssl_decrypt($row["picture_data"], $cipher, $encryption_key, $options, $encryption_iv);
        $id = [$row["id"], $row["name"], $row["extension"], $picture_data];
        array_push($ids, $id);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2 zadatak 2-2</title>
</head>

<body>
    <?php
    foreach ($ids as $id) {
        echo "<p>$id[1]</p>";
        echo '<img src="data:image/' . $id[2] . ';base64,' . $id[3] . '">';
        echo "<a download='$id[1]' href='data:image/$id[2];base64,$id[3]'>Click to download picture</a>";
        echo "<br>";
    }
    ?>
</body>

</html>