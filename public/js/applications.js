document.addEventListener("DOMContentLoaded", async () => {
  const jobTitle = document.getElementById("jobTitle");
  const list = document.getElementById("applicationList");
  const numApplications = document.getElementById("numApplications");

  // Extract jobRSN from URL
  const urlParams = new URLSearchParams(window.location.search);
  const jobRSN = urlParams.get("jobRSN");

  if (!jobRSN) {
    console.error("Missing jobRSN in URL");
    if (list) list.innerHTML = `<div class="p-3 text-danger">Missing job identifier.</div>`;
    return;
  }

  try {
    // Fetch JSON from PHP controller
    const res = await fetch(`/app/Controllers/applicationController.php?jobRSN=${jobRSN}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    if (data.error) {
      throw new Error(data.error);
    }

    // Update job info
    if (data.job && jobTitle) {
      jobTitle.textContent = data.job.title || "(Untitled Job)";
      const descriptionEl = jobTitle.nextElementSibling;
      if (descriptionEl) {
        descriptionEl.innerHTML = `
          <strong>Description: </strong> ${data.job.description || "(No description provided)"}<br>
          <strong>Location:</strong> ${data.job.location || "N/A"}<br>
          <strong>Company:</strong> ${data.job.company || "N/A"}<br>
          <strong>Pay:</strong> ${data.job.pay ? "$" + data.job.pay : "N/A"}
        `;
      }
    }

    // Clear the current list
    list.innerHTML = "";

    // Render applications
    if (Array.isArray(data.applications) && data.applications.length > 0) {
      data.applications.forEach((app) => {
        const a = document.createElement("a");
        a.href = "#";
        a.className = "list-group-item list-group-item-action";
        a.innerHTML = `
          <div class="d-flex w-100 justify-content-between">
            <h6 class="mb-1">${app.name || "(Unknown Applicant)"}</h6>
            <small class="text-muted">${app.appliedDate || "(Unknown Date)"}</small>
          </div>
          <p class="mb-1">${app.coverLetter || ""}</p>
        `;
        list.appendChild(a);
      });
    } else {
      list.innerHTML = `<div class="p-3 text-muted">No applications found for this job.</div>`;
    }

    // Update application count
    if (numApplications) {
      numApplications.textContent = `Number of Applications: ${data.applications?.length || 0}`;
    }

  } catch (err) {
    console.error("Failed to load applications:", err);
    list.innerHTML = `<div class="p-3 text-danger">Error loading applications: ${err.message}</div>`;
    if (numApplications) numApplications.textContent = "Number of Applications: 0";
  }
});
