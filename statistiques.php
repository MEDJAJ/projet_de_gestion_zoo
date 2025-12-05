<?php
include "config.php";


$sql1 = "
    SELECT habitats.NomHab, COUNT(animal.ID) AS total
    FROM habitats
    LEFT JOIN animal ON animal.IdHab = habitats.IdHab
    GROUP BY habitats.IdHab
";
$res1 = mysqli_query($conn, $sql1);
$labelsHab = []; $dataHab = [];
while ($row = mysqli_fetch_assoc($res1)){
    $labelsHab[] = $row['NomHab'];
    $dataHab[] = $row['total'];
}


$sql2 = "SELECT Type_alimentaire, COUNT(*) AS total FROM animal GROUP BY Type_alimentaire";
$res2 = mysqli_query($conn, $sql2);
$labelsType = []; $dataType = [];
while ($row = mysqli_fetch_assoc($res2)) {
    $labelsType[] = $row['Type_alimentaire'];
    $dataType[] = $row['total'];
}


$sql3 = "SELECT Nom, COUNT(*) AS total FROM animal GROUP BY Nom";
$res3 = mysqli_query($conn, $sql3);
$labelsNom = []; $dataNom = [];
while ($row = mysqli_fetch_assoc($res3)) {
    $labelsNom[] = $row['Nom'];
    $dataNom[] = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoo Éducatif - Statistiques</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">
<div class="min-h-screen flex flex-col lg:flex-row">


  <aside class="lg:w-64 bg-indigo-800 text-white hidden lg:flex flex-col">
    <div class="p-6 border-b border-green-800">
      <h1 class="text-2xl font-bold">Zoo Éducatif</h1>
      <p class="text-sm text-green-300 mt-1">Gestion statistiques</p>
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
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter habitat
      </a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg bg-indigo-700 transition font-medium">
        Statistiques
      </a>
    </nav>
    <div class="p-4 border-t border-green-800 text-sm text-green-300">
      Connecté en tant qu'admin
    </div>
  </aside>


  <header class="lg:hidden bg-indigo-800 text-white p-4">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-bold">Statistiques Zoo</h1>
      <button id="mobileMenuBtn" class="p-2">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
        </svg>
      </button>
    </div>
  
    
  </header>


  <main class="flex-1 p-4 md:p-8 overflow-auto">
    

    <div class="mb-6 md:mb-8">
      <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800">Statistiques du Zoo</h2>
      <p class="text-gray-600">Visualisez les données de votre zoo en temps réel</p>
    </div>

 
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
      
 
      <div class="bg-white rounded-xl shadow p-4 md:p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Animaux par habitat</h3>
        <div class="h-48 md:h-56">
          <canvas id="chartHabitat"></canvas>
        </div>
        <div class="mt-4 text-sm text-gray-500">
          Total habitats : <?= count($labelsHab) ?>
        </div>
      </div>


      <div class="bg-white rounded-xl shadow p-4 md:p-6">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Type alimentaire</h3>
        <div class="h-48 md:h-56">
          <canvas id="chartType"></canvas>
        </div>
        <div class="mt-4 text-sm text-gray-500">
          Total types : <?= count($labelsType) ?>
        </div>
      </div>

  
      <div class="bg-white rounded-xl shadow p-4 md:p-6 sm:col-span-2 lg:col-span-1">
        <h3 class="text-lg font-semibold mb-4 text-gray-800">Répartition des espèces</h3>
        <div class="h-48 md:h-56">
          <canvas id="chartEspeces"></canvas>
        </div>
        <div class="mt-4 text-sm text-gray-500">
          Total espèces : <?= count($labelsNom) ?>
        </div>
      </div>

    </div>

 
    <div class="mt-8 bg-gray-50 rounded-lg p-4 md:p-6">
      <h3 class="font-semibold text-gray-800 mb-3">Résumé des données</h3>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center">
          <p class="text-2xl font-bold text-green-600"><?= array_sum($dataHab) ?></p>
          <p class="text-sm text-gray-600">Animaux total</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-green-600"><?= count($labelsHab) ?></p>
          <p class="text-sm text-gray-600">Habitats</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-green-600"><?= count($labelsType) ?></p>
          <p class="text-sm text-gray-600">Types alimentaires</p>
        </div>
        <div class="text-center">
          <p class="text-2xl font-bold text-green-600"><?= count($labelsNom) ?></p>
          <p class="text-sm text-gray-600">Espèces uniques</p>
        </div>
      </div>
    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

  document.getElementById('mobileMenuBtn').addEventListener('click', function() {
    const mobileNav = document.getElementById('mobileNav');
    mobileNav.classList.toggle('hidden');
  });

  const labelsHab  = <?= json_encode($labelsHab) ?>;
  const dataHab    = <?= json_encode($dataHab) ?>;
  const labelsType = <?= json_encode($labelsType) ?>;
  const dataType   = <?= json_encode($dataType) ?>;
  const labelsNom  = <?= json_encode($labelsNom) ?>;
  const dataNom    = <?= json_encode($dataNom) ?>;

  
  new Chart(document.getElementById("chartHabitat"), {
    type: "bar",
    data: {
      labels: labelsHab,
      datasets: [{ 
        data: dataHab, 
        backgroundColor: "#4ade80", 
        borderRadius: 6 
      }]
    },
    options: { 
      responsive: true,
      maintainAspectRatio: false
    }
  });


  new Chart(document.getElementById("chartType"), {
    type: "pie",
    data: { 
      labels: labelsType, 
      datasets: [{ 
        data: dataType,
        backgroundColor: ["#4ade80", "#60a5fa", "#fbbf24"]
      }]
    },
    options: { 
      responsive: true,
      maintainAspectRatio: false
    }
  });


  new Chart(document.getElementById("chartEspeces"), {
    type: "doughnut",
    data: { 
      labels: labelsNom, 
      datasets: [{ 
        data: dataNom,
        backgroundColor: ["#4ade80", "#60a5fa", "#f472b6", "#fbbf24", "#a78bfa"]
      }]
    },
    options: { 
      responsive: true,
      maintainAspectRatio: false
    }
  });
</script>

</body>
</html>