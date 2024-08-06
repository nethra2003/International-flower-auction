<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flowers | Suppliers | IFA</title>
    <link rel="stylesheet" href="flowersel.css">
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
        <div class="flowerinfo">
            <a href="flowerinfo.html" class="flower">Check out the flowers in our inventory -Click here</a>
        </div>
            <!-- query 3 -->
            <!-- find flowers with low inventory -->
            <p id="head">Flowers with Low Inventory</p>
            <table>
                <tr>
                    <th>Flower Name</th>
                    <th>Variety Name</th>
                    <th>Quantity</th>
                </tr>
            
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

                $query = "SELECT f.name AS flowerName, v.varietyName, i.quantity
                          FROM inventory i
                          JOIN varieties v ON i.varietyId = v.varietyId
                          JOIN flowers f ON v.flowerId = f.flowerId
                          WHERE i.quantity < 10
                          ORDER BY i.quantity ASC";

                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['flowerName']}</td>
                                <td>{$row['varietyName']}</td>
                                <td>{$row['quantity']}</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No results found</td></tr>";
                }

                $conn->close();
            ?>
            </table>
            <!-- end query 3 -->

            <br><br>

            <!-- query 6 -->
            <!-- total inventory value per flower -->
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
            $query = "SELECT f.name AS FlowerName, SUM(i.quantity*v.startingPrice) AS TotalInventoryValue
            FROM inventory i
            JOIN varieties v ON i.varietyId=v.varietyId
            JOIN flowers f ON v.flowerId=f.flowerId
            GROUP BY f.flowerId
            ORDER BY TotalInventoryValue DESC";
            
            $result = $conn->query($query);
            echo "<p id='head'>Total Inventory Value Per Flower</p>";
            if ($result->num_rows > 0) {
                echo "<table><tr><th>Flower Name</th><th>Total Inventory Value</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["FlowerName"]. "</td><td>" . $row["TotalInventoryValue"]. "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "0 results";
            }
            ?>

            <!-- end of query 6  -->

            <br><br>

            <!-- display varieties table -->
    <p id="head">Varieties</p>
    <table>
        <thead>
            <tr>
                <th>Variety ID</th>
                <th>flowerId</th>
                <th>Variety Name</th>
                <th>Starting Price</th>
                <th>Listing Date</th>
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
    
    $sql = "SELECT * FROM varieties";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row["varietyId"] . "</td>";
            echo "<td>" . $row["flowerId"] . "</td>";
            echo "<td>" . $row["varietyName"] . "</td>";
            echo "<td>" . $row["startingPrice"] . "</td>";
            echo "<td>" . $row["listingDate"] . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No results found</td></tr>";
    }
    $conn->close();
    ?>
    </table>

        </div>
    </div>
    <script>
        const ctx = document.getElementById('popularFlowerChart').getContext('2d');
        const popularFlowerChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($varietyNames); ?>,
                datasets: [{
                    label: 'Auction Count',
                    data: <?php echo json_encode($auctionCounts); ?>,
                    backgroundColor: 'rgb(255, 179, 193, 0.6)',
                    borderColor: '#800f2f',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
