<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST"){

    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $type = mysqli_real_escape_string($conn, $_POST["type"]);
    $habitat = mysqli_real_escape_string($conn, $_POST["habitat"]);

    $imageName = "";
    if (!empty($_FILES["image"]["name"])){
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $uploadPath = "uploads/" . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
    }

    $sql = "
        INSERT INTO animal (Nom, Type_alimentaire, IdHab, Image)
        VALUES ('$nom', '$type', '$habitat', '$imageName')
    ";

    if (mysqli_query($conn, $sql)) {
        $message = " Animal ajouté avec succès !";
    } else {
        $message = " Erreur SQL : " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un animal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">

   
    <aside class="w-64 bg-green-900 text-white flex flex-col shadow-lg">
        <div class="text-center py-6 border-b border-green-800">
            <h1 class="text-2xl font-bold tracking-wide">Zoo Educatif</h1>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-3">
            <a href="liste_animaux.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Liste animaux</a>
            <a href="ajouter_animal.php" class="block py-2 px-4 rounded hover:bg-green-700 bg-green-700 font-medium shadow">Ajouter animal</a>
            <a href="liste_habitats.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Liste habitats</a>
            <a href="ajouter_habitat.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter habitat</a>
            <a href="statistiques.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Statistiques</a>
        </nav>
    </aside>

  
    <main class="flex-1 flex items-center justify-center bg-gray-100 p-8 overflow-auto">

        <div class="max-w-lg w-full bg-white p-6 rounded-xl shadow-lg">

            <h1 class="text-3xl font-bold text-green-700 mb-4 text-center">Ajouter un animal</h1>

        
            <?php if (!empty($message)): ?>
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg font-medium">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

         
            <form method="POST" enctype="multipart/form-data">

                <label class="block mb-1 font-semibold">Nom de l'animal</label>
                <input type="text" name="nom" required
                       class="w-full p-2 border rounded-lg mb-3">

                <label class="block mb-1 font-semibold">Type alimentaire</label>
                <select name="type" required class="w-full p-2 border rounded-lg mb-3">
                    <option value="">-- Sélectionner --</option>
                    <option value="Carnivore">Carnivore</option>
                    <option value="Herbivore">Herbivore</option>
                    <option value="Omnivore">Omnivore</option>
                </select>

                <label class="block mb-1 font-semibold">Habitat</label>
                <select name="habitat" required class="w-full p-2 border rounded-lg mb-3">
                    <option value="">-- Sélectionner --</option>
                    <option value="10">Savane</option>
                    <option value="2">Jungle</option>
                    <option value="3">Désert</option>
                    <option value="4">Océan</option>
                </select>

                <label class="block mb-1 font-semibold">Image</label>
                <input type="file" name="image"
                       class="w-full p-2 border rounded-lg mb-4">

                <button type="submit"
                        class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 font-semibold">
                    Ajouter l'animal
                </button>

            </form>
        </div>

    </main>

</div>
</body>
</html>
