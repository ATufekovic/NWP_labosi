<?php
/*Napraviti skriptu koja će omogućiti upload dokumenta ili slike (pdf, jpeg,png) i kriptiranje 
dokumenta pomoću biblioteke OpenSSL. Na serveru treba biti uploadan samo kriptirani dokument.
Napraviti skriptu koja će dohvatiti sve kriptirane dokumente, dekriptirati ih i prikazati
linkove za preuzimanje dokumenata.
*/
include "./encrypt.php";
if (isset($_POST['submit'])) {
    echo "File sent...<br>";

    $file = basename($_FILES["uploaded_file"]["name"]);
    $is_valid = true;
    $file_extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $file_name = pathinfo($file, PATHINFO_FILENAME);
    echo $file_name . "<br>";
    $check = getimagesize($_FILES["uploaded_file"]["tmp_name"]);
    if ($check !== false) {
        echo "File is a picture of type: " . $check["mime"] . " -> $file_extension<br>";
        $is_valid = true;
    } else {
        echo "File is not a valid picture.<br>";
        $is_valid = false;
    }

    if ($_FILES["uploaded_file"]["size"] > 500000) {
        echo "Your picture is too large...<br>";
        $is_valid = false;
    }

    if ($file_extension != "jpg" && $file_extension != "jpeg" && $file_extension != "png") {
        echo "Sorry, only jpg, jpeg and png files are allowed.<br>";
        $is_valid = false;
    }

    if (!$is_valid) {
        echo "Your file was not uploaded...<br>";
    } else {
        $fp_content = base64_encode(file_get_contents($_FILES["uploaded_file"]["tmp_name"]));

        $data = openssl_encrypt($fp_content, $cipher, $encryption_key, $options, $encryption_iv);
        $test = openssl_decrypt($data, $cipher, $encryption_key, $options, $encryption_iv);
        if(!strcmp($data, $test)){
            echo "ENCRYPT PROBLEM";
            return;
        }

        $servername = "localhost";
        $username = "root";
        $password = "";
        $databasename = "nwp_lv";

        $conn = new mysqli($servername, $username, $password, $databasename);
        if ($conn->connect_error) {
            die("Connection failed to database, picture not saved: " . $conn->connect_error);
        }
        $sql = "INSERT INTO `lv2_zad2`(`name`, `extension`, `picture_data`) VALUES ('$file_name','$file_extension','$data')";

        $result = $conn->query($sql);
        if ($result) {
            echo "File encrypted successfully.<br>";
        } else {
            echo "File was not saved...<br>";
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2 zadatak 2-1</title>
</head>

<body>

    <form name="upload" action="<?= htmlentities($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
        <input type="file" name="uploaded_file" id="uploaded_file">
        <button type="submit" name="submit">SUBMIT FILE</button>
    </form>
    <?php
    if(isset($fp_content))
        echo '<img src="data:image/' . $file_extension . ';base64,' . $fp_content . '" alt ="' . $file_name . '">';
    ?>
</body>

</html>