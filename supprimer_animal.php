<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) die("Erreur de connexion : " . mysqli_connect_error());


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;


if ($id > 0) {
    $query = "DELETE FROM animal WHERE ID=$id";
    mysqli_query($conn, $query);
}



exit;
