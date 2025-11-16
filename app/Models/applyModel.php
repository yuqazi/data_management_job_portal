<?php

require_once __DIR__ . '/../../config.php';

function submitApplication($userId, $jobId, $coverLetter, $resumePath, $applicationQuestions): bool
{
    global $pdo;

    try {
        // Start a transaction so both inserts succeed or fail together
        $pdo->beginTransaction();

        // Insert into applications
        $sqlApp = "INSERT INTO applications (people_id, job_id, cover_letter, resume_path)
                    VALUES (:people_id, :job_id, :cover_letter, :resume_path);";
        $stmtApp = $pdo->prepare($sqlApp);

        $stmtApp->bindParam(':people_id', $userId);
        $stmtApp->bindParam(':job_id', $jobId);
        $stmtApp->bindParam(':cover_letter', $coverLetter);
        $stmtApp->bindParam(':resume_path', $resumePath);

        $stmtApp->execute();

        // Prepare insert for question answers
        $sqlQuest = "INSERT INTO question_answers (question_id, people_id, answer, option_id)
                        VALUES (:question_id, :people_id, :answer, :option_id);";
        $stmtQuest = $pdo->prepare($sqlQuest);

        // Bind static param once
        $stmtQuest->bindParam(':people_id', $userId);

        // Loop through application questions stored in session
        foreach ($applicationQuestions as $question) {
            $question_id = $question['question_id'];
            $answer = $question['answer'];
            $option_id = $question['option_id'];

            $stmtQuest->bindParam(':question_id', $question_id);
            $stmtQuest->bindParam(':answer', $answer);
            $stmtQuest->bindParam(':option_id', $option_id);

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
