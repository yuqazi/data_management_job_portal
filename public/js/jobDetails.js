document.addEventListener("DOMContentLoaded", async () => {
  const jobTitleHeading = document.querySelector(".card-title");
  const jobTitle = document.querySelectorAll(".card-body h4")[0].nextElementSibling;
  const jobDesc = document.querySelectorAll(".card-body h4")[1].nextElementSibling;
  const jobLocation = document.querySelectorAll(".card-body h4")[2].nextElementSibling;
  const jobTagsContainer = document.getElementById("jobTags"); // Updated selector
  const companyProfileBtn = document.querySelector("a[href='/company_profile.html']");
  const applyBtn = document.getElementById("applyBtn");

  const urlParams = new URLSearchParams(window.location.search);
  const jobId = urlParams.get("job_id");

  if (!jobId) {
    jobTitleHeading.textContent = "Job Not Found";
    jobDesc.textContent = "Missing job identifier (?job_id=...)";
    return;
  }

  try {
    const res = await fetch(`/app/Controllers/job-detailsController.php?job_id=${jobId}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    if (!data.success) throw new Error(data.error || "Unknown error");

    const job = data.jobDetails;

    jobTitleHeading.textContent = job.title || "Unknown Job";
    jobTitle.textContent = job.title || "N/A";
    jobDesc.textContent = job.description || "No description provided.";
    jobLocation.textContent = job.location || "Not specified.";

    // Populate tags into the #jobTags container
    jobTagsContainer.innerHTML = "";
    if (Array.isArray(job.tags) && job.tags.length > 0) {
      job.tags.forEach(tag => {
        const span = document.createElement("span");
        span.className = "badge bg-secondary me-2 mb-1"; // Added mb-1 for spacing
        span.textContent = tag;
        jobTagsContainer.appendChild(span);
      });
    } else {
      jobTagsContainer.innerHTML = `<span class="text-muted">No tags listed.</span>`;
    }

const companyProfileBtn = document.getElementById("companyProfileBtn");

if (companyProfileBtn) {
  // Set link dynamically to the company that owns this job post
  companyProfileBtn.href = `/index.php/company-profile?id=${job.org_id}`;
}

    if (applyBtn) {
      applyBtn.href = `/index.php/apply?job_id=${jobId}`;
    }

  } catch (err) {
    console.error("Error loading job details:", err);
    jobTitleHeading.textContent = "Error Loading Job";
    jobDesc.textContent = err.message;
  }
});
