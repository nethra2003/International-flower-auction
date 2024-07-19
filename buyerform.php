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
    $bemail=$_POST['email'];
    $address=$_POST['address'];
    $paymentid=$_POST['accno'];
    //bank details variables
    $bname=$_POST['bankname'];
    $bbranch=$_POST['bankbranch'];
    $accno=$_POST['accno'];
    $ifsc=$_POST['ifsccode'];
    $uname=$_POST['accname'];
    $role='buyer';

    //define the query

    //insert into buyer table
    $sql2 = $con->prepare("INSERT INTO buyer (fullname, dob, contactnumber, bemail, address, paymentid) VALUES (?, ?, ?, ?, ?, ?)");
    $sql2->bind_param("ssssss", $name, $dob, $contactNumber, $bemail, $address, $paymentid);
    $sql2->execute();
    
    //insert into users table
    //get sellerID
    $bid_q = "SELECT buyerId FROM buyer ORDER BY buyerId DESC LIMIT 1";
    $res = $con->query($bid_q);
    $bid = $res->fetch_assoc()['buyerId'];

    // //query for insert into users table
    $sql3 = $con->prepare("INSERT INTO users (userid, username, email, role) VALUES (?, ?, ?, ?)");
    $sql3->bind_param("isss",$bid, $name, $bemail, $role);
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
