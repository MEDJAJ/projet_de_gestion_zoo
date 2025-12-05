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
<body class="bg-gray-50 min-h-screen flex">

    <main class="flex-1 p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-2 text-emerald-800">Modifier l'animal</h1>
            <p class="text-gray-600">Modifiez les informations de l'animal</p>
        </div>

        <?php if ($message): ?>
            <div class="mb-6 p-4 bg-emerald-100 text-emerald-800 rounded-lg border border-emerald-200">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-emerald-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="font-medium"><?= htmlspecialchars($message) ?></span>
                </div>
            </div>
        <?php endif; ?>

     
        <div class="max-w-lg mx-auto">
            <form method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-xl shadow">
                
          
                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Nom de l'animal</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($animal['Nom']) ?>" required 
                           class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                </div>

          
                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Type alimentaire</label>
                    <select name="type" required 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">-- Sélectionner --</option>
                        <option value="Carnivore" <?= $animal['Type_alimentaire']=="Carnivore" ? 'selected' : '' ?>>Carnivore</option>
                        <option value="Herbivore" <?= $animal['Type_alimentaire']=="Herbivore" ? 'selected' : '' ?>>Herbivore</option>
                        <option value="Omnivore" <?= $animal['Type_alimentaire']=="Omnivore" ? 'selected' : '' ?>>Omnivore</option>
                    </select>
                </div>

             
                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Habitat</label>
                    <select name="habitat" required 
                            class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        <option value="">Sélectionner un habitat</option>
                        <?php
                        $sqlHab = "SELECT IdHab, NomHab FROM habitats";
                        $resHab = mysqli_query($conn, $sqlHab);
                        while ($hab = mysqli_fetch_assoc($resHab)) {
                            $selected = ($animal['IdHab'] == $hab['IdHab']) ? "selected" : "";
                            echo '<option value="'.$hab['IdHab'].'" '.$selected.'>'.$hab['NomHab'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block mb-2 font-semibold text-gray-700">Image</label>
                    <input type="file" name="image" 
                           class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    
                    <?php if($animal['Image']): ?>
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Image actuelle :</p>
                            <img src="uploads/<?= htmlspecialchars($animal['Image']) ?>" 
                                 alt="Image actuelle" 
                                 class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                        </div>
                    <?php endif; ?>
                </div>

          
                <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-4 rounded-lg font-semibold transition">
                    Modifier l'animal
                </button>

            </form>

  
            <div class="mt-6 text-center">
                <a href="liste_animaux.php" class="text-emerald-700 hover:text-emerald-800 font-medium">
                    ← Retour à la liste des animaux
                </a>
            </div>
        </div>

    </main>
</body>
</html>