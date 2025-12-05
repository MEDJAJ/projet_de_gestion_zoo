<?php





if (!isset($_GET['id'])) {
    die("Aucun habitat sélectionné.");
}

$id = intval($_GET['id']);


$sql = "SELECT * FROM habitats WHERE IdHab = $id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
    die("Habitat introuvable.");
}

$habitat = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = $habitat['Image']; 


    if (!empty($_FILES["image"]["name"])) {
        $image = time() . "_" . basename($_FILES["image"]["name"]);
        $target = "uploads/" . $image;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target);
    }

 
    $update = "
        UPDATE habitats 
        SET NomHab='$nom', Description_Hab='$description', Image='$image'
        WHERE IdHab=$id
    ";

    if (mysqli_query($conn, $update)) {
        header("Location: liste_habitats.php?success=1");
        exit;
    } else {
        echo "Erreur : " . mysqli_error($conn);
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Habitat</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-6">
    <h2 class="text-2xl font-bold mb-4">Modifier Habitat</h2>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">

        <div>
            <label class="font-medium">Nom :</label>
            <input type="text" name="nom" required 
                   value="<?= htmlspecialchars($habitat['NomHab']) ?>"
                   class="w-full border p-2 rounded">
        </div>

        <div>
            <label class="font-medium">Description :</label>
            <textarea name="description" required class="w-full border p-2 rounded"
                      rows="4"><?= htmlspecialchars($habitat['Description_Hab']) ?></textarea>
        </div>

       <label class="block mb-2 font-semibold">Image</label>
            <input type="file" name="image" class="w-full p-2 border rounded-lg mb-3">
            <?php if($habitat['Image']): ?>
                <img src="uploads/<?= htmlspecialchars($habitat['Image']) ?>" alt="Image actuelle" class="w-32 h-32 object-cover rounded mb-4">
            <?php endif; ?>

        <button class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700">
            Enregistrer les modifications
        </button>

        <a href="liste_habitats.php" class="ml-2 text-gray-600 hover:underline">
            Annuler
        </a>
    </form>
</div>

</body>
</html>
