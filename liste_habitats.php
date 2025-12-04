<?php

$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}


$sql = "SELECT * FROM habitats ORDER BY IdHab ASC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoo Educatif - Liste Habitats</title>
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
      <a href="ajouter_animal.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter animal</a>
      <a href="liste_habitats.php" class="block py-2 px-4 rounded hover:bg-green-700 bg-green-700 font-medium shadow">Liste habitats</a>
      <a href="ajouter_habitat.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter habitat</a>
      <a href="statistiques.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Statistiques</a>
    </nav>
  </aside>


  <main class="flex-1 p-8 overflow-auto">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Liste des Habitats</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="bg-white rounded-2xl shadow-lg p-4 flex flex-col items-center transition-transform transform hover:-translate-y-1 hover:shadow-2xl">
           
            <img src="uploads/<?= htmlspecialchars($row['Image'] ?? 'default.jpg') ?>" 
                 alt="<?= htmlspecialchars($row['NomHab']) ?>" 
                 class="w-full h-40 object-cover rounded-xl mb-4">
            <h3 class="text-xl font-bold mb-2"><?= htmlspecialchars($row['NomHab']) ?></h3>
            <p class="text-gray-700 mb-4 text-center"><?= htmlspecialchars($row['Description_Hab']) ?></p>
            <div class="flex space-x-2">
              <a href="modifier_habitat.php?id=<?= $row['IdHab'] ?>" 
                 class="bg-blue-600 text-white py-1 px-3 rounded hover:bg-blue-700">Modifier</a>
              <a href="supprimer_habitat.php?id=<?= $row['IdHab'] ?>" 
                 onclick="return confirm('Voulez-vous vraiment supprimer cet habitat ?');"
                 class="bg-red-600 text-white py-1 px-3 rounded hover:bg-red-700">Supprimer</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-gray-600 col-span-3 text-center">Aucun habitat trouvé.</p>
      <?php endif; ?>
    </div>
  </main>
</div>
</body>
</html>
