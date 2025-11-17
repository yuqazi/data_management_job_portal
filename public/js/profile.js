// =============================================
//  GET USER ID FROM URL
// =============================================
const urlParams = new URLSearchParams(window.location.search);
const userId = urlParams.get("id");

// If no user ID is in the URL, stop everything
if (!userId) {
  document.addEventListener("DOMContentLoaded", () => {
    document.getElementById("username").textContent = "Invalid user ID.";
  });
}

// API endpoint (using front controller routing)
const API_URL = `/index.php/api/profile`;

// =============================================
//  LOAD USER PROFILE
// =============================================
function loadUserProfile() {
  fetch(`${API_URL}?id=${userId}`)
    .then(response => {
      if (!response.ok) {
        throw new Error("Failed to load user");
      }
      return response.json();
    })
    .then(user => {
      displayUserProfile(user);
    })
    .catch(error => {
      console.error("Error fetching user:", error);
      document.getElementById("username").textContent = "Error loading profile.";
    });
}

// =============================================
//  DISPLAY USER PROFILE IN HTML
// =============================================
function displayUserProfile(user) {
  document.getElementById("username").textContent = user.name;
  document.getElementById("userDescription").textContent = user.description;
  document.getElementById("userEmail").innerHTML = `<strong>Email:</strong> ${user.email}`;
  document.getElementById("userNumber").innerHTML = `<strong>Phone Number:</strong> ${user.phone}`;

  // --- Skills ---
  const skillsList = document.getElementById("skillsList");
  if (skillsList && user.skills) {
    skillsList.innerHTML = "";
    user.skills.forEach(skill => {
      const li = document.createElement("li");
      li.className = "list-group-item p-2 m-1 border border-dark rounded-pill";
      li.textContent = skill;
      skillsList.appendChild(li);
    });
  }

  // --- Jobs ---
  const jobsList = document.getElementById("jobsList");
  if (jobsList) {
    jobsList.innerHTML = "";
    (user.jobs || []).forEach(job => {
      const li = document.createElement("li");
      li.className = "list-group-item mb-3";
      li.innerHTML = `<h5>${job.title || "Untitled Position"}</h5>
                      <p><em>${job.duration || ""}</em></p>`;
      jobsList.appendChild(li);
    });
  }

  // --- Certifications ---
  const certificationsList = document.getElementById("certificationsList");
  if (certificationsList) {
    certificationsList.innerHTML = "";
    (user.certifications || []).forEach(cert => {
      const li = document.createElement("li");
      li.className = "list-group-item mb-3";
      li.innerHTML = `<h5>${cert.certificate || "Certification"}</h5>`;
      certificationsList.appendChild(li);
    });
  }
}

// Load profile on page load
loadUserProfile();

// =============================================
//  EDIT DESCRIPTION
// =============================================
document.addEventListener("click", function (event) {
  if (event.target && event.target.id === "editDescriptionBtn") {
    const descriptionEl = document.getElementById("userDescription");
    const currentText = descriptionEl.textContent.trim();

    descriptionEl.innerHTML = `
      <textarea id="descriptionTextarea" class="form-control" rows="4">${currentText}</textarea>
      <button id="saveDescriptionBtn" class="btn btn-primary btn-sm mt-2">Save</button>
    `;
  }

  if (event.target && event.target.id === "saveDescriptionBtn") {
    const textarea = document.getElementById("descriptionTextarea");
    const newText = textarea.value.trim();

    fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        action: "updateAbout",
        about: newText,
        id: userId
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          document.getElementById("userDescription").textContent = newText;
        } else {
          alert("Failed to update description: " + data.message);
        }
      })
      .catch(error => console.error("Error updating description:", error));
  }
});

// =============================================
//  ADD EXPERIENCE
// =============================================
document.addEventListener("click", function (event) {
  if (event.target && event.target.id === "addExperienceBtn") {
    const jobsList = document.getElementById("jobsList");
    const li = document.createElement("li");
    li.className = "list-group-item mb-3";
    li.innerHTML = `
      <input type="text" class="form-control mb-2" placeholder="Job Title" id="newJobTitle">
      <input type="text" class="form-control mb-2" placeholder="Duration" id="newJobDuration">
      <button id="saveJobBtn" class="btn btn-primary btn-sm">Save</button>
    `;
    jobsList.appendChild(li);
  }

  if (event.target && event.target.id === "saveJobBtn") {
    const title = document.getElementById("newJobTitle").value.trim();
    const duration = document.getElementById("newJobDuration").value.trim();

    if (!title || !duration) {
      alert("Please fill in all fields");
      return;
    }

    fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        action: "addExperience",
        title: title,
        duration: duration,
        id: userId
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const li = document.createElement("li");
          li.className = "list-group-item mb-3";
          li.innerHTML = `<h5>${title}</h5><p><em>${duration}</em></p>`;
          event.target.parentElement.replaceWith(li);
        } else {
          alert("Failed to add work experience: " + data.message);
        }
      })
      .catch(error => console.error("Error adding work experience:", error));
  }
});

// =============================================
//  ADD CERTIFICATION
// =============================================
document.addEventListener("click", function (event) {
  if (event.target && event.target.id === "addCertificationBtn") {
    const certificationsList = document.getElementById("certificationsList");
    const li = document.createElement("li");
    li.className = "list-group-item mb-3";
    li.innerHTML = `
      <input type="text" class="form-control mb-2" placeholder="Certification Name" id="newCertName">
      <button id="saveCertBtn" class="btn btn-primary btn-sm">Save</button>
    `;
    certificationsList.appendChild(li);
  }

  if (event.target && event.target.id === "saveCertBtn") {
    const name = document.getElementById("newCertName").value.trim();

    if (!name) {
      alert("Please enter a certification name");
      return;
    }

    fetch(API_URL, {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        action: "addCertification",
        certificate: name,
        id: userId
      })
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          const li = document.createElement("li");
          li.className = "list-group-item mb-3";
          li.innerHTML = `<h5>${name}</h5>`;
          event.target.parentElement.replaceWith(li);
        } else {
          alert("Failed to add certification: " + data.message);
        }
      })
      .catch(error => console.error("Error adding certification:", error));
  }
});
