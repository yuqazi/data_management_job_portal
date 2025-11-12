document.addEventListener("DOMContentLoaded", async () => {
  //  Select elements 
  const jobTitleHeading = document.querySelector(".card-title");
  const jobTitle = document.querySelectorAll(".card-body h4")[0].nextElementSibling;
  const jobDesc = document.querySelectorAll(".card-body h4")[1].nextElementSibling;
  const jobLocation = document.querySelectorAll(".card-body h4")[2].nextElementSibling;
  const jobTagsContainer = document.querySelector(".rounded-pill");
  const companyProfileBtn = document.querySelector("a[href='/company_profile.html']");
  const applyBtn = document.querySelector("a[href='/apply.html']");

  //  Get jobRSN from URL 
  const urlParams = new URLSearchParams(window.location.search);
  const jobRSN = urlParams.get("jobRSN");

  if (!jobRSN) {
    jobTitleHeading.textContent = "Job Not Found";
    jobDesc.textContent = "Missing job identifier (?jobRSN=...)";
    return;
  }

  try {
    //  Fetch job info 
    const res = await fetch(`/app/Controllers/jobController.php?jobRSN=${jobRSN}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    if (data.error) throw new Error(data.error);

    //  Fill in job details 
    jobTitleHeading.textContent = data.title || "Unknown Job";
    jobTitle.textContent = data.title || "N/A";
    jobDesc.textContent = data.description || "No description provided.";
    jobLocation.textContent = data.location || "Not specified.";

    //  Job tags 
    jobTagsContainer.innerHTML = "";
    if (Array.isArray(data.tags) && data.tags.length > 0) {
      data.tags.forEach(tag => {
        const span = document.createElement("span");
        span.className = "badge bg-secondary me-2";
        span.textContent = tag;
        jobTagsContainer.appendChild(span);
      });
    } else {
      jobTagsContainer.innerHTML = `<span class="text-muted">No tags listed.</span>`;
    }

    //  Buttons (link updates) 
    if (companyProfileBtn) {
      companyProfileBtn.href = `/company_profile.html?orgRSN=${data.orgRSN}`;
    }
    if (applyBtn) {
      applyBtn.href = `/apply.html?jobRSN=${jobRSN}`;
    }

  } catch (err) {
    console.error("Error loading job details:", err);
    jobTitleHeading.textContent = "Error Loading Job";
    jobDesc.textContent = err.message;
  }
});
