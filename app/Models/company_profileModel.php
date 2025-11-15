<?php
require_once 'config.php';

class company_profileModel{
    public static function getCompany($companyId){
        global $pdo;
        $sql = "SELECT o.name, o.email, o.telephone, o.location
                FROM org o 
                WHERE o.orgRSN = :companyId;";
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
        $sql = "SELECT j.title, j.description, j.pay, j.location, j.job_type, j.hours
                FROM jobs j
                WHERE j.orgRSN = :companyId;";
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

    public static function exportApplications($companyId, $jobId){
        
        global $pdo;
        $sql = "SELECT p.name AS applicant_name, p.email AS applicant_email, a.cover_letter, a.resume_path, p.personRSN as applicant_id
                FROM application a
                JOIN people p ON a.peopleRSN = p.personRSN
                JOIN jobs j ON a.jobRSN = j.jobRSN
                JOIN org o ON j.orgRSN = o.orgRSN
                WHERE o.orgRSN = :companyId AND j.jobRSN = :jobId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':companyId', $companyId);
        $stmt->bindParam(':jobId', $jobId);
        $stmt->execute();
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $sqlquestioncount = "SELECT COUNT(*) AS question_count
                        FROM job_questions q
                        WHERE q.jobRSN = :jobId;";
        $stmtQuestionCount = $pdo->prepare($sqlquestioncount);
        $stmtQuestionCount->bindParam(':jobId', $jobId);
        $stmtQuestionCount->execute();
        $questionCountResult = $stmtQuestionCount->fetch(PDO::FETCH_ASSOC);
        $questionCount = $questionCountResult ? (int)$questionCountResult['question_count'] : 0;

        $sqlquestion = "SELECT q.questionRSN
                        FROM job_questions q
                        WHERE q.jobRSN = :jobId
                        ORDER BY q.questionRSN;";
        $stmtQuestions = $pdo->prepare($sqlquestion);
        $stmtQuestions->bindParam(':jobId', $jobId);
        $stmtQuestions->execute();
        $questions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

        $sqlanswer = "SELECT qa.answer
                        FROM question_answers qa
                        WHERE qa.questionRSN = :questionRSN
                        AND qa.peopleRSN = :applicantId
                        ORDER BY qa.questionRSN;";
        $stmtAnswers = $pdo->prepare($sqlanswer);


        // Set headers to prompt file download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="applications.csv"');

        $output = fopen('php://output', 'w');
        $headers = ['Applicant Name', 'Applicant Email', 'Cover Letter', 'Resume Path'];

        for ($i=1; $i <= $questionCount; $i++) {
            $headers[] = "Question $i Answer";
        }

        fputcsv($output, $headers);

        foreach ($applications as $application) {

            $line = [
                $application['applicant_name'],
                $application['applicant_email'],
                $application['cover_letter'],
                $application['resume_path']
            ];

            foreach ($questions as $question) {

                $stmtAnswers->bindParam(':questionRSN', $question['questionRSN']);
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

    
    function DeleteJob($jobId){
        global $pdo;

        $sql = "DELETE FROM jobs WHERE jobRSN = :jobId;";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':jobId', $jobId);
        return $stmt->execute();
    }

}
?>