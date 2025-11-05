// Example: select which user to load
const userId = 2; // Change this to 1, 2, etc. to test different users

fetch(`profileController.php?id=${userId}`)
  .then(response => {
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    return response.json();
  })
  .then(user => {
    document.getElementById('username').textContent = user.name;
    document.getElementById('userDescription').textContent = user.description;
    document.getElementById('userEmail').innerHTML = `<strong>Email:</strong> ${user.email}`;
    document.getElementById('userNumber').innerHTML = `<strong>Phone Number:</strong> ${user.phone}`;

    const skillsList = document.getElementById('skillsList');
    if (skillsList && user.skills) {
      skillsList.innerHTML = '';

      user.skills.forEach(skill => {
        const li = document.createElement('li');
        li.className = 'list-group-item p-2 m-1 border border-dark rounded-pill';
        li.textContent = skill;
        skillsList.appendChild(li);
      });
    }

    const jobsList = document.getElementById('jobsList');
    if (jobsList) {
      jobsList.innerHTML = '';
      // Support both older `appliedJobs` shape and current `jobs` shape
      const jobs = user.appliedJobs || user.jobs || [];
      jobs.forEach(job => {
        const title = job.title || job.jobTitle || 'Untitled Position';
        const company = job.company || job.employer || '';
        // duration (model uses 'duration') or appliedDate (older model)
        const duration = job.duration || job.appliedDate || job.date || '';
        const description = job.description || job.details || '';

        const li = document.createElement('li');
        li.className = 'list-group-item mb-3';
        let html = `<h5>${title}` + (company ? ` at ${company}` : '') + `</h5>`;
        if (duration) html += `<p><em>${duration}</em></p>`;
        if (description) html += `<p>${description}</p>`;
        li.innerHTML = html;
        jobsList.appendChild(li);
      });
    }

    const certificationsList = document.getElementById('certificationsList');
    if (certificationsList) {
      certificationsList.innerHTML = '';
      const certs = user.certifications || [];
      certs.forEach(cert => {
        const name = cert.name || cert.title || 'Certification';
        const issuer = cert.issuer || cert.issuedBy || '';
        const year = cert.year || cert.issueDate || cert.issueYear || '';
        const description = cert.description || '';

        const li = document.createElement('li');
        li.className = 'list-group-item mb-3';
        let html = `<h5>${name}</h5>`;
        if (issuer || year) html += `<p><em>${issuer}${issuer && year ? ' — ' : ''}${year}</em></p>`;
        if (description) html += `<p>${description}</p>`;
        li.innerHTML = html;
        certificationsList.appendChild(li);
      });
    }

  })
  .catch(error => {
    console.error('Error fetching user:', error);
    document.getElementById('username').textContent = 'Error loading user.';
  });
