<?php

$password = 2143;

$hashed_pwd = password_hash($password , PASSWORD_DEFAULT);
echo 'Pass:' ;
echo$password ;
echo "------------------>";
echo 'hash: ';
echo $hashed_pwd;