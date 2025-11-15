// company_profile.js

document.addEventListener("DOMContentLoaded", async () => {
    try {
        // Extract the company ID from the URL (e.g., /company_profile?id=3)
        const params = new URLSearchParams(window.location.search);
        const companyId = params.get("id");

        if (!companyId) {
            console.error("Company ID not provided in URL.");
            return;
        }

        // Fetch company info
        const companyResponse = await fetch(`/api/company.php?id=${companyId}`);
        if (!companyResponse.ok) throw new Error("Failed to load company info.");
        const company = await companyResponse.json();

        // Populate company info on the page
        document.querySelector(".card-title").textContent = company.name || "Unknown Company";
        document.querySelector(".card-text").innerHTML = `<strong>Website:</strong> ${company.website || "N/A"}`;

        // Fetch job postings for this company
        const jobsResponse = await fetch(`/api/company_jobs.php?company_id=${companyId}`);
        if (!jobsResponse.ok) throw new Error("Failed to load company jobs.");
        const jobs = await jobsResponse.json();

        // Render job postings
        const jobList = document.querySelector(".list-group");
        jobList.innerHTML = ""; // Clear default placeholders

        if (jobs.length === 0) {
            jobList.innerHTML = `<p class="text-muted p-3 mb-0">No jobs posted yet.</p>`;
        } else {
            jobs.forEach(job => {
                const jobItem = document.createElement("a");
                jobItem.href = `/applications.html?job_id=${job.id}`;
                jobItem.classList.add("list-group-item", "list-group-item-action", "d-flex", "justify-content-between", "gap-2");

                jobItem.innerHTML = `
                    <div class="col-8">
                        <h6 class="mb-1">${job.title}</h6>
                        <p class="mb-1">${job.description}</p>
                        <small class="text-muted">${job.location}</small>
                    </div>
                    <div class="text-end col-4">
                        <small class="text-muted d-block">${formatDate(job.created_at)}</small>
                        <p class="mb-1">${job.applicant_count || 0} applicants</p>
                        <button class="btn btn-primary btn-sm" data-job-id="$(job.id)">Export As CSV</button>
                        <button class="btn btn-danger btn-sm remove-btn" data-job-id="${job.id}">Remove Posting</button>
                    </div>
                `;

                jobList.appendChild(jobItem);
            });
        }

    } catch (error) {
        console.error("Error loading company profile:", error);
    }
});

// Utility function to make timestamps readable
function formatDate(dateString) {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleDateString(undefined, { month: "short", day: "numeric", year: "numeric" });
}
