<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}

require_once('../config/database.php');

$error = '';
$success = '';

// Get category info if id is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category = $result->fetch_assoc();
}

// Update category
if (isset($_POST['update'])) {
    $id          = intval($_POST['id']);
    $nom         = trim($_POST['nom']);
    $description = trim($_POST['description']);

    if (!empty($nom)) {
        $sql = "UPDATE category SET nom = ?, description = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nom, $description, $id);
        $stmt->execute();
        $success = "Catégorie mise à jour avec succès";
        header("Location: categories.php");
        exit;
    } else {
        $error = "Le nom de la catégorie ne peut pas être vide";
    }
}
?>

<?php include('../includes/header.php'); ?>

<div class="pt-20 max-w-2xl mx-auto px-6">
    <h2 class="text-2xl font-bold mb-6">Modifier la catégorie</h2>

    <?php if ($error): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" class="bg-white p-6 rounded shadow">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nom</label>
            <input type="text" name="nom" value="<?= htmlspecialchars($category['nom']) ?>"
                   class="w-full border rounded px-3 py-2" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Description</label>
            <textarea name="description" class="w-full border rounded px-3 py-2"><?= htmlspecialchars($category['description']) ?></textarea>
        </div>

        <button type="submit" name="update" 
                class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
            Mettre à jour
        </button>
        <a href="categories.php" class="ml-4 text-gray-600 hover:underline">Annuler</a>
    </form>
</div>

<?php include('../includes/footer.php'); ?>
