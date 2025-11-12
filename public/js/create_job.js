document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('createJobForm');
  const message = document.getElementById('formMessage');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(form);

    try {
      const response = await fetch('/app/Controllers/jobController.php', {
        method: 'POST',
        body: formData
      });

      const result = await response.json();

      if (result.success) {
        message.innerHTML = `<div class="alert alert-success">${result.message}</div>`;
        form.reset();
      } else {
        message.innerHTML = `<div class="alert alert-danger">${result.message}</div>`;
      }
    } catch (error) {
      console.error(error);
      message.innerHTML = `<div class="alert alert-danger">Error submitting job. Please try again later.</div>`;
    }
  });
});
