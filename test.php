<?php

// test if database connection is working

require_once "db_secret.php";

$conn = get_main_DB_connection();
if ($conn->connect_error) {
    echo "Connection failed: " . $conn->connect_error;
}
else {
    echo "Connection successful";
}
date_default_timezone_set('GMT');
echo "5";
 $stmt = $conn->prepare("SELECT * FROM subscribers WHERE email = ?");
 echo "4";
 $email="rishi";
 $stmt->bind_param("s", $email);
 echo "3";
 $stmt->execute();
 echo "2";
$stmt->store_result(); // Store the result to retrieve rows
echo "1";
    if ($stmt->num_rows > 0) { // Check if any rows were returned
        echo "9";
    } 

echo "1";

?>