<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Zoo Educatif - Statistiques</title>
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
      <a href="liste_habitats.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Liste habitats</a>
      <a href="ajouter_habitat.php" class="block py-2 px-4 rounded hover:bg-green-700 font-medium">Ajouter habitat</a>
      <a href="statistiques.php" class="block py-2 px-4 rounded hover:bg-green-700 bg-green-700 font-medium shadow">Statistiques</a>
    </nav>
  </aside>

 
  <main class="flex-1 flex flex-col items-center justify-center p-8 overflow-auto">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Statistiques du Zoo</h2>
    <p class="text-gray-700 mb-6 text-center max-w-2xl">Visualisation simple et élégante du nombre d'animaux par habitat pour mieux comprendre la répartition au sein du zoo.</p>


    <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-3xl">
      <canvas id="chartAnimals" class="h-80"></canvas>
    </div>
  </main>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartAnimals').getContext('2d');
const chart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ['Savane', 'Jungle', 'Désert', 'Océan'],
    datasets: [{
      label: 'Nombre d\'animaux',
      data: [3, 3, 2, 3],
      backgroundColor: ['#4ade80','#22c55e','#16a34a','#15803d'],
      borderRadius: 8,
      barThickness: 40
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { display: false },
      title: { display: true, text: 'Animaux par Habitat', font: { size: 20 } }
    },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 } },
      x: { grid: { display: false } }
    }
  }
});
</script>
</body>
</html>
