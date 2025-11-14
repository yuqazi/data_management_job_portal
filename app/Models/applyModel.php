<?php

require_once 'config.php';

function submitApplication($userId, $jobId, $coverLetter, $resumePath, $applicationQuestions): bool
{
    global $pdo;

    try {
        // Start a transaction so both inserts succeed or fail together
        $pdo->beginTransaction();

        // Insert into applications
        $sqlApp = "INSERT INTO applications (peopleRSN, jobRSN, cover_letter, resume_path)
                    VALUES (:peopleRSN, :jobRSN, :cover_letter, :resume_path);";
        $stmtApp = $pdo->prepare($sqlApp);

        $stmtApp->bindParam(':peopleRSN', $userId);
        $stmtApp->bindParam(':jobRSN', $jobId);
        $stmtApp->bindParam(':cover_letter', $coverLetter);
        $stmtApp->bindParam(':resume_path', $resumePath);

        $stmtApp->execute();

        // Prepare insert for question answers
        $sqlQuest = "INSERT INTO question_answers (questionRSN, peopleRSN, answer, optionRSN)
                        VALUES (:questionRSN, :peopleRSN, :answer, :optionRSN);";
        $stmtQuest = $pdo->prepare($sqlQuest);

        // Bind static param once
        $stmtQuest->bindParam(':peopleRSN', $userId);

        // Loop through application questions stored in session
        foreach ($applicationQuestions as $question) {
            $questionRSN = $question['questionRSN'];
            $answer = $question['answer'];
            $optionRSN = $question['optionRSN'];

            $stmtQuest->bindParam(':questionRSN', $questionRSN);
            $stmtQuest->bindParam(':answer', $answer);
            $stmtQuest->bindParam(':optionRSN', $optionRSN);

            $stmtQuest->execute();
        }

        // Commit if all successful
        $pdo->commit();
        return true;

    } catch (PDOException $e) {
        // Roll back on error
        $pdo->rollBack();
        error_log("Database error: " . $e->getMessage());
        return false;
    }
}
?>
