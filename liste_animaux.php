<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : "";
$habitat = isset($_GET['habitat']) ? intval($_GET['habitat']) : 0;

$sql = "
    SELECT a.ID, a.Nom, a.Type_alimentaire, a.Image, h.NomHab, h.IdHab
    FROM animal a
    LEFT JOIN habitats h ON a.IdHab = h.IdHab
    WHERE 1=1
";

if (!empty($type)) {
    $sql .= " AND a.Type_alimentaire = '$type' ";
}

if (!empty($habitat)) {
    $sql .= " AND h.IdHab = $habitat ";
}

$sql .= " ORDER BY a.ID ASC";

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoo Educatif - Liste Animaux</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex flex-col lg:flex-row">


  <aside class="lg:w-64 bg-indigo-800 text-white hidden lg:flex flex-col">
    <div class="p-6 border-b border-indigo-700">
      <h1 class="text-2xl font-bold">Zoo Éducatif</h1>
      <p class="text-sm text-indigo-300 mt-1">Gestion des animaux</p>
    </div>
    
    
    
    <div class="p-4 border-t border-indigo-700 text-sm text-indigo-300">
      Connecté en tant qu'admin
    </div>
  </aside>


  <header class="lg:hidden bg-indigo-800 text-white p-4">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-bold">Zoo Éducatif</h1>
      <button id="mobileMenuBtn" class="p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
    
    <nav id="mobileNav" class="hidden mt-4 space-y-2">
      <a href="liste_animaux.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">Liste animaux</a>
      <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-600 transition">Ajouter animal</a>
      <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-600 transition">Liste habitats</a>
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-600 transition">Ajouter habitat</a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-600 transition">Statistiques</a>
    </nav>
  </header>


  <main class="flex-1 p-4 lg:p-8">

    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-800 mb-2">Liste des Animaux</h1>
      <p class="text-gray-600">Gérez et consultez tous les animaux du zoo</p>
    </div>


    <div class="mb-8 bg-white rounded-xl shadow p-6">
      <h2 class="text-xl font-semibold text-gray-800 mb-4">Filtrer les animaux</h2>

      <form method="GET" class="space-y-4 md:space-y-0 md:flex md:space-x-4">

      
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Type alimentaire</label>
          <select name="type" class="w-full p-3 border border-gray-300 rounded-lg">
            <option value="">Tous les types</option>
            <option value="Carnivore" <?= ($type=="Carnivore" ? "selected" : "") ?>>Carnivore</option>
            <option value="Herbivore" <?= ($type=="Herbivore" ? "selected" : "") ?>>Herbivore</option>
            <option value="Omnivore"  <?= ($type=="Omnivore" ? "selected" : "") ?>>Omnivore</option>
          </select>
        </div>

        
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-2">Habitat</label>
          <?php 
          $sql_h = "SELECT IdHab, NomHab FROM habitats";
          $res_h = mysqli_query($conn, $sql_h); 
          ?>
          <select name="habitat" class="w-full p-3 border border-gray-300 rounded-lg">
            <option value="">Tous les habitats</option>

            <?php while ($h = mysqli_fetch_assoc($res_h)) : ?>
              <option value="<?= $h['IdHab'] ?>" <?= ($habitat == $h['IdHab'] ? "selected" : "") ?>>
                <?= htmlspecialchars($h['NomHab']) ?>
              </option>
            <?php endwhile; ?>

          </select>
        </div>

        <div class="flex items-end">
          <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-6 rounded-lg">
            Appliquer filtres
          </button>
        </div>

      </form>
    </div>

  
    <div class="mb-8">
      <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800">Résultats (<?= mysqli_num_rows($result) ?>)</h2>
        <a href="ajouter_animal.php" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg">
          + Nouvel animal
        </a>
      </div>

      <?php if(mysqli_num_rows($result) > 0): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">

        <?php while($row = mysqli_fetch_assoc($result)): ?>

          <?php
            $typeColor = $row['Type_alimentaire']=="Carnivore" ? "bg-red-100 text-red-800"
                       : ($row['Type_alimentaire']=="Herbivore" ? "bg-green-100 text-green-800"
                       : "bg-yellow-100 text-yellow-800");
          ?>

          <div class="animalCard bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition cursor-pointer"
               data-nom="<?= htmlspecialchars($row['Nom']) ?>"
               data-type="<?= htmlspecialchars($row['Type_alimentaire']) ?>"
               data-habitat="<?= htmlspecialchars($row['NomHab']) ?>"
               data-image="uploads/<?= htmlspecialchars($row['Image']) ?>">

            <div class="h-48 overflow-hidden">
              <img src="uploads/<?= htmlspecialchars($row['Image']) ?>" 
                   class="w-full h-full object-cover">
            </div>

            <div class="p-5">
              <div class="flex justify-between mb-3">
                <h3 class="text-lg font-bold"><?= htmlspecialchars($row['Nom']) ?></h3>
                <span class="text-xs px-2 py-1 rounded-full <?= $typeColor ?>">
                    <?= htmlspecialchars($row['Type_alimentaire']) ?>
                </span>
              </div>

              <p class="text-sm text-gray-600 mb-4">
                <span class="font-medium">Habitat :</span> <?= htmlspecialchars($row['NomHab']) ?>
              </p>

              <div class="flex space-x-3">
                <a href="modifier_animal.php?id=<?= $row['ID'] ?>" class="flex-1 bg-blue-50 text-blue-600 py-2 rounded-lg text-center text-sm">Modifier</a>
                <a href="supprimer_animal.php?id=<?= $row['ID'] ?>" onclick="return confirm('Supprimer ?')" class="flex-1 bg-red-50 text-red-600 py-2 rounded-lg text-center text-sm">Supprimer</a>
              </div>
            </div>

          </div>

        <?php endwhile; ?>
      </div>

      <?php else: ?>
      <p class="text-center text-gray-500">Aucun animal trouvé.</p>
      <?php endif; ?>

    </div>

  </main>
</div>


<div id="animalModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
  <div class="bg-white rounded-xl max-w-sm w-full p-6">
    <button id="closeModal" class="float-right text-gray-500">✖</button>

    <div class="text-center">
      <img id="modalImage" class="w-40 h-40 mx-auto mb-4 rounded-xl shadow">

      <h3 id="modalNom" class="text-2xl font-bold mb-1"></h3>
      <div id="modalTypeBadge" class="px-3 py-1 rounded-full mb-3 inline-block"></div>

      <p class="bg-gray-50 p-3 rounded mb-4">
        <span class="font-medium">Habitat :</span>
        <span id="modalHabitat"></span>
      </p>

      <button id="modalCloseBtn" class="w-full bg-gray-100 py-2 rounded-lg">
        Fermer
      </button>
    </div>
  </div>
</div>

<script>


document.getElementById('mobileMenuBtn').addEventListener('click', function() {
  const mobileNav = document.getElementById('mobileNav');
  mobileNav.classList.toggle('hidden');
});

const modal = document.getElementById('animalModal');
const modalImage = document.getElementById('modalImage');
const modalNom = document.getElementById('modalNom');
const modalTypeBadge = document.getElementById('modalTypeBadge');
const modalHabitat = document.getElementById('modalHabitat');

const typeColors = {
  "Carnivore": "bg-red-100 text-red-800",
  "Herbivore": "bg-green-100 text-green-800",
  "Omnivore":  "bg-yellow-100 text-yellow-800"
};

document.querySelectorAll('.animalCard').forEach(card => {
  card.addEventListener('click', function(e) {
    if (e.target.tagName === 'A') return;

    modalImage.src = this.dataset.image;
    modalNom.textContent = this.dataset.nom;
    modalHabitat.textContent = this.dataset.habitat;
    modalTypeBadge.textContent = this.dataset.type;
    modalTypeBadge.className = "px-3 py-1 rounded-full " + (typeColors[this.dataset.type] || "");

    modal.classList.remove("hidden");
    modal.classList.add("flex");
  });
});

document.getElementById('closeModal').onclick = () => {
  modal.classList.add("hidden");
};
document.getElementById('modalCloseBtn').onclick = () => {
  modal.classList.add("hidden");
};
</script>

</body>
</html>
