document.addEventListener("DOMContentLoaded", () => {
  const ctx = document.getElementById('skillsChart').getContext('2d');
  let skillsChartInstance = null; // keep a reference to the chart

  function loadSkillsChart() {
    fetch('/app/Controllers/chartController.php')
      .then(res => res.json())
      .then(data => {
        if (!Array.isArray(data) || data.length === 0) {
          ctx.canvas.parentElement.innerHTML = '<p class="text-center text-muted">No skill data available.</p>';
          return;
        }

        const skills = data.map(item => item.skill);
        const counts = data.map(item => Number(item.people_count));

        // Destroy previous chart if it exists
        if (skillsChartInstance) {
          skillsChartInstance.destroy();
        }

        // Create new chart
        skillsChartInstance = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: skills,
            datasets: [{
              label: 'Number of Jobs Requiring Skill',
              data: counts,
              backgroundColor: 'rgba(54, 162, 235, 0.6)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: {
                display: true,
                position: 'top'
              }
            },
            scales: {
              y: {
                beginAtZero: true,
                precision: 0
              }
            }
          }
        });
      })
      .catch(err => {
        console.error('Error loading skills chart:', err);
        ctx.canvas.parentElement.innerHTML = '<p class="text-danger text-center">Error loading skills chart.</p>';
      });
  }

  // Load chart initially
  loadSkillsChart();
});
