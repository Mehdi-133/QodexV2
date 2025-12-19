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



<div id="teacherSpace" class="pt-16">
    <!-- Results Section -->
    <div id="results" class="section-content">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Résultats des Étudiants</h2>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Étudiant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quiz</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Score</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold mr-3">
                                            YK
                                        </div>
                                        <div class="text-sm font-medium text-gray-900">Youssef Kadiri</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Les Bases de HTML5</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-green-600">18/20</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">04 Déc 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Réussi
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <?php include('../includes/footer.php'); ?>