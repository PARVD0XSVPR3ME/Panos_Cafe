<?php

if (isset($_POST['submit'])) {
    include_once 'dbconn.php';  
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    //error handlers
    //Check for empty fields
    //h regexp anagnwrizei stathera ths patras kai kinita dhladh ths morfhs 261 045 4361 kai 697 452 6589 (opws kai 261-045-4361 kai 
    // 2610454361 ,omoiws gia kinita)
    if( !preg_match('/^69[0-9][- ]?[0-9]{3}[- ]?[0-9]{4}$/', $phone) && !preg_match('/^261[- ]?[0-9]{3}[- ]?[0-9]{4}$/', $phone)) {
        header("Location: ../index.php?signup=$phone");
        exit();
    } 
    else {
        $sql = "SELECT * FROM users WHERE email_address =?;";
        //create prepared statement
        $stmt = mysqli_stmt_init($conn);
        //prepare the prepared statement
        if (!mysqli_stmt_prepare($stmt , $sql)) {
            header("Location: ../index.php?signup=preparedstatementfailed");
            exit();
        }
        else {
            //bind parameters to the placeholder
            mysqli_stmt_bind_param($stmt , "s" , $email);
            //run parameters inside database
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            $resultCheck = mysqli_num_rows($result);
            if ($resultCheck > 0 ) {
                header("Location: ../index.php?signup=usertaken");
                exit();
            }
            else {
                //trim whitespaces from phone 
                $phone = preg_replace('/(\s+)|(-)+/', '', $phone);
                //HASHING THE PASSWORD
                $hashed_pwd = password_hash($password , PASSWORD_DEFAULT);

                
                //INSERT USER IN DATABASE
                $sql = "INSERT INTO users (email_address , password_hash , phone ) VALUES (? , ? , ?);";
               
                $stmt = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($stmt , $sql)) {
                    header("Location: ../index.php?signup=preparedstatem2entfailed");
                    exit();
                }
                else {
                    mysqli_stmt_bind_param($stmt , "sss" , $email , $hashed_pwd , $phone);
                    mysqli_stmt_execute($stmt);
                }
                header("Location: ../index.php?signup=success");
                exit();
            }

        }
        
    }       
}
else {
    header("Location: ../index.php");
    exit();
}