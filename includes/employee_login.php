<?php 
session_start();

if(isset($_POST['submit'])) {
    include 'dbconn.php';
    $e_uname = mysqli_real_escape_string($conn , $_POST['e_uname']);
    $e_pwd = mysqli_real_escape_string($conn , $_POST['e_pwd']);
    //error handlers
    //Check if inputs are empty
    $sql = "SELECT * FROM employee WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt , $sql)) {
        header("Location: ../index.php?signup=preparedstatementfailed");
        exit();
    }
    else {  
        mysqli_stmt_bind_param($stmt , "s" , $e_uname);
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
                $hashedPwdCheck = password_verify($e_pwd , $row['password_hash']);
                if ($hashedPwdCheck == false) {
                    
                    header("Location: ../index.php?login=WrongPassword");
                    exit();
                }
                elseif ($hashedPwdCheck == true) {
                    $_SESSION['e_uname'] = $row['username'];
                    $_SESSION['e_pwd'] = $row['password_hash'];
                    $_SESSION['e_name'] = $row['name'];
                    $_SESSION['e_surname'] = $row['surname'];
                    $_SESSION['afm'] = $row['afm'];
                    $_SESSION['amka'] = $row['amka'];
                    $_SESSION['iban'] = $row['iban'];
                    $_SESSION['employee_type'] = $row['employee_type'];
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