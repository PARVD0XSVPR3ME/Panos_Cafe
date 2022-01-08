<?php 
session_start();

if(isset($_POST['submit'])) {
    include 'dbconn.php';
    $uemail = mysqli_real_escape_string($conn , $_POST['uemail']);
    $pwd = mysqli_real_escape_string($conn , $_POST['pwd']);
    //error handlers
    //Check if inputs are empty
    $sql = "SELECT * FROM users WHERE email_address = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt , $sql)) {
        header("Location: ../index.php?signup=preparedstatementfailed");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt , "s" , $uemail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $resultCheck = mysqli_num_rows($result);
        if ($resultCheck < 1) {
            header("Location: ../index.php?login=NotFound");
            exit();
        }
        else {
            if ($row = mysqli_fetch_assoc($result)) {
                //dehashing the password
                $hashedPwdCheck = password_verify($pwd , $row['password_hash']);
                if ($hashedPwdCheck == false) {
                    
                    header("Location: ../index.php?login=WrongPassword");
                    exit();
                }
                elseif ($hashedPwdCheck == true) {
                    $_SESSION['u_email'] = $row['email_address'];
                    $_SESSION['u_phone'] = $row['phone'];
                    header("Location: ../index.php?login=Success");
                    exit();
                }
            }
        }
    }
} 
else {
    header("Location: ../index.php?login=error");
    exit();
}