// Prevent dropdowns from closing when clicking checkboxes
document.querySelectorAll('.dropdown-menu').forEach(menu => {
  menu.addEventListener('click', function (e) {
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
      e.stopPropagation();
    }
  });
});

let currentPage = 1;
const limit = 5; // jobs per page

function loadJobs(page = 1) {
  const formData = new FormData(document.getElementById('filtersForm'));

  // Convert FormData to URL query parameters
  const params = new URLSearchParams();
  params.append('page', page);
  params.append('limit', limit);

  // Add selected filters
  for (let [key, value] of formData.entries()) {
    params.append(key, value);
  }

  // Call the backend controller using an absolute path so the request resolves
  // correctly regardless of where the HTML file lives on disk.
  fetch(`/app/Controllers/indexController.php?${params.toString()}`)
    .then(res => res.json())
    .then(data => {
      const jobs = data.jobs || [];
      const jobsList = document.getElementById('jobsList');
      const prevBtn = document.getElementById('prevPage');
      const nextBtn = document.getElementById('nextPage');
      jobsList.innerHTML = '';

      if (jobs.length === 0) {
        jobsList.innerHTML = '<p class="text-center my-3 text-muted">No jobs found.</p>';
        nextBtn.disabled = true;
        return;
      }

      jobs.forEach(job => {
  const jobCard = document.createElement('a');
  // Link to the apply page using absolute path so it resolves from the web root
  jobCard.href = '/apply.html';
        jobCard.className = 'list-group-item list-group-item-action d-flex justify-content-between gap-2';
        jobCard.innerHTML = `
          <div class="col-8">
            <h6 class="mb-1">${job.title}</h6>
            <p class="mb-1">${job.description}</p>
            <small class="text-muted">${job.location}</small>
          </div>
          <div class="text-end col-4">
            <small class="text-muted d-block">Posted on: ${job.postedDate}</small>
            <p class="mb-1">${job.company}</p>
          </div>
        `;
        jobsList.appendChild(jobCard);
      });

      // Pagination buttons
      prevBtn.disabled = data.page <= 1;
      const totalPages = Math.ceil(data.totalJobs / data.limit);
      nextBtn.disabled = data.page >= totalPages;
    })
    .catch(err => {
      console.error('Error loading jobs:', err);
      document.getElementById('jobsList').innerHTML = '<p class="text-danger text-center">Error loading jobs.</p>';
    });
}

// Add event listeners for pagination buttons
document.addEventListener("DOMContentLoaded", () => {
  const prevBtn = document.getElementById('prevPage');
  const nextBtn = document.getElementById('nextPage');

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

  // Listen to any filter change
  const filtersForm = document.getElementById('filtersForm');
  filtersForm.addEventListener('change', () => {
    currentPage = 1;
    loadJobs(currentPage);
  });

  // Load first page on startup
  loadJobs(currentPage);
});
