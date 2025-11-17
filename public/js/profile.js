// Example: select which user to load
const userId = 2; // Change this to 1, 2, etc. to test different users
// Use the front controller (`index.php`) so Apache routes requests correctly
const API_URL = `/index.php/api/profile`;

// Function to fetch and display user profile data
function loadUserProfile() {
  fetch(`${API_URL}?id=${userId}`)
    .then(response => {
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
      return response.json();
    })
    .then(user => {
      displayUserProfile(user);
    })
    .catch(error => {
      console.error('Error fetching user:', error);
      document.getElementById('username').textContent = 'Error loading user.';
    });
}

// Function to display the user profile data in the DOM
function displayUserProfile(user) {
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
    const jobs = user.jobs || [];
    jobs.forEach(job => {
      const title = job.title || 'Untitled Position';
      const duration = job.duration || '';

      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      let html = `<h5>${title}</h5>`;
      if (duration) html += `<p><em>${duration}</em></p>`;
      li.innerHTML = html;
      jobsList.appendChild(li);
    });
  }

  const certificationsList = document.getElementById('certificationsList');
  if (certificationsList) {
    certificationsList.innerHTML = '';
    const certs = user.certifications || [];
    certs.forEach(cert => {
      const name = cert.certificate || 'Certification';

      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      li.innerHTML = `<h5>${name}</h5>`;
      certificationsList.appendChild(li);
    });
  }
}

// Load profile on page load
loadUserProfile();

// Edit Description Handler
document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'editDescriptionBtn'){
      const descriptionEl = document.getElementById('userDescription');
      const currentText = descriptionEl.textContent.trim();

      descriptionEl.innerHTML = `
        <textarea id="descriptionTextarea" class="form-control" rows="4">${currentText}</textarea>
        <button id="saveDescriptionBtn" class="btn btn-primary btn-sm mt-2">Save</button>
      `;
  }

  if(event.target && event.target.id === 'saveDescriptionBtn'){
      const textarea = document.getElementById('descriptionTextarea');
      const newText = textarea.value.trim();

      // Send update to backend
      fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'updateAbout',
          about: newText
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const descriptionEl = document.getElementById('userDescription');
          descriptionEl.textContent = newText;
          console.log('Description updated successfully');
        } else {
          alert('Failed to update description: ' + data.message);
        }
      })
      .catch(error => console.error('Error updating description:', error));
    }
});

// Add Work Experience Handler
document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'addExperienceBtn'){
      const jobsList = document.getElementById('jobsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      li.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Job Title" id="newJobTitle">
        <input type="text" class="form-control mb-2" placeholder="Duration" id="newJobDuration">
        <button id="saveJobBtn" class="btn btn-primary btn-sm">Save</button>
      `;
      jobsList.appendChild(li);
  }

  if(event.target && event.target.id === 'saveJobBtn'){
      const title = document.getElementById('newJobTitle').value.trim();
      const duration = document.getElementById('newJobDuration').value.trim();
      
      if (!title || !duration) {
        alert('Please fill in all fields');
        return;
      }

      // Send to backend
      fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'addExperience',
          title: title,
          duration: duration
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const jobsList = document.getElementById('jobsList');
          const li = document.createElement('li');
          li.className = 'list-group-item mb-3';
          li.innerHTML = `<h5>${title}</h5><p><em>${duration}</em></p>`;
          jobsList.replaceChild(li, event.target.parentElement);
          console.log('Work experience added successfully');
        } else {
          alert('Failed to add work experience: ' + data.message);
        }
      })
      .catch(error => console.error('Error adding work experience:', error));
  }
});

// Add Certification Handler
document.addEventListener('click', function(event){
  if(event.target && event.target.id === 'addCertificationBtn'){
      const certificationsList = document.getElementById('certificationsList');
      const li = document.createElement('li');
      li.className = 'list-group-item mb-3';
      li.innerHTML = `
        <input type="text" class="form-control mb-2" placeholder="Certification Name" id="newCertName">
        <button id="saveCertBtn" class="btn btn-primary btn-sm">Save</button>
      `;
      certificationsList.appendChild(li);
  }
  
  if(event.target && event.target.id === 'saveCertBtn'){
      const name = document.getElementById('newCertName').value.trim();
      
      if (!name) {
        alert('Please enter a certification name');
        return;
      }

      // Send to backend
      fetch(API_URL, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({
          action: 'addCertification',
          certificate: name
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const certificationsList = document.getElementById('certificationsList');
          const li = document.createElement('li');
          li.className = 'list-group-item mb-3';
          li.innerHTML = `<h5>${name}</h5>`;
          certificationsList.replaceChild(li, event.target.parentElement);
          console.log('Certification added successfully');
        } else {
          alert('Failed to add certification: ' + data.message);
        }
      })
      .catch(error => console.error('Error adding certification:', error));
  }
});