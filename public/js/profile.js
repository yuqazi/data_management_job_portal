// Example: select which user to load
const userId = 2; // Change this to 1, 2, etc. to test different users

// Fetch profile data from the backend controller using an absolute path
// so the request resolves correctly from the web root.
fetch(`/app/Controllers/profileController.php?id=${userId}`)
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

document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'editDescriptionBtn'){
      // Handle edit description button click
      const descriptionEl = document.getElementById('userDescription');
      const currentText = descriptionEl.textContent.trim();

      descriptionEl.innerHTML = `
        <textarea id="descriptionTextarea" class="form-control" rows="4">${currentText}</textarea>
        <button id="saveDescriptionBtn" class="btn btn-primary btn-sm mt-2">Save</button>
      `;
  }

  if(event.target && event.target.id === 'saveDescriptionBtn'){
      // Handle save description button click
      const textarea = document.getElementById('descriptionTextarea');
      const newText = textarea.value.trim();
      const descriptionEl = document.getElementById('userDescription');
      descriptionEl.textContent = newText;
    }
});

document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'addExperienceBtn'){
      // Handle add work experience button click
      const jobsList = document.getElementById('jobsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      li.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Job Title" id="newJobTitle">
        <input type="text" class="form-control mb-2" placeholder="Company" id="newJobCompany">
        <input type="text" class="form-control mb-2" placeholder="Duration" id="newJobDuration">
        <textarea class="form-control mb-2" rows="3" placeholder="Description" id="newJobDescription"></textarea>
        <button id="saveJobBtn" class="btn btn-primary btn-sm">Save</button>
      `;
      jobsList.appendChild(li);
  }

  if(event.target && event.target.id === 'saveJobBtn'){
      const title = document.getElementById('newJobTitle').value.trim();
      const company = document.getElementById('newJobCompany').value.trim();
      const duration = document.getElementById('newJobDuration').value.trim();
      const description = document.getElementById('newJobDescription').value.trim();
      const jobsList = document.getElementById('jobsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      let html = `<h5>${title}` + (company ? ` at ${company}` : '') + `</h5>`;
      if (duration) html += `<p><em>${duration}</em></p>`;
      if (description) html += `<p>${description}</p>`;
      li.innerHTML = html;
      jobsList.replaceChild(li, event.target.parentElement);
  }
});

document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'addCertificationBtn'){
      // Handle add certification button click
      const certificationsList = document.getElementById('certificationsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      li.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Certification Name" id="newCertName">
        <input type="text" class="form-control mb-2" placeholder="Issuer" id="newCertIssuer">
        <input type="text" class="form-control mb-2" placeholder="Year" id="newCertYear">
        <textarea class="form-control mb-2" rows="3" placeholder="Description" id="newCertDescription"></textarea>
        <button id="saveCertBtn" class="btn btn-primary btn-sm">Save</button>
      `;
      certificationsList.appendChild(li);
  }
  if(event.target && event.target.id === 'saveCertBtn'){
      const name = document.getElementById('newCertName').value.trim();
      const issuer = document.getElementById('newCertIssuer').value.trim();
      const year = document.getElementById('newCertYear').value.trim();
      const description = document.getElementById('newCertDescription').value.trim();
      const certificationsList = document.getElementById('certificationsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      let html = `<h5>${name}</h5>`;
      if (issuer || year) html += `<p><em>${issuer}${issuer && year ? ' — ' : ''}${year}</em></p>`;
      if (description) html += `<p>${description}</p>`;
      li.innerHTML = html;
      certificationsList.replaceChild(li, event.target.parentElement);
  }
});