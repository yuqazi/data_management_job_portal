document.addEventListener("DOMContentLoaded", async () => {
    const params = new URLSearchParams(window.location.search);

    // Accept multiple parameter name variations
    const jobId =
        params.get("jobRSN") ||
        params.get("job_id") ||
        params.get("id");

    const titleEl = document.getElementById("jobTitle");
    const descEl = document.getElementById("jobDescription");
    const numEl = document.getElementById("numApplications");
    const listEl = document.getElementById("applicationList");

    if (!jobId) {
        listEl.innerHTML = `<div class="text-danger">Missing job ID in URL.</div>`;
        return;
    }

    try {
        const res = await fetch(`/app/Controllers/applicationsController.php?jobRSN=${encodeURIComponent(jobId)}`);
        if (!res.ok) throw new Error("HTTP " + res.status);

        const data = await res.json();

        // set job title + description
        if (data.job) {
            titleEl.textContent = data.job.title || "(Untitled job)";
            descEl.textContent = data.job.description || "";
        }

        const apps = data.applications || [];

        // number of applications
        numEl.textContent = `Applications: ${apps.length}`;

        // render applications
        if (apps.length === 0) {
            listEl.innerHTML = `<p>No applications found.</p>`;
            return;
        }

        listEl.innerHTML = apps
            .map(app => {
                const name = app.name || "Unknown";
                const applied = app.applied_at || "";

                return `
                    <jobapp>
                        <a href="/index.php/profile?id=${app.user_id}" 
                           class="list-group-item list-group-item-action">

                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">${name}</h6>
                                <small class="text-muted">${applied}</small>
                            </div>

                        </a>
                    </jobapp>
                `;
            })
            .join("");

    } catch (err) {
        console.error("Applications load error:", err);
        listEl.innerHTML =
            `<p class="text-danger">Error loading applications.</p>`;
    }
});
