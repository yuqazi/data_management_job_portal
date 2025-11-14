<?php
// indexModel.php
require_once 'config.php';

function getAllJobs() {

    global $pdo;

    $sql = "SELECT j.title, o.name AS company, j.address AS location, j.desc AS description, j.hours AS jobType, e.duration AS experience, j.pay AS salaryRange
            FROM jobs j, org o, exp_want e, skills_want sw, skills s
            WHERE j.orgRSN = o.orgRSN
            AND e.jobRSN = j.jobRSN

            --AND sw.jobRSN = j.jobRSN
            --AND sw.skillRSN = s.skillRSN
            --AND s.skill = inputskill

            --AND j.pay < lowerInput
            --AND j.pay > upperInput

            --AND j.hours = inputType
            ;";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $jobs;

    /*
    return [
        // TechCorp
        [
            "title" => "Frontend Developer",
            "company" => "TechCorp",
            "location" => "Toronto, ON",
            "description" => "Develop responsive web apps using React and Bootstrap.",
            "postedDate" => "2025-11-01",
            "jobType" => "Full-Time",
            "experience" => "Mid-Level",
            "salaryRange" => "60k-80k"
        ],
        [
            "title" => "Backend Engineer",
            "company" => "TechCorp",
            "location" => "Remote",
            "description" => "Design RESTful APIs with Node.js and PHP.",
            "postedDate" => "2025-10-28",
            "jobType" => "Remote",
            "experience" => "Senior",
            "salaryRange" => "80k-100k"
        ],
        [
            "title" => "Quality Assurance Tester",
            "company" => "TechCorp",
            "location" => "Toronto, ON",
            "description" => "Perform test cases and ensure product stability.",
            "postedDate" => "2025-09-22",
            "jobType" => "Volunteer",
            "experience" => "Entry-Level",
            "salaryRange" => "45k-60k"
        ],

        // DataSoft
        [
            "title" => "Data Analyst",
            "company" => "DataSoft",
            "location" => "Vancouver, BC",
            "description" => "Analyze datasets, build dashboards, and generate business insights.",
            "postedDate" => "2025-10-25",
            "jobType" => "Full-Time",
            "experience" => "Mid-Level",
            "salaryRange" => "65k-85k"
        ],
        [
            "title" => "Machine Learning Engineer",
            "company" => "DataSoft",
            "location" => "Calgary, AB",
            "description" => "Build and deploy ML models using Python and TensorFlow.",
            "postedDate" => "2025-09-18",
            "jobType" => "Volunteer",
            "experience" => "Senior",
            "salaryRange" => "100k-130k"
        ],
        [
            "title" => "Database Administrator",
            "company" => "DataSoft",
            "location" => "Vancouver, BC",
            "description" => "Manage large-scale MySQL databases and optimize performance.",
            "postedDate" => "2025-10-02",
            "jobType" => "Full-Time",
            "experience" => "Mid-Level",
            "salaryRange" => "70k-90k"
        ],

        // Designify
        [
            "title" => "UX Designer",
            "company" => "Designify",
            "location" => "Ottawa, ON",
            "description" => "Create wireframes, prototypes, and visual mockups.",
            "postedDate" => "2025-11-02",
            "jobType" => "Full-Time",
            "experience" => "Entry-Level",
            "salaryRange" => "55k-70k"
        ],
        [
            "title" => "Graphic Designer",
            "company" => "Designify",
            "location" => "Toronto, ON",
            "description" => "Design visual assets and digital illustrations.",
            "postedDate" => "2025-10-20",
            "jobType" => "Volunteer",
            "experience" => "Mid-Level",
            "salaryRange" => "50k-65k"
        ],
        [
            "title" => "Marketing Coordinator",
            "company" => "Designify",
            "location" => "Calgary, AB",
            "description" => "Coordinate and plan marketing campaigns.",
            "postedDate" => "2025-08-14",
            "jobType" => "Full-Time",
            "experience" => "Entry-Level",
            "salaryRange" => "45k-55k"
        ],

        // CloudEdge
        [
            "title" => "DevOps Engineer",
            "company" => "CloudEdge",
            "location" => "Remote",
            "description" => "Manage CI/CD pipelines and AWS infrastructure.",
            "postedDate" => "2025-10-10",
            "jobType" => "Remote",
            "experience" => "Senior",
            "salaryRange" => "95k-120k"
        ],
        [
            "title" => "System Administrator",
            "company" => "CloudEdge",
            "location" => "Toronto, ON",
            "description" => "Maintain Linux servers and network infrastructure.",
            "postedDate" => "2025-09-05",
            "jobType" => "Full-Time",
            "experience" => "Mid-Level",
            "salaryRange" => "70k-85k"
        ],

        // CodeWave
        [
            "title" => "Junior Web Developer",
            "company" => "CodeWave",
            "location" => "Mississauga, ON",
            "description" => "Assist in building small web apps using JavaScript and PHP.",
            "postedDate" => "2025-11-05",
            "jobType" => "Full-Time",
            "experience" => "Entry-Level",
            "salaryRange" => "45k-55k"
        ],
        [
            "title" => "Mobile App Developer",
            "company" => "CodeWave",
            "location" => "Remote",
            "description" => "Develop Android and iOS applications using Flutter.",
            "postedDate" => "2025-10-29",
            "jobType" => "Remote",
            "experience" => "Mid-Level",
            "salaryRange" => "75k-90k"
        ],
        [
            "title" => "Tech Support Specialist",
            "company" => "CodeWave",
            "location" => "Calgary, AB",
            "description" => "Provide tier 2 support for enterprise software clients.",
            "postedDate" => "2025-09-20",
            "jobType" => "Volunteer",
            "experience" => "Entry-Level",
            "salaryRange" => "40k-50k"
        ],
        [
            "title" => "IT Project Coordinator",
            "company" => "CodeWave",
            "location" => "Toronto, ON",
            "description" => "Assist project managers and track sprint progress.",
            "postedDate" => "2025-08-10",
            "jobType" => "Volunteer",
            "experience" => "Mid-Level",
            "salaryRange" => "60k-75k"
        ],
        [
            "title" => "Security Engineer",
            "company" => "CodeWave",
            "location" => "Ottawa, ON",
            "description" => "Implement and monitor network security systems.",
            "postedDate" => "2025-07-25",
            "jobType" => "Full-Time",
            "experience" => "Senior",
            "salaryRange" => "100k-130k"
        ]
    ];
    */
}
?>
