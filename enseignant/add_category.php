<?php
include('../config/database.php');

?>
    

<div id="createCategoryModal" class="flex hidden fixed inset-0 bg-black bg-opacity-50  items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-gray-900">Nouvelle Catégorie</h3>
                <button onclick="closeModal('createCategoryModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form action="categories.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Nom de la catégorie *
                    </label>
                    <input
                        type="text"
                        name="nom"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Ex: HTML/CSS">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Description
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500"
                        placeholder="Décrivez cette catégorie..."></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('createCategoryModal')"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg">
                        Annuler
                    </button>

                    <button type="submit" name="add"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg">
                        Créer
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>



<script>
    // Open modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    // Close modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        .0
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        if (event.target.classList.contains('bg-opacity-50')) {
            event.target.classList.add('hidden');
            event.target.classList.remove('flex');
        }
    }
</script>