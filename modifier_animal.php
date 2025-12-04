<?php

$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";


$sql = "SELECT * FROM animal WHERE ID=$id";
$result = mysqli_query($conn, $sql);
$animal = mysqli_fetch_assoc($result);


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $habitat = mysqli_real_escape_string($conn, $_POST["habitat"]);

    
    $imageName = $animal['Image'];
    if (!empty($_FILES["image"]["name"])) {
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], "uploads/" . $imageName);
    }

    $update = "
        UPDATE animal 
        SET Nom='$nom', Type_alimentaire='$type', IdHab='$habitat', Image='$imageName'
        WHERE ID=$id
    ";

    if (mysqli_query($conn, $update)) {
        $message = "Animal modifié avec succès !";
       
        $animal['Nom'] = $nom;
        $animal['Type_alimentaire'] = $type;
        $animal['IdHab'] = $habitat;
        $animal['Image'] = $imageName;
    } else {
        $message = "Erreur SQL : " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un animal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex">


    <aside class="w-64 bg-green-900 text-white flex flex-col shadow-lg">
        <div class="text-center py-6 border-b border-green-800">
            <h1 class="text-2xl font-bold tracking-wide">Zoo Educatif</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-3">
            <a href="liste_animaux.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Liste animaux</a>
            <a href="ajouter_animal.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter animal</a>
            <a href="liste_habitats.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Liste habitats</a>
            <a href="ajouter_habitat.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter habitat</a>
            <a href="statistiques.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Statistiques</a>
        </nav>
    </aside>


    <main class="flex-1 p-8">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Modifier l'animal</h2>

        <?php if ($message): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg font-medium">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow-lg max-w-lg mx-auto">
            <label class="block mb-2 font-semibold">Nom de l'animal</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($animal['Nom']) ?>" required class="w-full p-2 border rounded-lg mb-3">

            <label class="block mb-2 font-semibold">Type alimentaire</label>
            <select name="type" required class="w-full p-2 border rounded-lg mb-3">
                <option value="">-- Sélectionner --</option>
                <option value="Carnivore" <?= $animal['Type_alimentaire']=="Carnivore" ? 'selected' : '' ?>>Carnivore</option>
                <option value="Herbivore" <?= $animal['Type_alimentaire']=="Herbivore" ? 'selected' : '' ?>>Herbivore</option>
                <option value="Omnivore" <?= $animal['Type_alimentaire']=="Omnivore" ? 'selected' : '' ?>>Omnivore</option>
            </select>

          <label class="block mb-2 font-semibold">Habitat</label>
<select name="habitat" required class="w-full p-2 border rounded-lg mb-3">
    <option value=""> Sélectionner un habitat </option>

    <?php
    $sqlHab = "SELECT IdHab, NomHab FROM habitats";
    $resHab = mysqli_query($conn, $sqlHab);

    while ($hab = mysqli_fetch_assoc($resHab)) {
        $selected = ($animal['IdHab'] == $hab['IdHab']) ? "selected" : "";
        echo '<option value="'.$hab['IdHab'].'" '.$selected.'>'.$hab['NomHab'].'</option>';
    }
    ?>
</select>

            <label class="block mb-2 font-semibold">Image</label>
            <input type="file" name="image" class="w-full p-2 border rounded-lg mb-3">
            <?php if($animal['Image']): ?>
                <img src="uploads/<?= htmlspecialchars($animal['Image']) ?>" alt="Image actuelle" class="w-32 h-32 object-cover rounded mb-4">
            <?php endif; ?>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 font-semibold transition">Modifier l'animal</button>
        </form>
    </main>
</body>
</html>
