<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales | Suppliers | IFA</title>
    <link rel="stylesheet" href="salessel.css?v=1.0">
    <link rel="stylesheet" href="sellernav.css?v=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        <!-- query 2 -->
        <!-- retrieve total sales amount per seller -->
        <p id="head">Total Sales Amount Per Seller</p>
        <canvas id="salesPerSellerChart" width="500" height="400"></canvas>
        <?php
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
        $query = "SELECT s.fullname, s.company, SUM(a.highestBid) AS totalsales
                  FROM auctions a, varieties v, flowers f, seller s
                  WHERE a.varietyId=v.varietyId AND v.flowerId=f.flowerId AND f.sellerId=s.sellerId AND a.STATUS='COMPLETED'
                  GROUP BY s.sellerId
                  ORDER BY totalsales DESC";
        
        $result = $conn->query($query);
        
        $sellerNames = [];
        $totalSales = [];
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $sellerNames[] = $row["fullname"] . " (" . $row["company"] . ")";
                $totalSales[] = $row["totalsales"];
            }
        } else {
            echo "0 results";
        }
        ?>

        <!-- end query 2 -->

        <br><br>

        <!-- query 8 -->
        <!-- get total annual sales per year -->
        <p id="head">Total Annual Sales Per Year</p>
        <?php
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
        $query = "SELECT YEAR(a.endtime) AS Year, SUM(a.highestBid) AS TotalSales
        FROM auctions a
        WHERE a.STATUS='COMPLETED'
        GROUP BY YEAR(a.endtime)
        ORDER BY Year";
        
        $result = $conn->query($query);
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Year</th><th>Total Sales</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["Year"]. "</td><td>" . $row["TotalSales"]. "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }
        ?>

        <!-- end query 8 -->

        <br><br>

        <!-- display auctions table -->
        <p id="head">Auctions</p>
    <table>
        <thead>
            <tr>
                <th>Auction ID</th>
                <th>Variety ID</th>
                <th>Start time</th>
                <th>End time</th>
                <th>Highest Bid</th>
                <th>Highest Bidder</th>
                <th>Status</th>
            </tr>
        </thead>
    <?php
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
    
    $sql = "SELECT * FROM auctions";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["auctionId"] . "</td>";
            echo "<td>" . $row["varietyId"] . "</td>";
            echo "<td>" . $row["starttime"] . "</td>";
            echo "<td>" . $row["endtime"] . "</td>";
            echo "<td>" . $row["highestBid"] . "</td>";
            echo "<td>" . $row["highestBidder"] . "</td>";
            echo "<td>" . $row["STATUS"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No results found</td></tr>";
    }
    $conn->close();
    ?>
    </table>
        </div>
    </div>
    <script>
    const ctx2 = document.getElementById('salesPerSellerChart').getContext('2d');
    const salesPerSellerChart = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($sellerNames); ?>,
            datasets: [{
                label: 'Total Sales',
                data: <?php echo json_encode($totalSales); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    
                }
            }
        }
    });
    </script>
</body>
</html>
