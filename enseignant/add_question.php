<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}


if ($_SESSION['role'] !== 'enseignant') {
    header("Location: /QodexV2/etudiant/dashboard.php");
    exit;
}

include('../config/database.php');


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$error_message = '';
$success_message = '';
$quiz = null;


if (!isset($_GET['quiz_id']) || !is_numeric($_GET['quiz_id'])) {
    $_SESSION['error_message'] = "Invalid quiz ID.";
    header("Location: quizez.php");
    exit;
}

$quiz_id = (int)$_GET['quiz_id'];


$quiz_sql = "SELECT q.id, q.titre, q.categorie_id, c.nom as category_name 
             FROM quiz q 
             INNER JOIN category c ON q.categorie_id = c.id 
             WHERE q.id = ? AND q.enseignant_id = ?";
$quiz_stmt = mysqli_prepare($conn, $quiz_sql);
mysqli_stmt_bind_param($quiz_stmt, "ii", $quiz_id, $_SESSION['user_id']);
mysqli_stmt_execute($quiz_stmt);
$quiz_result = mysqli_stmt_get_result($quiz_stmt);

if (mysqli_num_rows($quiz_result) === 0) {
    $_SESSION['error_message'] = "Quiz not found or you don't have permission to edit it.";
    header("Location: quizez.php");
    exit;
}

$quiz = mysqli_fetch_assoc($quiz_result);


$count_sql = "SELECT COUNT(*) as total FROM question WHERE quiz_id = ?";
$count_stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_bind_param($count_stmt, "i", $quiz_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$question_count = mysqli_fetch_assoc($count_result)['total'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_question'])) {
 
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = "Invalid CSRF token. Please try again.";
    } else {
       
        $question_text = trim($_POST['question_text']);
        $question_type = $_POST['question_type'];
        $correct_answer = (int)$_POST['correct_answer'];
        
        
        if (empty($question_text)) {
            $error_message = "Question text is required.";
        } elseif (strlen($question_text) > 500) {
            $error_message = "Question text must be less than 500 characters.";
        } elseif (!in_array($question_type, ['multiple_choice', 'true_false'])) {
            $error_message = "Invalid question type.";
        } else {
            if ($question_type === 'multiple_choice') {
                $option1 = trim($_POST['option1']);
                $option2 = trim($_POST['option2']);
                $option3 = trim($_POST['option3']);
                $option4 = trim($_POST['option4']);
                
                
                if (empty($option1) || empty($option2) || empty($option3) || empty($option4)) {
                    $error_message = "All four options are required for multiple choice questions.";
                } elseif (!in_array($correct_answer, [1, 2, 3, 4])) {
                    $error_message = "Invalid correct answer selection.";
                } else {
                    
                    $insert_sql = "INSERT INTO question (quiz_id, question, option1, option2, option3, option4, correct_option, created_at) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                    $insert_stmt = mysqli_prepare($conn, $insert_sql);
                    mysqli_stmt_bind_param($insert_stmt, "isssssi", $quiz_id, $question_text, $option1, $option2, $option3, $option4, $correct_answer);
                    
                    if (mysqli_stmt_execute($insert_stmt)) {
                        $success_message = "Question added successfully!";
                        $question_count++;
                        
                        $_POST = array();
                    } else {
                        $error_message = "Failed to add question. Please try again.";
                    }
                    mysqli_stmt_close($insert_stmt);
                }
            } else { 
                $option1 = "Vrai";
                $option2 = "Faux";
                $option3 = "";
                $option4 = "";
                
                if (!in_array($correct_answer, [1, 2])) {
                    $error_message = "Invalid correct answer for True/False question.";
                } else {
                    $insert_sql = "INSERT INTO question (quiz_id, question, option1, option2, option3, option4, correct_option, created_at) 
                                   VALUES (?, ?, ?, ?, '', '', ?, NOW())";
                    $insert_stmt = mysqli_prepare($conn, $insert_sql);
                    mysqli_stmt_bind_param($insert_stmt, "isssi", $quiz_id, $question_text, $option1, $option2, $correct_answer);
                    
                    if (mysqli_stmt_execute($insert_stmt)) {
                        $success_message = "Question added successfully!";
                        $question_count++;
                        $_POST = array();
                    } else {
                        $error_message = "Failed to add question. Please try again.";
                    }
                    mysqli_stmt_close($insert_stmt);
                }
            }
        }
    }
}


$questions_sql = "SELECT id, question, option1, option2, option3, option4, correct_option FROM question WHERE quiz_id = ? ORDER BY id ASC";
$questions_stmt = mysqli_prepare($conn, $questions_sql);
mysqli_stmt_bind_param($questions_stmt, "i", $quiz_id);
mysqli_stmt_execute($questions_stmt);
$questions_result = mysqli_stmt_get_result($questions_stmt);

$initials = "";
if (isset($_SESSION['nom'])) {
    $words = explode(' ', $_SESSION['nom']);
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
}

include('../includes/header.php');
?>

<div id="teacherSpace" class="pt-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="quizez.php" class="text-indigo-600 hover:text-indigo-800 flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Retour aux quiz
            </a>
        </div>

        
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl p-6 mb-8">
            <h2 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($quiz['titre']); ?></h2>
            <p class="text-indigo-100">
                <i class="fas fa-folder mr-2"></i><?php echo htmlspecialchars($quiz['category_name']); ?>
                <span class="mx-3">|</span>
                <i class="fas fa-question-circle mr-2"></i><?php echo $question_count; ?> question(s)
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
           
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Ajouter une Question</h3>
                    
                    <?php if (!empty($error_message)): ?>
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <i class="fas fa-exclamation-circle mr-2"></i><?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <i class="fas fa-check-circle mr-2"></i><?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" id="questionForm">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Texte de la question *
                            </label>
                            <textarea 
                                name="question_text" 
                                rows="3" 
                                required 
                                maxlength="500" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                placeholder="Entrez la question ici..."><?php echo isset($_POST['question_text']) ? htmlspecialchars($_POST['question_text']) : ''; ?></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Type de question *
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="question_type" value="multiple_choice" class="form-radio text-indigo-600" <?php echo (!isset($_POST['question_type']) || $_POST['question_type'] === 'multiple_choice') ? 'checked' : ''; ?> onchange="toggleQuestionType()">
                                    <span class="ml-2">Choix multiple (4 options)</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="question_type" value="true_false" class="form-radio text-indigo-600" <?php echo (isset($_POST['question_type']) && $_POST['question_type'] === 'true_false') ? 'checked' : ''; ?> onchange="toggleQuestionType()">
                                    <span class="ml-2">Vrai/Faux</span>
                                </label>
                            </div>
                        </div>

                        <div id="multipleChoiceOptions" class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-3">
                                Options de réponse *
                            </label>
                            <div class="space-y-3">
                                <input type="text" name="option1" maxlength="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Option 1" value="<?php echo isset($_POST['option1']) ? htmlspecialchars($_POST['option1']) : ''; ?>">
                                <input type="text" name="option2" maxlength="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Option 2" value="<?php echo isset($_POST['option2']) ? htmlspecialchars($_POST['option2']) : ''; ?>">
                                <input type="text" name="option3" maxlength="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Option 3" value="<?php echo isset($_POST['option3']) ? htmlspecialchars($_POST['option3']) : ''; ?>">
                                <input type="text" name="option4" maxlength="200" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" placeholder="Option 4" value="<?php echo isset($_POST['option4']) ? htmlspecialchars($_POST['option4']) : ''; ?>">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">
                                Réponse correcte *
                            </label>
                            <select name="correct_answer" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500" id="correctAnswerSelect">
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                                <option value="4">Option 4</option>
                            </select>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" name="add_question" class="flex-1 px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold">
                                <i class="fas fa-plus mr-2"></i>Ajouter la Question
                            </button>
                            <a href="quizez.php" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition font-semibold">
                                Terminer
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">
                        Questions ajoutées (<?php echo $question_count; ?>)
                    </h3>
                    
                    <?php if ($question_count === 0): ?>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-clipboard-list text-4xl mb-3"></i>
                            <p>Aucune question ajoutée</p>
                        </div>
                    <?php else: ?>
                        <div class="space-y-3 max-h-96 overflow-y-auto">
                            <?php 
                            $index = 1;
                            while ($question = mysqli_fetch_assoc($questions_result)): 
                            ?>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <span class="font-bold text-indigo-600">Q<?php echo $index; ?>:</span>
                                            <p class="text-sm text-gray-700 mt-1"><?php echo htmlspecialchars(substr($question['question'], 0, 60)) . (strlen($question['question']) > 60 ? '...' : ''); ?></p>
                                            <p class="text-xs text-green-600 mt-1">
                                                <i class="fas fa-check-circle"></i> 
                                                <?php 
                                                $correct_option = 'option' . $question['correct_option'];
                                                echo htmlspecialchars($question[$correct_option]); 
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php 
                            $index++;
                            endwhile; 
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleQuestionType() {
    const questionType = document.querySelector('input[name="question_type"]:checked').value;
    const multipleChoiceDiv = document.getElementById('multipleChoiceOptions');
    const correctAnswerSelect = document.getElementById('correctAnswerSelect');
    
    if (questionType === 'true_false') {
        multipleChoiceDiv.style.display = 'none';
    
        document.querySelectorAll('#multipleChoiceOptions input').forEach(input => {
            input.removeAttribute('required');
            input.value = '';
        });
       
        correctAnswerSelect.innerHTML = `
            <option value="1">Vrai</option>
            <option value="2">Faux</option>
        `;
    } else {
        multipleChoiceDiv.style.display = 'block';
        document.querySelectorAll('#multipleChoiceOptions input').forEach(input => {
            input.setAttribute('required', 'required');
        });
        correctAnswerSelect.innerHTML = `
            <option value="1">Option 1</option>
            <option value="2">Option 2</option>
            <option value="3">Option 3</option>
            <option value="4">Option 4</option>
        `;
    }
}


document.addEventListener('DOMContentLoaded', toggleQuestionType);
</script>

<?php 
mysqli_stmt_close($quiz_stmt);
mysqli_stmt_close($count_stmt);
mysqli_stmt_close($questions_stmt);
include('../includes/footer.php'); 
?>