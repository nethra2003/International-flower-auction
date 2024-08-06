<?php
if($_SERVER['REQUEST_METHOD']=='POST') {
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
    //get form inputs
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    //query to chech user credentials
    $sql = $con->prepare("SELECT username, email, role FROM users WHERE username = ? AND email = ? AND role = ?");
    $sql->bind_param("sss", $fullname, $email, $role);
    $sql->execute();
    $result = $sql->get_result();

    if($result->num_rows == 1) {
        //redirect based on role
        if($role == 'buyer'){
            header("Location: http://localhost/ifa/buyerfile.html");
        }
        elseif($role == 'seller'){
            header("Location: http://localhost/ifa/sellerfile.html");
        }
        else{
            echo "Invalid role.";
        }
    } 
    else{
        echo "Invalid username or password";
    }

    //close connection
    $sql->close();
    $con->close();
}
