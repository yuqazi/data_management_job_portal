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

  })
  .catch(error => {
    console.error('Error fetching user:', error);
    document.getElementById('username').textContent = 'Error loading user.';
  });
