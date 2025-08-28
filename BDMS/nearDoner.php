<?php
include 'conn.php';

$recipient_lat = $_POST['recipient_lat'];
$recipient_lon = $_POST['recipient_lon'];
$radius = 10; // Radius in kilometers

$sql = "SELECT *, 
        (6371 * acos(cos(radians($recipient_lat)) 
        * cos(radians(latitude)) 
        * cos(radians(longitude) - radians($recipient_lon)) 
        + sin(radians($recipient_lat)) 
        * sin(radians(latitude)))) AS distance 
        FROM donor_details 
        HAVING distance < $radius 
        ORDER BY distance 
        LIMIT 5"; // Adjust LIMIT as needed to get top closest donors

$result = mysqli_query($conn, $sql);

if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "Donor: " . $row['fullname'] . " - Distance: " . round($row['distance'], 2) . " km<br>";
    }
} else {
    echo "No nearby donors found within the specified radius.";
}

mysqli_close($conn);
?>
