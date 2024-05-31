
<?php

/**
 * emailVerification
 *
 * This function is used to verify the user entered email is in valid format or not 
 *
 * @param string $emailId - the email ID entered by the user in login or signup form in front end
 * 
 * @return array  - Returns an array ['isCorrect' => bool, 'textPopUp' => string] 
 */
function emailVerification($emailId) {
    $regx = '/^([a-zA-Z0-9\._]{3,50})@([a-zA-Z0-9]{2,20})\.([a-z]{2,20})(\.[a-z]+)*$/';
    $status = ['isCorrect' => true, 'textPopUp' => 'You have entered a valid Email Id'];
    
    if (preg_match($regx, $emailId)) {
        return $status;
    } else {
        $status['isCorrect'] = false;
        $status['textPopUp'] = 'The Email Id you have entered is incorrect';
        return $status;
    }
}


# function for gcaptcha verification
function gcaptchaVerification($token,$secretKey) {
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    $data = array(
        'secret' => $secretKey,
        'response' => $token
    );

    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $response = json_decode($result);


    if ($response->success == false) {
        return false;
    } else if ($response->success == true) {
        return true;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"]) && isset($_POST["token"])) {

    # Check if the captcha is correct
    $email = $_POST["email"];
    if (!emailVerification($email)['isCorrect']) {
        echo "0";
        return;
    }
    # import the secret key for the captcha
    require_once "db_secret.php";

    if (!gcaptchaVerification($_POST["token"], get_secret_key())) {
        echo "0";
        return;
    }

    # Check if the email is already subscribed
    $conn = get_main_DB_connection();
    # set current timezone to GMT
    date_default_timezone_set('GMT');
    $stmt = $conn->prepare("SELECT * FROM subscribers WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $stmt->store_result(); // Store the result to retrieve rows

    if ($stmt->num_rows > 0) { // Check if any rows were returned
        echo "2";
    } 
    else {
        $stmt = $conn->prepare("INSERT INTO `subscribers` (`email`,`date`) VALUES (?,?)");
        $date = date("Y-m-d H:i:s");
        $stmt->bind_param("ss", $email, $date);
        if ($stmt->execute()) {
            echo "1";
        }
        else {
            echo "0";
        }
    }
}
else {
    echo "0";
}

?>