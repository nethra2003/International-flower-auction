<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bid on Auction | IFA</title>
    <link rel="stylesheet" href="biddingbuy.css?v=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>
<body>
    <div class="container">
        <a href="buyerfile.html" id="back"><i class="fa-solid fa-arrow-left"></i></a>
        <h1>Place a Bid</h1>
        <form action="http://localhost/ifa/placebid.php" method="POST">
            <label for="auctionId">Auction ID:</label>
            <input type="text" id="auctionId" name="auctionId" required><br><br>
            
            <label for="userId">User ID:</label>
            <input type="text" id="userId" name="userId" required><br><br>
            
            <label for="bidAmount">Bid Amount:</label>
            <input type="text" id="bidAmount" name="bidAmount" required><br><br>
            
            <input type="submit" value="Place Bid">
        </form>

        <h2>Latest Bid Amount</h2>
        <form method="POST">
            <label for="auctionId">Auction ID:</label>
            <input type="text" id="auctionId" name="auctionId" required>
            <input type="submit" value="View Latest Bid">
        </form> 

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['auctionId'])) {
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

            $auctionId = $_POST['auctionId'];

            // Get the latest bid amount for the given auction
            $query = "SELECT MAX(bidAmount) AS latestBid FROM bids WHERE auctionId = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $auctionId);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                echo "<p>Latest Bid Amount: $" . $row['latestBid'] . "</p>";
            } else {
                echo "<p>No bids found for this auction.</p>";
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
        ?>
    </div>
</body>
</html>
