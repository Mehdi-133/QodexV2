<?php
session_start();


if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}

require_once('../config/database.php');

if (isset($_POST['add'])) {

    $nom         = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $created_by  = $_SESSION['user_id'];

    if (!empty($nom)) {
        $sql = "INSERT INTO category (nom, description, created_by)
                VALUES (?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nom, $description, $created_by);
        $stmt->execute();
    }

    header("Location: categories.php");
    exit;
}


$sql = "
    SELECT 
        c.id,
        c.nom,
        c.description,
        COUNT(q.id) AS quiz_count
    FROM category c
    LEFT JOIN quiz q ON q.categorie_id = c.id
    GROUP BY c.id, c.nom, c.description
    ORDER BY c.nom ASC
";

$result = $conn->query($sql);


include('../includes/header.php');
include('../enseignant/add_category.php');
?>

<div class="pt-20">
    <div class="max-w-7xl mx-auto px-6 py-8">


        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Gestion des Catégories</h2>
                <p class="text-gray-600 mt-1">Toutes les catégories disponibles</p>
            </div>

            <button
                onclick="openModal('createCategoryModal')"
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                <i class="fas fa-plus mr-2"></i>Nouvelle Catégorie
            </button>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $colors = ['blue', 'green', 'purple', 'orange', 'red', 'indigo'];
                    $color = $colors[array_rand($colors)];
                    ?>

                    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-<?= $color ?>-500">


                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-xl font-bold text-gray-900 break-words">
                                <?= htmlspecialchars($row['nom']) ?>
                            </h3>

                            <div class="flex gap-3 ml-3">

                                <a href="edit_category.php?id=<?= $row['id'] ?>"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>


                                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>


                            </div>
                        </div>


                        <p class="text-gray-600 text-sm">
                            <?= $row['description']
                                ? htmlspecialchars($row['description'])
                                : '<i>Aucune description disponible</i>' ?>
                        </p>


                        <div class="flex items-center justify-between mt-4 pt-4 border-t">
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">
                                <i class="fas fa-list mr-1"></i>
                                <?= $row['quiz_count'] ?> quiz
                            </span>

                            <a href="quizez.php?category_id=<?= $row['id'] ?>"
                                class="text-indigo-600 text-sm font-semibold hover:underline">
                                Voir les quiz →
                            </a>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed">
                    <i class="fas fa-folder-open text-5xl text-gray-400 mb-4"></i>
                    <p class="text-gray-600 font-medium">Aucune catégorie trouvée</p>
                    <p class="text-gray-400 text-sm">
                        Les catégories apparaîtront ici automatiquement
                    </p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>
<script>
    function confirmDelete(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette catégorie ?")) {
            window.location.href = "delete_category.php?id=" + id;
        }
    }
</script>


<?php include('../includes/footer.php'); ?>