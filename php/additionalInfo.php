<?php
    include 'dbcon.php';
    session_start();
    if(!$con){
        echo("Error bro");
    }
    if(isset($_POST['submit'])){
        echo($_SESSION['username']);
        $id=$_SESSION['id'];
        $firstname=$_POST['firstname'];
        $lastname=$_POST['lastname'];
        $phno=$_POST['phno'];
        $dob=$_POST['dob'];
        $curr_weight = $_POST['currentweight'];
        $height=$_POST['height'];
        $goalweight=$_POST['GoalWeight'];
        $free_time=$_POST['free_time'];
        $sql="insert into addition_info values('$id','$firstname','$lastname','$phno','$dob','$curr_weight','$height','$goalweight','./images/default.png','$free_time','');";
        $res=mysqli_query($con,$sql);
        if(!$res){
            echo(mysqli_error($con));
            echo("Its a problem bro");
        }
        else{
            header("location:../dashboard.php");
        }
    }
    else{

        echo("its not joke brother");
    }
?>