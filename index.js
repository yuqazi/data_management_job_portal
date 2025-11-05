document.querySelectorAll('.dropdown-menu').forEach(menu => {
  menu.addEventListener('click', function (e) {
    // Only prevent closing when clicking on checkbox or its label
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
      e.stopPropagation();
    }
  });
});

let currentPage = 1;
const limit = 5; // jobs per page

function loadJobs(page = 1) {
  fetch(`indexController.php?page=${page}&limit=${limit}`)
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
        jobCard.href = 'apply.html';
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

      // Handle pagination button states
      prevBtn.disabled = data.page <= 1;
      const totalPages = Math.ceil(data.totalJobs / data.limit);
      nextBtn.disabled = data.page >= totalPages;
    })
    .catch(err => {
      console.error('Error loading jobs:', err);
      document.getElementById('jobsList').innerHTML = '<p class="text-danger text-center">Error loading jobs.</p>';
    });
}

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

  // Load first page on startup
  loadJobs(currentPage);
});
