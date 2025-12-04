<?php

$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}


$message = "";
 $imageName = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = mysqli_real_escape_string($conn, $_POST["nom"]);
    $description = mysqli_real_escape_string($conn, $_POST["description"]);
 if (!empty($_FILES["image"]["name"])){
        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $uploadPath = "uploads/" . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
    }
    $sql = "INSERT INTO habitats (NomHab, Description_Hab,Image) VALUES ('$nom', '$description','$imageName')";

    if (mysqli_query($conn, $sql)) {
        $message = " Habitat ajouté avec succès !";
    } else {
        $message = " Erreur SQL : " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Ajouter Habitat</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
<div class="flex h-screen">

 
  


  <main class="flex-1 p-8 overflow-auto">
    <h2 class="text-2xl font-bold mb-6">Ajouter un Habitat</h2>

    <?php if ($message): ?>
      <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg font-medium">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

 
    <form method="POST" class="bg-white p-6 rounded shadow max-w-lg mx-auto" enctype="multipart/form-data">
      <label class="block mb-2 font-semibold">Nom de l'habitat</label>
      <input type="text" name="nom" class="w-full mb-4 p-2 border rounded" placeholder="Ex: Savane" required>

      <label class="block mb-2 font-semibold">Description</label>
      <textarea name="description" class="w-full mb-4 p-2 border rounded" placeholder="Description de l'habitat" rows="4" required></textarea>
      <div class="mb-4">
        <input name="image" type="file" placeholder="choisire une image">
      </div>
      <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700 w-full font-semibold">
        Ajouter
      </button>
    </form>
  </main>
</div>
</body>
</html>
