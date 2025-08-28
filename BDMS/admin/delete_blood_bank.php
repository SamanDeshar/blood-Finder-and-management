<?php
// delete_blood_bank.php
include 'conn.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM blood_bank WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect back to the main page after deletion
        header("Location: blood_edit.php"); // replace 'main_page.php' with your main page name
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "Invalid ID.";
}
?>
