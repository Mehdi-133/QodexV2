<div id="createQuizModal"
    class="hidden fixed inset-0 bg-black bg-opacity-50 items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full mx-4 overflow-y-auto max-h-[90vh]">
        <div class="p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Créer un Quiz</h3>
                <button onclick="closeModal('createQuizModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form method="POST">

                
                <div class="mb-4">
                    <label class="text-sm font-medium">Titre *</label>
                    <input type="text" name="titre" required
                        class="w-full border rounded px-4 py-2">
                </div>

                <div class="mb-4">
                   
                    <textarea name="description"
                        class="w-full border rounded px-4 py-2"></textarea>
                </div>

                <div class="mb-6">
                    <label class="text-sm font-medium">Catégorie *</label>
                    <select name="categorie_id" required
                        class="w-full border rounded px-4 py-2">
                        <option value="">-- Choisir --</option>
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                            <option value="<?= $cat['id'] ?>">
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-xl font-bold text-gray-900">Questions</h4>
                        <button type="button" onclick="addQuestion()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition text-sm">
                            <i class="fas fa-plus mr-2"></i>Ajouter une question
                        </button>
                    </div>

                    <div id="questionsContainer">
                      
                        <div class="bg-gray-50 rounded-lg p-4 mb-4 question-block">
                            <div class="flex justify-between items-center mb-4">
                                <h5 class="font-bold text-gray-900">Question 1</h5>
                                <button type="button" onclick="removeQuestion(this)" class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">Question *</label>
                                <input type="text" name="questions[0][question]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Posez votre question...">
                            </div>

                            <div class="grid md:grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-gray-700 text-sm mb-2">Option 1 *</label>
                                    <input type="text" name="questions[0][option1]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm mb-2">Option 2 *</label>
                                    <input type="text" name="questions[0][option2]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm mb-2">Option 3 *</label>
                                    <input type="text" name="questions[0][option3]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-gray-700 text-sm mb-2">Option 4 *</label>
                                    <input type="text" name="questions[0][option4]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Réponse correcte *</label>
                                <select name="questions[0][correct]" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Sélectionner la bonne réponse</option>
                                    <option value="1">Option 1</option>
                                    <option value="2">Option 2</option>
                                    <option value="3">Option 3</option>
                                    <option value="4">Option 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="flex gap-3">
                    <button type="button" onclick="closeModal('createQuizModal')" class="flex-1 border rounded py-2">
                        Annuler
                    </button>

                    <button type="submit" name="add_quiz" class="flex-1 bg-indigo-600 text-white rounded py-2">
                        Créer le Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let questionIndex = 1;

function addQuestion() {
    const container = document.getElementById('questionsContainer');
    const newQuestion = container.children[0].cloneNode(true);

    
    newQuestion.querySelectorAll('input').forEach(input => input.value = '');
    newQuestion.querySelector('select').value = '';

   
    newQuestion.querySelectorAll('input').forEach(input => {
        input.name = input.name.replace(/\d+/, questionIndex);
    });
    newQuestion.querySelector('select').name = newQuestion.querySelector('select').name.replace(/\d+/, questionIndex);

   
    newQuestion.querySelector('h5').textContent = 'Question ' + (questionIndex + 1);

    container.appendChild(newQuestion);
    questionIndex++;
}

function removeQuestion(button) {
    const container = document.getElementById('questionsContainer');
    if (container.children.length > 1) {
        button.closest('.question-block').remove();
    } else {
        alert('Vous devez avoir au moins une question.');
    }
}

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.getElementById(id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById(id).classList.add('hidden');
    document.getElementById(id).classList.remove('flex');
}
</script>
