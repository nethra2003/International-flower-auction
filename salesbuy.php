<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales | Buyers | IFA</title>
    <link rel="stylesheet" href="salesbuy.css">
    <link rel="stylesheet" href="buyernav.css?v=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body style="background-color: #fbe8ec;">
    <div class="box">
        <div class="leftbox">
            <a href="buyerfile.html" class="heading">BUYERS</a>
            <div class="box1">
            <a href="http://localhost/ifa/flowerbuy.php" id="l1">Flowers</a><br><br><br>
            <a href="http://localhost/ifa/auctionbuy.php" id="l2">Auctions</a><br><br><br>
            <a href="http://localhost/ifa/salesbuy.php" id="l3">Sales</a><br><br><br>
            <a href="http://localhost/ifa/biddingbuy.php" id="l4">Bidding</a><br><br><br>
            </div>
            <a href="http://localhost/ifa/home.html" id="logout">LOGOUT</a>
        </div>
        <div class="rightbox">
        <!-- query 8-->
        <!-- Query to get total annual sales per year -->
        <p id="head">Total Annual Sales</p>
        <canvas id="annualSalesChart"width="300" height="300"></canvas>
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

        $query = "SELECT YEAR(a.endTime) AS Year, SUM(a.highestBid) AS TotalSales
                  FROM auctions a
                  WHERE a.STATUS = 'COMPLETED'
                  GROUP BY YEAR(a.endTime)
                  ORDER BY Year";

        $result = $conn->query($query);

        $years = [];
        $totalSales = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $years[] = $row["Year"];
                $totalSales[] = $row["TotalSales"];
            }
        } else {
            echo "0 results";
        }

        $conn->close();
        ?>
        <!-- end query 8 -->

        <br><br>

        <!-- query 4 -->
        <!-- find average starting price of varieties by flower -->
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
        $query = "SELECT f.name AS floweName, AVG(v.startingPrice) AS AverageStartingPrice
        FROM varieties v, flowers f
        WHERE v.flowerId=f.flowerId
        GROUP BY f.flowerId
        ORDER BY AverageStartingPrice DESC";
        
        $result = $conn->query($query);
        echo "<h2 id='head'>Average Starting Price of Varieties by Flower</h2>";
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Flower Name</th><th>Average Starting Price</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["floweName"]. "</td><td>" . $row["AverageStartingPrice"]. "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }
        ?>

        <br><br>

        <!-- display seller details -->
        <h1>Seller Information</h1>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Location</th>
                    <th>Contact Number</th>
                    <th>Email</th>
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
        
        $query = "SELECT fullName, address, contactNumber, semail FROM seller";
        $result = $conn->query($query);
        
        $sellers = [];
        
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["fullName"] . "</td>";
                echo "<td>" . $row["address"] . "</td>";
                echo "<td>" . $row["contactNumber"] . "</td>";
                echo "<td>" . $row["semail"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "0 results";
        }
        
        $conn->close();
        ?>
        </div>
    </div>
    <script>
    const ctx = document.getElementById('annualSalesChart').getContext('2d');
    const annualSalesChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($years); ?>,
            datasets: [{
                label: 'Total Sales',
                data: <?php echo json_encode($totalSales); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
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
                    'rgba(255, 159, 64, 1)',
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
                
            }
        }
    });
    </script>
</body>
</html>
