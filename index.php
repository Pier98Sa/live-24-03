<?php

define("DB_SERVERNAME", 'localhost');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", 'root');
define("DB_NAME", 'university_db');

$conn = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($conn && $conn->connect_error){
    echo "Connection failed:" . $conn->connect_error;
}

$nomeDaCercare = $_GET['nome'];
/* non sicura perchè si potrebbe avere un DATA LEAK, //////DA USARE SOLO SE NON CI SONO VARIABILI NELLA QUERY/////////
$query = "SELECT * FROM `students` WHERE `students`.`name` = '{$nomeDaCercare}';";

/SELECT * FROM `students` WHERE `name` = '' 1 OR 1 OR name=''; in questo modo avrei un possibile DATA LEAK
$result = $conn->query($query);

if ($result && $result->num_rows > 0){
    while ($currentRow = $result->fetch_assoc()){
        echo"Students:" . $currentRow['name']. ' - '. $currentRow['surname']. ' - '. $currentRow['email']. '<br>';
    }
}else if ($result){
    echo "0 result";
}else{
    echo "Query Error";
}
*/

//IN QUESTO MODO NON AVRO IL DATA LEAK, perchè i dati di input vengono sanificati
// prepare and bind
$stmt = $conn->prepare("SELECT * FROM `students` WHERE name = (?) ");

$stmt->bind_param("s",$nomeDaCercare );
// set parameters and execute
$stmt->execute();

$result=$stmt->get_result();

if ($result && $result->num_rows > 0){
    while ($currentRow = $result->fetch_assoc()){
        echo"Students:" . $currentRow['name']. ' - '. $currentRow['surname']. ' - '. $currentRow['email']. '<br>';
    }
}else if ($result){
    echo "0 result";
}else{
    echo "Query Error";
}


$stmt->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form method="GET">
        <input name='nome' type="text" placeholder="nome dello studente">
        <button type="submit">CERCA</button>
    </form>
</body>
</html>