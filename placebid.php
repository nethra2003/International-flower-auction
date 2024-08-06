<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Server variables
    $server = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ifa";

    // Create connection
    $conn = new mysqli($server, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
    $auctionId = $_POST['auctionId'];
    $userId = $_POST['userId'];
    $bidAmount = $_POST['bidAmount'];

    // Check the auction status
    $statusQuery = "SELECT STATUS, highestBid FROM auctions WHERE auctionId = ?";
    $statusStmt = $conn->prepare($statusQuery);
    $statusStmt->bind_param("i", $auctionId);
    $statusStmt->execute();
    $statusResult = $statusStmt->get_result();
    
    if ($statusResult->num_rows > 0) {
        $auction = $statusResult->fetch_assoc();
        
        if ($auction['STATUS'] === 'COMPLETED') {
            echo "This auction is already completed. You cannot place a bid.";
        } elseif ($bidAmount > $auction['highestBid']) {
            // Update the highest bid and highest bidder
            $updateSql = "UPDATE auctions SET highestBid = ?, highestBidder = ? WHERE auctionId = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("dii", $bidAmount, $userId, $auctionId);
            $updateStmt->execute();

            echo "Your bid has been placed successfully.";
        } else {
            echo "Your bid must be higher than the current highest bid.";
        }
    } else {
        echo "Auction not found.";
    }

    $statusStmt->close();
    $conn->close();
}
