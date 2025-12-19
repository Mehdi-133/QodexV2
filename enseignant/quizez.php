<?php
ob_start();
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in'])) {
    header("Location: /QodexV2/auth/login.php");
    exit;
}

include('../config/database.php');


$initials = '';
if (!empty($_SESSION['nom'])) {
    $words = explode(' ', trim($_SESSION['nom']));
    $initials = strtoupper(
        substr($words[0], 0, 1) .
            (isset($words[1]) ? substr($words[1], 0, 1) : '')
    );
}

if (isset($_POST['add_quiz'])) {
    $titre        = trim($_POST['titre']);
    $description  = trim($_POST['description']);
    $categorie_id = (int)$_POST['categorie_id'];
    $enseignant   = $_SESSION['user_id'];

    $conn->begin_transaction();

    try {
        
        $sql = "INSERT INTO quiz (titre, description, enseignant_id, categorie_id) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssii", $titre, $description, $enseignant, $categorie_id);
        $stmt->execute();
        $quiz_id = $stmt->insert_id;

        // Insert questions
        foreach ($_POST['questions'] as $q) {
            $sqlQ = "INSERT INTO question (quiz_id, question, option1, option2, option3, option4, correct) 
                     VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmtQ = $conn->prepare($sqlQ);
            $stmtQ->bind_param(
                "isssssi",
                $quiz_id,
                $q['question'],
                $q['option1'],
                $q['option2'],
                $q['option3'],
                $q['option4'],
                $q['correct']
            );
            $stmtQ->execute();
        }

        $conn->commit();
        header("Location: quizez.php");
        exit;
    } catch (Exception $e) {
        $conn->rollback();
        echo "Erreur lors de la création du quiz : " . $e->getMessage();
    }
}


$sqlQuiz = "
    SELECT 
        q.id,
        q.titre,
        q.description,
        c.nom AS categorie,
        COUNT(DISTINCT qu.id) AS total_questions,
        COUNT(DISTINCT r.id) AS total_participants
    FROM quiz q
    JOIN category c ON q.categorie_id = c.id
    LEFT JOIN question qu ON qu.quiz_id = q.id
    LEFT JOIN result r ON r.quiz_id = q.id
    WHERE q.enseignant_id = ?
    GROUP BY q.id
    ORDER BY q.created_at DESC
";

$stmt = $conn->prepare($sqlQuiz);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$quizzes = $stmt->get_result();


$categories = mysqli_query($conn, "SELECT id, nom FROM category ORDER BY nom ASC");

include('../includes/header.php');
include('../enseignant/add_quiz.php');
?>

<div id="teacherSpace" class="pt-20">
    <div class="max-w-7xl mx-auto px-4 py-8">

        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Mes Quiz</h2>
                <p class="text-gray-600 mt-2">Créez et gérez vos quiz</p>
            </div>
            <button onclick="openModal('createQuizModal')"
                class="bg-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-indigo-700">
                <i class="fas fa-plus mr-2"></i>Créer un Quiz
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <?php if ($quizzes->num_rows > 0): ?>
                <?php while ($quiz = $quizzes->fetch_assoc()): ?>

                    <div class="bg-white rounded-xl shadow-md p-6">
                        <div class="flex justify-between mb-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs rounded-full">
                                <?= htmlspecialchars($quiz['categorie']) ?>
                            </span>
                            <div class="flex gap-2">
                                <button class="text-blue-600"><i class="fas fa-edit"></i></button>
                                <button class="text-red-600"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>

                        <h3 class="text-xl font-bold mb-2">
                            <?= htmlspecialchars($quiz['titre']) ?>
                        </h3>

                        <p class="text-gray-600 text-sm mb-4">
                            <?= htmlspecialchars($quiz['description'] ?? 'Aucune description') ?>
                        </p>

                        <div class="flex justify-between text-sm text-gray-500 mb-4">
                            <span><i class="fas fa-question-circle"></i> <?= $quiz['total_questions'] ?> questions</span>
                            <span><i class="fas fa-user-friends"></i> <?= $quiz['total_participants'] ?> participants</span>
                        </div>

                        <a href="view_results.php?quiz_id=<?= $quiz['id'] ?>"
                            class="block w-full text-center bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                            Voir les résultats
                        </a>
                    </div>

                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-3 text-center text-gray-500 py-20">
                    Aucun quiz trouvé
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php include('../includes/footer.php');
ob_end_flush(); ?>