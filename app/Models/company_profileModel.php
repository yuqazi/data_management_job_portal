<?php
require_once __DIR__ . '/../../config.php';

class company_profileModel{
    public static function getCompany($companyId){
        global $pdo;
        $sql = "
        SELECT o.name, o.email, o.telephone, o.location
        FROM org o 
        WHERE o.org_id = :companyId;
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':companyId', $companyId);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return null;
        }
    }

    public static function getJobsByCompany($companyId){
        global $pdo;
        $sql = "SELECT j.job_id, j.title, j.description, j.pay, j.location, j.job_type, j.hours, 
                (SELECT COUNT(*) FROM application WHERE job_id = j.job_id) as applicant_count
                FROM jobs j
                WHERE j.org_id = :companyId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':companyId', $companyId);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if($result){
            return $result;
        }else{
            return [];
        }
    }

    public static function exportApplications($companyId, $jobId){
        
        global $pdo;
        $sql = "SELECT 
                    p.name AS applicant_name,
                    p.email AS applicant_email,
                    CASE 
                        WHEN a.resume_id IS NOT NULL THEN 'Y'
                        ELSE 'N'
                    END AS has_resume,
                    CASE 
                        WHEN a.coverletter_id IS NOT NULL THEN 'Y'
                        ELSE 'N'
                    END AS has_coverletter,
                    p.people_id AS applicant_id
                FROM application a
                JOIN people p ON a.people_id = p.people_id
                JOIN jobs j ON a.job_id = j.job_id
                JOIN org o ON j.org_id = o.org_id
                WHERE o.org_id = :companyId 
                AND j.job_id = :jobId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':companyId', $companyId);
        $stmt->bindParam(':jobId', $jobId);
        $stmt->execute();
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sqlquestioncount = "SELECT COUNT(*) AS question_count
                        FROM job_questions q
                        WHERE q.job_id = :jobId;";
        $stmtQuestionCount = $pdo->prepare($sqlquestioncount);
        $stmtQuestionCount->bindParam(':jobId', $jobId);
        $stmtQuestionCount->execute();
        $questionCountResult = $stmtQuestionCount->fetch(PDO::FETCH_ASSOC);
        $questionCount = $questionCountResult ? (int)$questionCountResult['question_count'] : 0;

        $sqlquestion = "SELECT q.question_id
                        FROM job_questions q
                        WHERE q.job_id = :jobId
                        ORDER BY q.question_id;";
        $stmtQuestions = $pdo->prepare($sqlquestion);
        $stmtQuestions->bindParam(':jobId', $jobId);
        $stmtQuestions->execute();
        $questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

        $sqlanswer = "SELECT qa.answer
                        FROM question_answers qa
                        WHERE qa.question_id = :question_id
                        AND qa.people_id = :applicantId
                        ORDER BY qa.question_id;";
        $stmtAnswers = $pdo->prepare($sqlanswer);


        // Set headers to prompt file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="applications.csv"');

        $output = fopen('php://output', 'w');
        $headers = ['Applicant Name', 'Applicant Email', 'Cover Letter', 'Resume'];

        for ($i=1; $i <= $questionCount; $i++) {
            $headers[] = "Question $i Answer";
        }

        fputcsv($output, $headers);

        foreach ($applications as $application) {

            $line = [
                $application['applicant_name'],
                $application['applicant_email'],
                $application['has_coverletter'],
                $application['has_resume']
            ];

            foreach ($questions as $question) {

                $stmtAnswers->bindParam(':question_id', $question['question_id']);
                $stmtAnswers->bindParam(':applicantId', $application['applicant_id']);
                $stmtAnswers->execute();
                $answers = $stmtAnswers->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($answers as $answer) {
                    $line[] = $answer['answer'] ?? "";
                }
            }

            fputcsv($output, $line);
        }

        fclose($output);
    }

    
    public static function DeleteJob($jobId){
        global $pdo;

        $sql = "DELETE FROM jobs WHERE job_id = :jobId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);
        return $stmt->execute();
    }

}
?>