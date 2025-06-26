<?php
    include 'dbcon.php';
    session_start();
    if(!$con){
        echo("Error bro");
    }
    if(isset($_POST['submit'])){
        $username=$_POST['username'];
        $faculty_id=$_POST['faculty_id'];
        $email=$_POST['email'];
        $pswd = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql="insert into faculty values('$username','$faculty_id','$email','$pswd');";
        $res=mysqli_query($con,$sql);
        if(!$res){
            echo("Its a problem bro");
        }
        else{
            header("location:../login.html");
        }
    }
    else{
        echo("its not joke brother");
    }
?>