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

        // Fetch company info using POST
        const companyFormData = new FormData();
        companyFormData.append('companyId', companyId);
        const companyResponse = await fetch(`/app/Controllers/company_profileController.php`, {
            method: 'POST',
            body: companyFormData
        });
        if (!companyResponse.ok) throw new Error("Failed to load company info.");
        const company = await companyResponse.json();

        // Populate company info on the page
        document.querySelector(".card-title").textContent = company.name || "Unknown Company";
        document.querySelector(".card-text").innerHTML = `<strong>Email:</strong> ${company.email || "N/A"}`;

        // Fetch job postings for this company
        const jobsFormData = new FormData();
        jobsFormData.append('companyId', companyId);
        jobsFormData.append('type', 'jobs');
        const jobsResponse = await fetch(`/app/Controllers/company_profileController.php`, {
            method: 'POST',
            body: jobsFormData
        });
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
                jobItem.href = `/index.php/applications?job_id=${job.job_id}`;
                jobItem.classList.add("list-group-item", "list-group-item-action", "d-flex", "justify-content-between", "gap-2");

                jobItem.innerHTML = `
                    <div class="col-8">
                        <h6 class="mb-1">${job.title}</h6>
                        <p class="mb-1">${job.description}</p>
                        <small class="text-muted">${job.location}</small>
                    </div>
                    <div class="text-end col-4">
                        <p class="mb-1">${job.applicant_count || 0} applicants</p>
                        <button type="button" class="exportcsv-btn btn btn-primary btn-sm" data-job-id="${job.job_id}">Export As CSV</button>
                        <button type="button" class="remove-btn btn btn-danger btn-sm" data-job-id="${job.job_id}">Remove Posting</button>
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

// Event delegation for dynamically created buttons
document.addEventListener("click", async (event) => {
    const btn = event.target.closest('button');
    if (!btn) return;

    // Prevent clicks on buttons inside the parent <a> from triggering navigation
    event.preventDefault();
    event.stopPropagation();

    const jobId = btn.getAttribute('data-job-id');
    // Re-extract companyId from URL each time to avoid scope issues
    const params = new URLSearchParams(window.location.search);
    const companyId = params.get('id');

    // Remove posting
    if (btn.classList.contains('remove-btn')) {
        if (!jobId) return;
        if (!confirm('Are you sure you want to remove this job posting?')) return;

        try {
            const removeFormData = new FormData();
            removeFormData.append('jobId', jobId);
            removeFormData.append('companyId', companyId);
            removeFormData.append('action', 'remove');
            const response = await fetch(`/app/Controllers/company_profileController.php`, {
                method: 'POST',
                body: removeFormData
            });
            if (!response.ok) throw new Error('Failed to remove job posting.');
            const result = await response.json();
            if (result.success) {
                alert('Job posting removed successfully.');
                window.location.reload();
            } else {
                alert('Failed to remove job posting.');
            }
        } catch (error) {
            console.error('Error removing job posting:', error);
            alert('An error occurred while trying to remove the job posting.');
        }
    }

    // Export applicants CSV
    if (btn.classList.contains('exportcsv-btn')) {
        if (!jobId) return;
        try {
            const exportFormData = new FormData();
            exportFormData.append('jobId', jobId);
            exportFormData.append('companyId', companyId);
            exportFormData.append('action', 'export');
            const response = await fetch(`/app/Controllers/company_profileController.php`, {
                method: 'POST',
                body: exportFormData
            });
            if (!response.ok) {
                // try to parse JSON error
                let errText = await response.text();
                throw new Error('Failed to export applicants: ' + errText);
            }
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `applicants_job_${jobId}.csv`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Error exporting applicants:', error);
            alert('An error occurred while trying to export applicants.');
        }
    }
});