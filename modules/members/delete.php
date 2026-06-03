<?php
require_once '../../config/database.php';

if(isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    $query = "DELETE FROM library_members WHERE id = $id";

    if(mysqli_query($conn, $query)) {
        header("Location: index.php?deleted=1");
    } else {
        header("Location: index.php?error=1");
    }
} else {
    header("Location: index.php");
}
?>