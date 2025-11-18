// ---------------------------------------------------------
// Prevent dropdowns from closing when clicking checkboxes
// ---------------------------------------------------------
document.querySelectorAll('.dropdown-menu').forEach(menu => {
    menu.addEventListener('click', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
            e.stopPropagation();
        }
    });
});

let currentPage = 1;
const limit = 5;

// ---------------------------------------------------------
// Main loader function
// ---------------------------------------------------------
function loadJobs(page = 1) {

    const filtersForm = document.getElementById('filtersForm');
    const formData = new FormData(filtersForm);

    const params = new URLSearchParams();
    params.append('page', page);
    params.append('limit', limit);

    // Add selected filters
    for (let [key, value] of formData.entries()) {
        params.append(key, value);
    }

    // 🔍 Add search bar values
    const keywordInput = document.querySelectorAll('#centerInput')[0];
    const locationInput = document.querySelectorAll('#centerInput')[1];

    if (keywordInput.value.trim() !== "") {
        params.append('search', keywordInput.value.trim());
    }
    if (locationInput.value.trim() !== "") {
        params.append('location', locationInput.value.trim());
    }

    console.log("Request params:", params.toString());

    fetch(`/app/Controllers/indexController.php?${params.toString()}`)
        .then(res => res.json())
        .then(data => {

            const jobsList = document.getElementById('jobsList');
            const prevBtn = document.getElementById('prevPage');
            const nextBtn = document.getElementById('nextPage');

            jobsList.innerHTML = '';

            const jobs = data.jobs || [];

            if (jobs.length === 0) {
                jobsList.innerHTML = `<p class="text-center my-3 text-muted">No jobs found.</p>`;
                prevBtn.disabled = true;
                nextBtn.disabled = true;
                return;
            }

            jobs.forEach(job => {
                const jobCard = document.createElement('a');
                jobCard.href = `/index.php/job-details?job_id=${job.job_id}`;
                jobCard.className = 'list-group-item list-group-item-action d-flex justify-content-between gap-2';

                jobCard.innerHTML = `
                    <div class="col-8">
                        <h6 class="mb-1">${job.title}</h6>
                        <p class="mb-1">${job.description}</p>
                        <small class="text-muted">${job.location}</small>
                    </div>
                    <div class="text-end col-4">
                        <small class="text-muted d-block">${job.company}</small>
                    </div>
                `;

                jobsList.appendChild(jobCard);
            });

            prevBtn.disabled = data.page <= 1;
            nextBtn.disabled = data.page >= Math.ceil(data.totalJobs / data.limit);
        })
        .catch(err => {
            console.error("Error loading jobs:", err);
        });
}

// ---------------------------------------------------------
// Initialize
// ---------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {

    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');

    const filtersForm = document.getElementById('filtersForm');

    // Pagination
    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) {
            currentPage--;
            loadJobs(currentPage);
        }
    });

    nextBtn.addEventListener('click', () => {
        currentPage++;
        loadJobs(currentPage);
    });

    // Filters
    filtersForm.addEventListener('change', () => {
        currentPage = 1;
        loadJobs(currentPage);
    });

    // 🔍 Search button
    document.querySelector('input[type="button"]').addEventListener('click', () => {
        currentPage = 1;
        loadJobs(currentPage);
    });

    loadJobs(currentPage);
});
