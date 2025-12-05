<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$message = "";
$imageName = "";

function validerChamp($valeur, $regex) {
    return preg_match($regex, $valeur);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = trim($_POST["nom"]);
    $description = trim($_POST["description"]);

    if (!validerChamp($nom, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/")) {
        $message = "Nom d'habitat invalide. Lettres et espaces uniquement.";
    } elseif (!validerChamp($description, "/^[a-zA-Z0-9À-ÿ\s\.,!\?:;'-]{5,200}$/")) {
        $message = "Description invalide. Caractères non autorisés.";
    } else {
        $nom = mysqli_real_escape_string($conn, $nom);
        $description = mysqli_real_escape_string($conn, $description);

        if (!empty($_FILES["image"]["name"])) {
            $imageName = time() . "_" . basename($_FILES["image"]["name"]);
            $uploadPath = "uploads/" . $imageName;
            move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
        }

        $sql = "INSERT INTO habitats (NomHab, Description_Hab, Image)
                VALUES ('$nom', '$description', '$imageName')";

        if (mysqli_query($conn, $sql)) {
            $message = "Habitat ajouté avec succès !";
        } else {
            $message = "Erreur SQL : " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter Habitat - Zoo Éducatif</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

<div class="min-h-screen flex flex-col lg:flex-row">


  <aside class="lg:w-64 bg-indigo-800 text-white hidden lg:flex flex-col">
    <div class="p-6 border-b border-indigo-700">
      <h1 class="text-2xl font-bold">Zoo Éducatif</h1>
      <p class="text-sm text-indigo-300 mt-1">Gestion des habitats</p>
    </div>
    <nav class="flex-1 p-4 space-y-2">
      <a href="liste_animaux.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Liste animaux
      </a>
      <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter animal
      </a>
      <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Liste habitats
      </a>
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">
        Ajouter habitat
      </a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Statistiques
      </a>
    </nav>
    <div class="p-4 border-t border-indigo-700 text-sm text-indigo-300">
      Connecté en tant qu'admin
    </div>
  </aside>


  <header class="lg:hidden bg-indigo-800 text-white p-4">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-bold">Ajouter Habitat</h1>
      <button id="mobileMenuBtn" class="p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
    <nav id="mobileNav" class="hidden mt-4 space-y-2">
      <a href="liste_animaux.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Liste animaux
      </a>
      <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter animal
      </a>
      <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Liste habitats
      </a>
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">
        Ajouter habitat
      </a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Statistiques
      </a>
    </nav>
  </header>


  <main class="flex-1 p-4 md:p-6 lg:p-8 overflow-auto">
    
  
    <div class="mb-6 md:mb-8">
      <h1 class="text-2xl md:text-3xl font-bold mb-2 text-indigo-800">Ajouter un Habitat</h1>
      <p class="text-gray-600">Créez un nouvel habitat pour le zoo</p>
    </div>


    <?php if ($message): ?>
      <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg border border-green-200">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
          </svg>
          <span class="font-medium"><?= htmlspecialchars($message) ?></span>
        </div>
      </div>
    <?php endif; ?>


    <div class="max-w-lg mx-auto">
      <form method="POST" class="bg-white p-4 md:p-6 rounded-xl shadow" enctype="multipart/form-data">
        

        <div class="mb-4 md:mb-6">
          <label class="block mb-2 font-semibold text-gray-700">Nom de l'habitat</label>
          <input type="text" name="nom" 
                 class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                 placeholder="Ex: Savane, Jungle, Désert..." required>
        </div>

        <div class="mb-4 md:mb-6">
          <label class="block mb-2 font-semibold text-gray-700">Description</label>
          <textarea name="description" rows="4"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Description de l'habitat..." required></textarea>
        </div>


        <div class="mb-6 md:mb-8">
          <label class="block mb-2 font-semibold text-gray-700">Image</label>
          <input name="image" type="file"
                 class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>

        <button type="submit" 
                class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-semibold transition">
          Ajouter l'habitat
        </button>

      </form>

      <div class="mt-6 md:mt-8 text-sm text-gray-500">
        <p>✓ Tous les champs sont obligatoires</p>
        <p class="mt-1">✓ Formats d'image acceptés : JPG, PNG, GIF</p>
        <p class="mt-1">✓ Taille maximale : 5MB</p>
      </div>
    </div>

  </main>
</div>

<script>
  document.getElementById('mobileMenuBtn').addEventListener('click', function() {
    const mobileNav = document.getElementById('mobileNav');
    mobileNav.classList.toggle('hidden');
  });
</script>

</body>
</html>