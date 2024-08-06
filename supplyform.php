<?php
$insert = false;
if(isset($_POST['name'])) {
    //server variables
    $server="localhost";
    $username="root";
    $database="ifa";
    $password= "";
    
    //establish the connection
    $con = new mysqli($server, $username, $password, $database);
    
    //check if connection fails
    if($con->connect_error){
        die("Connection to this database failed due to". $con->connect_error);
    }

    //if connection is successful
    //personal details variables
    $name=$_POST['name'];
    $dob=$_POST['dob'];
    $contactNumber=$_POST['contactnumber'];
    $semail=$_POST['email'];
    $address=$_POST['address'];
    $company=$_POST['location'];
    $paymentid=$_POST['accno'];
    //bank details variables
    $bname=$_POST['bankname'];
    $bbranch=$_POST['bankbranch'];
    $accno=$_POST['accno'];
    $ifsc=$_POST['ifsccode'];
    $uname=$_POST['accname'];
    $role='seller';

    //define the query

    //insert into seller table
    $sql2 = $con->prepare("INSERT INTO seller (fullname, dob, contactnumber, semail, address, company, paymentid) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $sql2->bind_param("sssssss", $name, $dob, $contactNumber, $semail, $address, $company, $paymentid);
    $sql2->execute();
    
    //insert into users table
    //get sellerID
    $sid_q = "SELECT sellerId FROM seller ORDER BY sellerId DESC LIMIT 1";
    $res = $con->query($sid_q);
    $sid = $res->fetch_assoc()['sellerId'];

    // //query for insert into users table
    $sql3 = $con->prepare("INSERT INTO users (userid, username, email, role) VALUES (?, ?, ?, ?)");
    $sql3->bind_param("isss",$sid, $name, $semail, $role);
    $sql3->execute();

    //get userid from users table
    $uid_q = "SELECT userId FROM users ORDER BY userId DESC LIMIT 1";
    $res2 = $con->query($uid_q);
    $uid = $res2->fetch_assoc()['userId'];

    //insert into transactions table
    $sql1 = $con->prepare("INSERT INTO transactions (userid, bname, bbranch, accno, ifsc, uname) VALUES (?, ?, ?, ?, ?, ?)");
    $sql1->bind_param("isssss", $uid, $bname, $bbranch, $accno, $ifsc, $uname);
    $sql1->execute();

    //check insertion
    if($sql2->affected_rows > 0){
        echo "<p id='thanks'>Thanks for registering</p>";
        $insert=true;
    }
    else{
        echo "ERROR". $sql2->error;
    }
    //close connection
    $sql1->close();
    $sql2->close();
    $sql3->close();
    $con->close();
}
