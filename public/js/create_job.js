document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('createJobForm');
  const message = document.getElementById('formMessage');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Collect job type checkboxes
    const jobTypeCheckboxes = document.querySelectorAll('input[name="jobType"]:checked');
    const jobTypes = Array.from(jobTypeCheckboxes).map(cb => cb.value).join(', ');

    // Build data object
    const data = {
      title: document.getElementById('jobTitle').value,
      description: document.getElementById('jobDescription').value,
      location: document.getElementById('jobLocation').value,
      salary: document.getElementById('salary').value,
      hours: document.getElementById('hours').value,
      jobType: jobTypes
    };

    try {
      // Use absolute path from root with base folder
      const response = await fetch('/index.php/api/create-job', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      // Read raw server output for debugging
      const text = await response.text();
      console.log('SERVER RAW RESPONSE:', text);

      // Avoid invalid JSON crash
      let result = {};
      try {
        result = JSON.parse(text);
      } catch {
        message.innerHTML = `<div class="alert alert-danger">Invalid inputs, Please check your entries.</div>`;
        return;
      }

      if (result.success) {
        message.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
        form.reset();
        // Redirect after 2 seconds
        setTimeout(() => {
          window.location.href = '/data_management_job_portal/index.php/company-profile';
        }, 2000);
      } else {
        message.innerHTML = `<div class="alert alert-danger">${result.message || result.error || 'An error occurred.'}</div>`;
      }
    } catch (error) {
      console.error('FETCH ERROR:', error);
      message.innerHTML = `<div class="alert alert-danger">Failed to connect to the server.</div>`;
    }
  });
});
