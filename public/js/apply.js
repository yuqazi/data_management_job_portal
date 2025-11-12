// apply.js
document.addEventListener("DOMContentLoaded", async () => {
    const params = new URLSearchParams(window.location.search);
    const jobId = params.get("job_id");

    if (!jobId) {
        console.error("No job_id provided in URL.");
        return;
    }

    try {
        // Fetch job details
        const jobResponse = await fetch(`/api/job.php?id=${jobId}`);
        if (!jobResponse.ok) throw new Error("Failed to load job info.");
        const job = await jobResponse.json();

        document.querySelector(".card-title").textContent = job.title;
        document.querySelector(".card-text").innerHTML = `
            <strong>Description:</strong> ${job.description}
        `;

        // Job details section
        const jobDetails = document.querySelectorAll(".card.my-2 .card-body")[1];
        jobDetails.innerHTML = `
            <h5 class="card-title">Job Details</h5>
            <p class="card-text"><strong>Location:</strong> ${job.location}</p>
            <p class="card-text"><strong>Type:</strong> ${job.type || "N/A"}</p>
            <p class="card-text"><strong>Salary:</strong> ${job.salary || "Not specified"}</p>
        `;

        // Fetch job questions
        const questionsResponse = await fetch(`/api/job_questions.php?job_id=${jobId}`);
        if (!questionsResponse.ok) throw new Error("Failed to load questions.");
        const questions = await questionsResponse.json();

        const questionContainer = document.querySelector(".list-group");
        questionContainer.innerHTML = ""; // clear sample placeholders

        if (questions.length === 0) {
            questionContainer.innerHTML = `<p class="text-muted p-2">No additional questions for this job.</p>`;
        } else {
            questions.forEach((q, index) => {
                const questionDiv = document.createElement("div");
                questionDiv.classList.add("mb-3");
                questionDiv.innerHTML = `
                    <h6 class="mb-1">${q.question_text}</h6>
                    ${
                        q.type === "text"
                            ? `<input type="text" class="form-control" name="q_${q.id}" placeholder="Your answer...">`
                            : q.type === "mcq"
                            ? q.options
                                  .split(",")
                                  .map(
                                      (opt) => `
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="q_${q.id}" value="${opt.trim()}">
                                    <label class="form-check-label">${opt.trim()}</label>
                                </div>`
                                  )
                                  .join("")
                            : ""
                    }
                `;
                questionContainer.appendChild(questionDiv);
            });
        }

        // 3️⃣ Add event listener to the Apply button
        const applyButton = document.querySelector(".btn-success");
        applyButton.addEventListener("click", async (e) => {
            e.preventDefault();

            const resumeFile = document.querySelector("#resumeFile").files[0];
            const coverLetterFile = document.querySelector("#coverLetterFile").files[0];

            if (!resumeFile || !coverLetterFile) {
                alert("Please upload both resume and cover letter.");
                return;
            }

            // Gather answers
            const answers = [];
            questions.forEach((q) => {
                const input = document.querySelector(`[name="q_${q.id}"]:checked`) || document.querySelector(`[name="q_${q.id}"]`);
                answers.push({
                    question_id: q.id,
                    answer: input ? input.value : "",
                });
            });

            const formData = new FormData();
            formData.append("job_id", jobId);
            formData.append("resume", resumeFile);
            formData.append("cover_letter", coverLetterFile);
            formData.append("answers", JSON.stringify(answers));

            const response = await fetch("/api/apply.php", {
                method: "POST",
                body: formData,
            });

            const result = await response.json();
            if (result.success) {
                alert("Application submitted successfully!");
                window.location.href = "/index";
            } else {
                alert("Error submitting application: " + result.error);
            }
        });
    } catch (err) {
        console.error("Error loading apply page:", err);
    }
});
