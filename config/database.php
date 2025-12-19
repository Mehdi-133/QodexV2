<?php
$servername = "localhost";
$username = "root";
$password = "Mehdi133-";
$dbname = "qodexV2";

try{
$conn = mysqli_connect($servername, $username, $password, $dbname);
}

catch(mysqli_sql_exception){ 
    echo "error <br>";
}

?>