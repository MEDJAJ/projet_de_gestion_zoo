<?php
$conn = mysqli_connect("localhost", "root", "", "zoo");
if (!$conn) { die("Erreur : " . mysqli_connect_error()); }


$sql1 = "
    SELECT habitats.NomHab, COUNT(animal.ID) AS total
    FROM habitats
    LEFT JOIN animal ON animal.IdHab = habitats.IdHab
    GROUP BY habitats.IdHab
";
$res1 = mysqli_query($conn, $sql1);
$labelsHab = []; $dataHab = [];
while ($row = mysqli_fetch_assoc($res1)) {
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
  <title>Zoo Educatif - Statistiques</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 font-sans">
<div class="flex h-screen">

 

  <main class="flex-1 p-10 overflow-auto flex flex-col items-center">

    <h2 class="text-3xl font-bold mb-4">Statistiques du Zoo</h2>


    <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 w-full max-w-md">
      <h3 class="text-xl font-semibold mb-4">Nombre d’animaux par habitat</h3>
      <canvas id="chartHabitat" class="h-36"></canvas>
    </div>


    <div class="bg-white rounded-2xl shadow-lg p-6 mb-10 w-full max-w-md">
      <h3 class="text-xl font-semibold mb-4">Nombre d’animaux par type alimentaire</h3>
      <canvas id="chartType" class="h-36"></canvas>
    </div>


    <div class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md">
      <h3 class="text-xl font-semibold mb-4">Répartition des espèces</h3>
      <canvas id="chartEspeces" class="h-36"></canvas>
    </div>

  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

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
    datasets: [{ data: dataHab, backgroundColor: "#4ade80", borderRadius: 8 }]
  },
  options: { responsive: true }
});


new Chart(document.getElementById("chartType"), {
  type: "pie",
  data: { labels: labelsType, datasets: [{ data: dataType }]},
  options: { responsive: true }
});


new Chart(document.getElementById("chartEspeces"), {
  type: "doughnut",
  data: { labels: labelsNom, datasets: [{ data: dataNom }]},
  options: { responsive: true }
});
</script>

</body>
</html>
