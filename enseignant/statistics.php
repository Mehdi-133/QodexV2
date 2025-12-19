<?php

include('../config/database.php');

?>


<?php

$sqlCategories = "SELECT COUNT(*) AS total FROM category";
$resultCategories = $conn->query($sqlCategories);
$totalCategories = $resultCategories->fetch_assoc()['total'];


$sqlAverageSuccess = "SELECT AVG(score / total_questions * 100) AS average FROM result";
$resultAverage = $conn->query($sqlAverageSuccess);
$averageSuccess = round($resultAverage->fetch_assoc()['average'], 1);


$sqlQuizzes = "SELECT COUNT(*) AS total FROM quiz";
$resultQuizzes = $conn->query($sqlQuizzes);
$totalQuizzes = $resultQuizzes->fetch_assoc()['total'];


$sqlUsers = "SELECT COUNT(*) AS total FROM user";
$resultUsers = $conn->query($sqlUsers);
$totalUsers = $resultUsers->fetch_assoc()['total'];

?>











