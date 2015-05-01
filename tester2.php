<html>
        <head>
        <title>Retrieve data from database </title>
        </head>
        <body>
<?php
$servername = "54.229.149.217";
$username = "john";
$password = "password";
$dbname = "infoshow";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
echo("BALLS!!!!!");
}


$sql = "SELECT * from exhibit";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["Exhibit_ID"]. "<br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
        </html>

