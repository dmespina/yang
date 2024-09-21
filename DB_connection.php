<?php  

$sName = "localhost";
$uName = "u244315997_Yahng";
$pass  = "Yahng2130";
$db_name = "u244315997_sms_db";

try {
    $conn = new PDO("mysql:host=$sName;dbname=$db_name", $uName, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: ". $e->getMessage();
    exit;
}

?>
