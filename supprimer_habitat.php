<?php

$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}


if (!isset($_GET['id'])) {
    die("Aucun habitat sélectionné.");
}

$id = intval($_GET['id']);


$sql = "DELETE FROM habitats WHERE IdHab = $id";

if (mysqli_query($conn, $sql)) {
    header("Location: liste_habitats.php?deleted=1");
    exit;
} else {
    echo "Erreur lors de la suppression : " . mysqli_error($conn);
}
?>
