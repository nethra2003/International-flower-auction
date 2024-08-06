<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ifa";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$auctionDetails = null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['getDetails'])) {
        $auctionId = $_POST['auctionId'];

        // Fetch auction details
        $query = "SELECT a.auctionId, v.varietyName, f.name AS flowerName, a.startTime, a.endTime, a.highestBid, a.highestBidder, a.STATUS
                  FROM auctions a
                  JOIN varieties v ON a.varietyId = v.varietyId
                  JOIN flowers f ON v.flowerId = f.flowerId
                  WHERE a.auctionId = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $auctionId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $auctionDetails = $result->fetch_assoc();
        }

        // Close the statement
        $stmt->close();
    } elseif (isset($_POST['confirmSale'])) {
        $auctionId = $_POST['auctionId'];

        // Mark the auction as completed
        $updateSql = "UPDATE auctions SET STATUS = 'COMPLETED' WHERE auctionId = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $auctionId);
        $stmt->execute();

        // Close the statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales | Suppliers | IFA</title>
    <link rel="stylesheet" href="salessel.css?v=1.0">
    <link rel="stylesheet" href="sellernav.css?v=1.0">
    <style>
        h2 {
            font-size: 3rem;
            color: #590d22;
        }
        #auctionId {
            font-size: 1.5rem;
            color: #800f2f;
            height: 30px;
            width: 50%;
        }
    </style>
</head>
<body style="background-color: #fbe8ec;">
    <div class="box">
        <div class="leftbox">
            <a href="sellerfile.html" class="heading">SUPPLIERS</a>
            <div class="box1">
                <a href="http://localhost/ifa/flowersel.php" id="l1">Flowers</a><br><br><br>
                <a href="http://localhost/ifa/auctionsel.php" id="l2">Auctions</a><br><br><br>
                <a href="http://localhost/ifa/salessel.php" id="l3">Sales</a><br><br><br>
                <a href="http://localhost/ifa/confirmsale.php" id="l4">Confirm bid</a><br><br><br>
            </div>
            <a href="http://localhost/ifa/home.html" id="logout">LOGOUT</a>
        </div>
        <div class="rightbox">
            <h2>Confirm Sale</h2>
            <form action="http://localhost/ifa/confirmsale.php" method="POST">
                <label for="auctionId" id="auctionId">Auction ID:</label>
                <input type="text" id="auctionId" name="auctionId" required><br><br>
                <input type="submit" name="getDetails" value="Get Details">
            </form>

            <?php
            if ($auctionDetails) {
                echo "<h2>Auction Details</h2>";
                echo "<p><strong>Auction ID:</strong> " . $auctionDetails['auctionId'] . "</p>";
                echo "<p><strong>Variety:</strong> " . $auctionDetails['varietyName'] . "</p>";
                echo "<p><strong>Flower Name:</strong> " . $auctionDetails['flowerName'] . "</p>";
                echo "<p><strong>Start Time:</strong> " . $auctionDetails['starttime'] . "</p>";
                echo "<p><strong>End Time:</strong> " . $auctionDetails['endtime'] . "</p>";
                echo "<p><strong>Highest Bid:</strong> $" . $auctionDetails['highestBid'] . "</p>";
                echo "<p><strong>Highest Bidder:</strong> " . $auctionDetails['highestBidder'] . "</p>";
                echo "<p><strong>Status:</strong> " . $auctionDetails['STATUS'] . "</p>";

                // Confirm sale form
                echo '<form action="http://localhost/ifa/confirmsale.php" method="POST">';
                echo '<input type="hidden" name="auctionId" value="' . $auctionDetails['auctionId'] . '">';
                echo '<input type="submit" name="confirmSale" value="Confirm Sale">';
                echo '</form>';
            }
            ?>
        </div>
    </div>
</body>
</html>
