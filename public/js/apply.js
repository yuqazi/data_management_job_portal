document.addEventListener("DOMContentLoaded", async () => {

    const urlParams = new URLSearchParams(window.location.search);
    const jobId = urlParams.get("job_id");

    if (!jobId) {
        console.error("No job_id in URL");
        return;
    }

    try {
        // -------------------------------------
        // Fetch job + questions from controller
        // -------------------------------------
        const res = await fetch(`/app/Controllers/applyController.php?job_id=${jobId}`);
        const data = await res.json();

        if (!data.success) {
            console.error("Error loading job:", data.message);
            return;
        }

        const job = data.data.job;
        const questions = data.data.questions;

        // -------------------------------------
        // Fill Job Information
        // -------------------------------------
        document.getElementById("jobTitle").innerHTML = job.title;
        document.getElementById("jobDescription").innerHTML = job.description;
        document.getElementById("jobLocation").innerHTML = job.location;
        document.getElementById("jobType").innerHTML = job.job_type;
        document.getElementById("jobSalary").innerHTML = job.pay;

        // -------------------------------------
        // Render Questions
        // -------------------------------------
        const questionsContainer = document.getElementById("questionsContainer");
        questionsContainer.innerHTML = ""; // clear default placeholder

        questions.forEach(q => {
            let html = `
                <div class="mb-3">
                    <h6 class="mb-1">${q.question_text}</h6>
                    <div>
            `;

            // Has options → show radio buttons
            if (q.options.length > 0) {
                q.options.forEach(opt => {
                    html += `
                        <div class="form-check">
                            <input class="form-check-input"
                                   type="radio"
                                   name="question_${q.question_id}"
                                   value="${opt.option_id}">
                            <label class="form-check-label">
                                ${opt.option_text}
                            </label>
                        </div>
                    `;
                });
            } 
            // No options → show text area
            else {
                html += `
                    <textarea 
                        class="form-control"
                        name="question_${q.question_id}"
                        rows="2"
                        placeholder="Your answer..."></textarea>
                `;
            }

            html += `
                    </div>
                </div>
            `;

            questionsContainer.insertAdjacentHTML("beforeend", html);
        });

        // --------------------------------------------------
        // Handle APPLY button click for file + answers upload
        // --------------------------------------------------

        const applyButton = document.getElementById("applyBtn");

        if (applyButton) {
            applyButton.addEventListener("click", async () => {

                const resume = document.getElementById("resumeFile").files[0];
                const cover = document.getElementById("coverLetterFile").files[0];

                if (!resume || !cover) {
                    alert("Please upload both resume and cover letter.");
                    return;
                }

                let formData = new FormData();
                formData.append("job_id", jobId);
                formData.append("resume", resume);
                formData.append("cover_letter", cover);

                // ------------------------------
                // Add question answers to formData
                // ------------------------------
                questions.forEach(q => {
                    let fieldName = `question_${q.question_id}`;
                    let elem = document.querySelector(`[name="${fieldName}"]:checked`) 
                               || document.querySelector(`[name="${fieldName}"]`);

                    if (elem) {
                        // Radio button → option ID
                        if (elem.type === "radio") {
                            formData.append(fieldName, elem.value);
                        }
                        // Textarea → answer text
                        else {
                            formData.append(fieldName, elem.value);
                        }
                    } else {
                        formData.append(fieldName, ""); // no answer
                    }
                });

                try {
                    const uploadRes = await fetch("/app/Models/uploadApplication.php", {
                        method: "POST",
                        body: formData
                    });

                    const uploadData = await uploadRes.json();

                    if (uploadData.success) {
                        alert("Application submitted successfully!");
                        window.location.href = "/index.php/index";
                    } else {
                        alert("Upload failed: " + uploadData.message);
                    }

                } catch (err) {
                    console.error("Upload error:", err);
                    alert("Error submitting application.");
                }
            });
        }

    } catch (err) {
        console.error("Error loading job:", err);
    }
});
