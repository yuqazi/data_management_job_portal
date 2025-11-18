<?php
require_once __DIR__ . '/../../config.php';

class ApplyModel {

    public function getJobAndQuestions($jobId) {
        global $pdo;

        // Fetch job
        $stmt = $pdo->prepare("SELECT * FROM jobs WHERE job_id = ?");
        $stmt->execute([$jobId]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$job) return null;

        // Fetch questions
        $stmtQ = $pdo->prepare("SELECT * FROM job_questions WHERE job_id = ? ORDER BY order_index ASC");
        $stmtQ->execute([$jobId]);
        $questions = $stmtQ->fetchAll(PDO::FETCH_ASSOC);

        // Fetch options for each question
        foreach ($questions as &$q) {
            $stmtO = $pdo->prepare("SELECT * FROM question_options WHERE question_id = ?");
            $stmtO->execute([$q['question_id']]);
            $q['options'] = $stmtO->fetchAll(PDO::FETCH_ASSOC);
        }

        return [
            "job" => $job,
            "questions" => $questions
        ];
    }
}
