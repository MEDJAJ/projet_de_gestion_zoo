<?php
include "config.php";



$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {
    $query = "DELETE FROM animal WHERE ID=$id";
    mysqli_query($conn, $query);
}


header("Location: liste_animaux.php");
exit;
