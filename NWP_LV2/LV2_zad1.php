<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2_zad1</title>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $databasename = "diplomski_radovi";

    $backup_directory = "E:/backups/$databasename";//promijeniti po potrebi
    if (!is_dir($backup_directory)) {
        if (!mkdir($backup_directory, 0777, true)) {
            die("Can't create directory");
        }
    }

    $conn = new mysqli($servername, $username, $password, $databasename);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SHOW TABLES;";

    $result = $conn->query($sql);
    if($result){
        while($table_names = $result->fetch_assoc()){
            foreach($table_names as $table_name){
                $sql_table = "SELECT * FROM $table_name;";
                $result_table = $conn->query($sql_table);
                if($result_table){
                    $rows = [];
                    while($row = $result_table->fetch_assoc()){
                        array_push($rows, $row);
                    }
                    
                    //napravi .txt file i zapiši sve što si dobio za svaku tablicu zasebno
                    if($fp = fopen("$backup_directory/$table_name.txt", "w")){
                        foreach($rows as $row){
                            $insert_statement = "INSERT INTO `$table_name` (";

                            $row_keys = array_keys($row);
                            foreach($row_keys as $row_key){
                                $insert_statement .= "`$row_key`";
                                if($row_key !== $row_keys[array_key_last($row_keys)]){//ako je zadnji argument, ne treba zarez razmak
                                    $insert_statement .= ", ";
                                }
                            }
                            $insert_statement .= ") VALUES (";
                            foreach($row_keys as $row_key){
                                $insert_statement .= "'$row[$row_key]'";
                                if($row_key !== $row_keys[array_key_last($row_keys)]){
                                    $insert_statement .= ", ";
                                }
                            }
                            $insert_statement .= ");";
                            echo "<p>Backed up with following statements:<br>";
                            echo $insert_statement . "</p>";

                            fwrite($fp, $insert_statement);
                            fwrite($fp, "\r\n");
                        }
                        fclose($fp);
                        echo "<h3>Table saved!</h3>";
                    } else {
                        echo "File cannot be opened!<br>";
                    }
                } else {
                    echo "No rows found";
                }
            }
        }
        echo "<h3>Database saved!</h3>";
    } else {
        echo "No tables found.";
    }
    $conn->close();
    ?>
</body>

</html>