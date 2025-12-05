<?php

include "config.php";

$message = "";

function validerChamp($valeur, $regex) {
    return preg_match($regex, $valeur);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"]);
    $type = trim($_POST["type"]);
    $habitat = trim($_POST["habitat"]);

    if (!validerChamp($nom, "/^[a-zA-ZÀ-ÿ\s]{2,50}$/")) {
        $message = " Nom invalide. Lettres et espaces uniquement.";
    } elseif (!validerChamp($type, "/^(Carnivore|Herbivore|Omnivore)$/")) {
        $message = "Type alimentaire invalide.";
    } elseif (!validerChamp($habitat, "/^[0-9]+$/")) {
        $message = "Habitat invalide.";
    } else {
        $nom = mysqli_real_escape_string($conn, $nom);
        $type = mysqli_real_escape_string($conn, $type);
        $habitat = mysqli_real_escape_string($conn, $habitat);

        $imageName = "";
        if (!empty($_FILES["image"]["name"])) {
            $imageName = time() . "_" . basename($_FILES["image"]["name"]);
            $uploadPath = "uploads/" . $imageName;
            move_uploaded_file($_FILES["image"]["tmp_name"], $uploadPath);
        }

        $sql = "
            INSERT INTO animal (Nom, Type_alimentaire, IdHab, Image)
            VALUES ('$nom', '$type', '$habitat', '$imageName')
        ";

        if (mysqli_query($conn, $sql)) {
            $message = "success:Animal ajouté avec succès !";
        } else {
            $message = "error:Erreur SQL : " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un animal - Zoo Éducatif</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex flex-col lg:flex-row">


    <aside class="lg:w-64 bg-indigo-800 text-white hidden lg:flex flex-col">
        <div class="p-6 border-b border-indigo-700">
            <h1 class="text-2xl font-bold">Zoo Éducatif</h1>
            <p class="text-sm text-indigo-300 mt-1">Gestion des animaux</p>
        </div>
        
        <nav class="flex-1 p-4 space-y-2">
            <a href="liste_animaux.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
                Liste animaux
            </a>
            <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">
                Ajouter animal
            </a>
            <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
                Liste habitats
            </a>
            <a href="ajouter_habitat.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
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
            <h1 class="text-xl font-bold">Ajouter Animal</h1>
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
            <a href="ajouter_animal.php" class="block py-3 px-4 rounded-lg bg-indigo-700 font-medium">
                Ajouter animal
            </a>
            <a href="liste_habitats.php" class="block py-3 px-4 rounded-lg hover:bg-indigo-700 transition">
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
        <div class="max-w-lg mx-auto w-full">
            
            <div class="bg-white rounded-xl shadow p-6 lg:p-8">
                <h1 class="text-2xl lg:text-3xl font-bold text-indigo-700 mb-4 text-center">Ajouter un animal</h1>

                <?php if (!empty($message)): 
                    $messageType = strpos($message, 'success:') === 0 ? 'success' : 'error';
                    $messageText = str_replace(['success:', 'error:'], '', $message);
                ?>
                    <div class="mb-6 p-4 rounded-lg <?= $messageType === 'success' ? 'bg-green-50 text-green-800 border border-green-200' : 'bg-red-50 text-red-800 border border-red-200' ?>">
                        <div class="flex items-center">
                            <?php if ($messageType === 'success'): ?>
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-red-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            <?php endif; ?>
                            <span class="font-medium"><?= htmlspecialchars($messageText) ?></span>
                        </div>
                        <?php if ($messageType === 'success'): ?>
                            <div class="mt-2 text-sm">
                                <a href="liste_animaux.php" class="text-indigo-700 hover:text-indigo-800 font-medium">
                                    Voir tous les animaux →
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom de l'animal</label>
                        <input type="text" name="nom" required
                               value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type alimentaire</label>
                        <select name="type" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner</option>
                            <option value="Carnivore" <?= (isset($_POST['type']) && $_POST['type'] == 'Carnivore') ? 'selected' : '' ?>>Carnivore</option>
                            <option value="Herbivore" <?= (isset($_POST['type']) && $_POST['type'] == 'Herbivore') ? 'selected' : '' ?>>Herbivore</option>
                            <option value="Omnivore"  <?= (isset($_POST['type']) && $_POST['type'] == 'Omnivore') ? 'selected' : '' ?>>Omnivore</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Habitat</label>
                        <select name="habitat" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Sélectionner</option>
                            <?php
                            $habitats = mysqli_query($conn, "SELECT IdHab, NomHab FROM habitats ORDER BY NomHab ASC");
                            while($rowHab = mysqli_fetch_assoc($habitats)) {
                                $selected = (isset($_POST['habitat']) && $_POST['habitat'] == $rowHab['IdHab']) ? "selected" : "";
                                echo '<option value="' . $rowHab['IdHab'] . '" ' . $selected . '>' . htmlspecialchars($rowHab['NomHab']) . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image"
                               class="w-full p-3 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    </div>

                    <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg transition">
                        Ajouter l'animal
                    </button>

                </form>
            </div>

        
            <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-800 mb-2">Validation</h3>
                    <p class="text-sm text-gray-600">Tous les champs sont validés avant l'ajout.</p>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="font-medium text-gray-800 mb-2">Format</h3>
                    <p class="text-sm text-gray-600">Image acceptée : JPG, PNG, GIF, WebP</p>
                </div>
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