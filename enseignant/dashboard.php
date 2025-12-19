<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}


?>

<?php

include('../config/database.php');

$initials = strtoupper(substr($_SESSION['nom'], 0, 1) . substr(strrchr($_SESSION['nom'], ' '), 1, 1));

include('../includes/header.php');

?>

<?php

    include("statistics.php")

?>





<div id="teacherSpace" class="pt-16">

    <!-- Dashboard Section -->
    <div id="dashboard" class="section-content">
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <h1 class="text-4xl font-bold mb-4">Tableau de bord Enseignant</h1>
                <p class="text-xl text-indigo-100 mb-6">Gérez vos quiz et suivez les performances de vos étudiants</p>
                <div class="flex gap-4">
                    <button onclick="showSection('categories'); openModal('createCategoryModal')" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                        <i class="fas fa-folder-plus mr-2"></i>Nouvelle Catégorie
                    </button>
                    <button onclick="showSection('quiz'); openModal('createQuizModal')" class="bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-800 transition">
                        <i class="fas fa-plus-circle mr-2"></i>Créer un Quiz
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total Quiz</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $totalQuizzes ?></p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <i class="fas fa-clipboard-list text-blue-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Catégories</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $totalCategories ?></p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <i class="fas fa-folder text-purple-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">User Actifs</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $totalUsers ?></p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <i class="fas fa-user-graduate text-green-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-md p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Taux Réussite</p>
                            <p class="text-3xl font-bold text-gray-900"><?= $averageSuccess ?>%</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <i class="fas fa-chart-line text-yellow-600 text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div id="categories" class="section-content hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Gestion des Catégories</h2>
            <div class="bg-white rounded-xl shadow-md p-6">
                <p class="text-gray-600">Liste des catégories créées...</p>
                <!-- Add category list logic here -->
            </div>
        </div>
    </div>

    <!-- Quiz Section -->
    <div id="quiz" class="section-content hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Mes Quiz</h2>
            <div class="bg-white rounded-xl shadow-md p-6">
                <p class="text-gray-600">Liste des quiz créés...</p>
                <!-- Add quiz list logic here -->
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="results" class="section-content hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Résultats</h2>
            <div class="bg-white rounded-xl shadow-md p-6">
                <p class="text-gray-600">Résultats des étudiants...</p>
                <!-- Add results logic here -->
            </div>
        </div>
    </div>

    <!-- Modal: Créer Catégorie -->
    <div id="createCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Nouvelle Catégorie</h3>
                    <button onclick="closeModal('createCategoryModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Nom de la catégorie *
                        </label>
                        <input type="text" name="nom" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Ex: HTML/CSS">
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Décrivez cette catégorie..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('createCategoryModal')" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-check mr-2"></i>Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Créer Quiz -->
    <div id="createQuizModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Créer un Quiz</h3>
                    <button onclick="closeModal('createQuizModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Titre du quiz *
                        </label>
                        <input type="text" name="titre" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Ex: Les Bases de HTML5">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Catégorie *
                        </label>
                        <select name="categorie_id" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="">Sélectionner une catégorie</option>
                            <!-- Add options dynamically -->
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">
                            Description
                        </label>
                        <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Décrivez votre quiz..."></textarea>
                    </div>
                    <div class="flex gap-3">
                        <button type="button" onclick="closeModal('createQuizModal')" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                            Annuler
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            <i class="fas fa-check mr-2"></i>Créer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    // Navigation
    function showSection(sectionId) {
        document.querySelectorAll('.section-content').forEach(section => {
            section.classList.add('hidden');
        });
        document.getElementById(sectionId).classList.remove('hidden');

        // Update active nav link
        document.querySelectorAll('nav a').forEach(link => {
            link.classList.remove('border-indigo-500', 'text-gray-900');
            link.classList.add('border-transparent', 'text-gray-500');
        });
        if (event && event.target) {
            event.target.classList.remove('border-transparent', 'text-gray-500');
            event.target.classList.add('border-indigo-500', 'text-gray-900');
        }
    }

    // Modals
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('bg-opacity-50')) {
            event.target.classList.add('hidden');
            event.target.classList.remove('flex');
        }
    }
</script>

<?php include('../includes/footer.php'); ?>