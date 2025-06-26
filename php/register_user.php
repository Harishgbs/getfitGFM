<?php
    include 'dbcon.php';
    session_start();
    if(!$con){
        echo("Error bro");
    }
    if(isset($_POST['submit'])){
        $username=$_POST['username'];
        $roll_no=$_POST['rollno'];
        $email=$_POST['email'];
        $pswd = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $sql="insert into students values('$username','$roll_no','$email','$pswd');";
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