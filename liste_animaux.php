<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$type = isset($_GET['type']) ? mysqli_real_escape_string($conn, $_GET['type']) : "";
$habitat = isset($_GET['habitat']) ? mysqli_real_escape_string($conn, $_GET['habitat']) : "";

$sql = "
    SELECT a.ID, a.Nom, a.Type_alimentaire, a.Image, h.NomHab
    FROM animal a
    LEFT JOIN habitats h ON a.IdHab = h.IdHab
    WHERE 1=1
";

if (!empty($type)) {
    $sql .= " AND a.Type_alimentaire = '$type' ";
}

if (!empty($habitat)) {
    $sql .= " AND h.NomHab = '$habitat' ";
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
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">

 

  <main class="flex-1 p-8 overflow-auto">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Liste des Animaux</h2>

 
    <form method="GET" class="mb-6 bg-white p-4 rounded-xl shadow flex flex-col sm:flex-row gap-4">
      <div class="flex flex-col w-full">
        <label class="font-semibold text-gray-700 mb-1">Filtrer par type alimentaire</label>
        <select name="type" class="p-2 border border-gray-300 rounded-lg">
          <option value="">Tous</option>
          <option value="Carnivore" <?= ($type=="Carnivore" ? "selected" : "") ?>>Carnivore</option>
          <option value="Herbivore" <?= ($type=="Herbivore" ? "selected" : "") ?>>Herbivore</option>
          <option value="Omnivore"  <?= ($type=="Omnivore" ? "selected" : "") ?>>Omnivore</option>
        </select>
      </div>

      <div class="flex flex-col w-full">
        <label class="font-semibold text-gray-700 mb-1">Filtrer par habitat</label>
        <select name="habitat" class="p-2 border border-gray-300 rounded-lg">
          <option value="">Tous</option>
          <option value="Savane" <?= ($habitat=="Savane" ? "selected" : "") ?>>Savane</option>
          <option value="Jungle" <?= ($habitat=="Jungle" ? "selected" : "") ?>>Jungle</option>
          <option value="Désert" <?= ($habitat=="Désert" ? "selected" : "") ?>>Désert</option>
          <option value="Océan" <?= ($habitat=="Océan" ? "selected" : "") ?>>Océan</option>
        </select>
      </div>

      <div class="flex items-end">
        <button class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">
          Rechercher
        </button>
      </div>
    </form>

  
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <?php if(mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
          <div class="animalCard bg-white rounded-2xl shadow-lg p-6 flex flex-col items-center cursor-pointer transition-transform transform hover:-translate-y-2 hover:shadow-2xl"
               data-nom="<?= htmlspecialchars($row['Nom']) ?>"
               data-type="<?= htmlspecialchars($row['Type_alimentaire']) ?>"
               data-habitat="<?= htmlspecialchars($row['NomHab'] ?? 'Non défini') ?>"
               data-image="uploads/<?= htmlspecialchars($row['Image'] ?? 'default.png') ?>">

            <img src="uploads/<?= htmlspecialchars($row['Image'] ?? 'default.png') ?>" 
                 alt="<?= htmlspecialchars($row['Nom']) ?>" 
                 class="w-40 h-40 object-cover rounded-xl mb-4 shadow-inner">
            
            <h3 class="text-2xl font-bold mb-1 text-gray-800"><?= htmlspecialchars($row['Nom']) ?></h3>
            <p class="text-green-700 font-semibold mb-1"><?= htmlspecialchars($row['Type_alimentaire']) ?></p>
            <p class="text-gray-500 mb-4"><?= htmlspecialchars($row['NomHab'] ?? 'Non défini') ?></p>

            <div class="flex space-x-3">
              <a href="modifier_animal.php?id=<?= $row['ID'] ?>" 
                 class="bg-blue-600 text-white py-2 px-4 rounded-lg shadow hover:bg-blue-700 transition">Modifier</a>

              <a href="supprimer_animal.php?id=<?= $row['ID'] ?>" 
                 onclick="return confirm('Voulez-vous vraiment supprimer cet animal ?');" 
                 class="bg-red-600 text-white py-2 px-4 rounded-lg shadow hover:bg-red-700 transition">Supprimer</a>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-gray-600 col-span-3 text-center">Aucun animal trouvé.</p>
      <?php endif; ?>
    </div>

  </main>
</div>



<div id="animalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full relative">
    <button id="closeModal" class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-2xl">&times;</button>
    <img id="modalImage" src="" alt="" class="w-60 h-60 object-cover rounded-xl mb-4 mx-auto">
    <h3 id="modalNom" class="text-2xl font-bold text-center mb-1"></h3>
    <p id="modalType" class="text-green-700 font-semibold text-center mb-1"></p>
    <p id="modalHabitat" class="text-gray-500 text-center"></p>
  </div>
</div>


<script>
  const modal = document.getElementById('animalModal');
  const modalImage = document.getElementById('modalImage');
  const modalNom = document.getElementById('modalNom');
  const modalType = document.getElementById('modalType');
  const modalHabitat = document.getElementById('modalHabitat');
  const closeModal = document.getElementById('closeModal');

  
  document.querySelectorAll('.animalCard').forEach(card => {
    card.addEventListener('click', () => {
      modalImage.src = card.dataset.image;
      modalNom.textContent = card.dataset.nom;
      modalType.textContent = card.dataset.type;
      modalHabitat.textContent = card.dataset.habitat;
      modal.classList.remove('hidden');
    });
  });

 
  
  closeModal.addEventListener('click', () => {
    modal.classList.add('hidden');
  });


  
  modal.addEventListener('click', (e) => {
    if(e.target === modal) modal.classList.add('hidden');
  });
</script>

</body>
</html>
