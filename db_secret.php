<?php

/*
 * @Function Name : get_main_DB_connection
 * @Function description : This function is used to get the connection object to the database.
 *
 * @return : Returns the connection object to the database.
 */
function get_main_DB_connection()
{
    $db_host = "localhost";
    $db_user = "u859967223_intbuddy";
    $db_pass = "EnqpK>nP4]";
    $db_name = "u859967223_prelaunch_DB";

    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;

}

/*
 * @Function Name : get_secret_key
 * @Function description : This function is used to get the secret key for the captcha.
 *
 * @return : Returns the secret key for the captcha.
 */
function get_secret_key()
{
    return "6LcO8uwpAAAAABJKsojcdUErRRMbL9aYzxNVImgW";
}



?>