<?php
$XML_path = "./LV2.xml";
$xml = simplexml_load_file($XML_path);
//foreach($xml as $record){
    /*object(SimpleXMLElement)#3 (7) { ["id"]=> string(1) "1" ["ime"]=> string(5) "Diane" ["prezime"]=> string(7) "Gilbert"
         ["email"]=> string(21) "dgilbert0@cbsnews.com" ["spol"]=> string(6) "Female" ["slika"]=>
          string(67) "https://robohash.org/numquamrepudiandaequia.png?size=50x50&set=set1" ["zivotopis"]=> string(76) "et eros 
          vestibulum ac est lacinia nisi venenatis tristique fusce congue diam" }*/
    //var_dump($record); echo "<br><br>";
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LV2 zadatak 3</title>
    <style>
        table {
            border:3px solid black;
            width: 500px;
        }
        th {
            text-align: left;
        }
        th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
<?php
foreach($xml as $record){
    echo "<table>
    <tr>
        <th rowspan='5'>
            <img src='" . $record->slika . "'>
        </th>
        <th>
            <p>Ime:" . $record->ime . "</p>
        </th>
    </tr>
    <tr>
        <td>
            <p>Prezime: " . $record->prezime . "</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Email: " . $record->email . "</p>
        </td>
    </tr>
    <tr>
        <td>
            <p>Životopis: " . $record->zivotopis . "</p>
        </td>
    </tr>
</table>";
}

?>
</body>
</html>