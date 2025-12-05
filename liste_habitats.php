<?php
include "config.php";


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
<body class="bg-gray-50">

<div class="min-h-screen flex flex-col lg:flex-row">


  <aside class="lg:w-64 bg-indigo-800 text-white hidden lg:flex flex-col">
    <div class="p-6 border-b border-purple-700">
      <h1 class="text-2xl font-bold">Zoo Éducatif</h1>
      <p class="text-sm text-purple-300 mt-1">Gestion des habitats</p>
    </div>
    
    <nav class="flex-1 p-4 space-y-2">
      <a href="liste_animaux.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Liste animaux
      </a>
      <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter animal
      </a>
      <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium ">
        Liste habitats
      </a>
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter habitat
      </a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Statistiques
      </a>
    </nav>
    
    <div class="p-4 border-t border-purple-700 text-sm text-purple-300">
      Connecté en tant qu'admin
    </div>
  </aside>


  <header class="lg:hidden bg-indigo-800 text-white p-4">
    <div class="flex items-center justify-between">
      <h1 class="text-xl font-bold">Habitats Zoo</h1>
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
      <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">
        Liste habitats
      </a>
      <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Ajouter habitat
      </a>
      <a href="statistiques.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
        Statistiques
      </a>
    </nav>
  </header>

 
  <main class="flex-1 p-4 lg:p-8">
    
   
    <div class="mb-6 lg:mb-8">
      <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2">Liste des Habitats</h1>
      <p class="text-gray-600">Gérez et consultez tous les habitats du zoo</p>
    </div>

   
    <div class="mb-6 lg:mb-8">
      <a href="ajouter_habitat.php" 
         class="inline-block bg-indigo-800 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition">
        + Ajouter un habitat
      </a>
    </div>

    
    <?php if (mysqli_num_rows($result) > 0): ?>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
            
       
            <div class="h-48 overflow-hidden">
              <img src="uploads/<?= htmlspecialchars($row['Image'] ?? 'default.jpg') ?>" 
                   alt="<?= htmlspecialchars($row['NomHab']) ?>" 
                   class="w-full h-full object-cover hover:scale-105 transition duration-300">
            </div>
            
         
            <div class="p-4 lg:p-5">
              
            
              <div class="mb-3">
                <h3 class="text-lg lg:text-xl font-bold text-gray-800 mb-1">
                  <?= htmlspecialchars($row['NomHab']) ?>
                </h3>
              
              </div>
              
             
              <p class="text-gray-600 text-sm mb-4">
                <?= htmlspecialchars($row['Description_Hab']) ?>
              </p>
              
             
              <div class="flex space-x-2 lg:space-x-3">
                <a href="modifier_habitat.php?id=<?= $row['IdHab'] ?>" 
                   class="flex-1 bg-blue-50 hover:bg-blue-100 text-blue-600 font-medium py-2 px-3 rounded-lg text-center text-sm transition">
                  Modifier
                </a>
                
                <a href="supprimer_habitat.php?id=<?= $row['IdHab'] ?>" 
                   onclick="return confirm('Voulez-vous vraiment supprimer cet habitat ?');"
                   class="flex-1 bg-red-50 hover:bg-red-100 text-red-600 font-medium py-2 px-3 rounded-lg text-center text-sm transition">
                  Supprimer
                </a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
   
      <div class="bg-white rounded-xl shadow p-8 lg:p-12 text-center">
        <div class="text-gray-400 mb-4">
          <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
          </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun habitat trouvé</h3>
        <p class="text-gray-600 mb-6">Commencez par ajouter votre premier habitat au zoo.</p>
        <a href="ajouter_habitat.php" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-6 rounded-lg transition">
          Ajouter un habitat
        </a>
      </div>
    <?php endif; ?>

 

  </main>
</div>

<script>
 
  document.getElementById('mobileMenuBtn').addEventListener('click', function() {
    const mobileNav = document.getElementById('mobileNav');
    mobileNav.classList.toggle('hidden');
  });

 
  const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, observerOptions);

 
  document.querySelectorAll('main > div > div').forEach(card => {
    if (card.classList.contains('bg-white')) {
      card.style.opacity = '0';
      card.style.transform = 'translateY(20px)';
      card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
      observer.observe(card);
    }
  });
</script>

</body>
</html>