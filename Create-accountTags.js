document.addEventListener('DOMContentLoaded', async function() {
    const skillContainer = document.getElementById('skillTags');

    try {
        // Fetch tags from controller
        const res = await fetch('tagController.php');
        const tags = await res.json();

        // Create checkboxes and labels dynamically
        tags.forEach((tag, index) => {
            // Create checkbox input
            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.className = 'btn-check';
            checkbox.id = `skill${index}`;
            checkbox.autocomplete = 'off';

            // Create label styled as pill
            const label = document.createElement('label');
            label.className = 'btn border-dark rounded-pill px-3';
            label.htmlFor = `skill${index}`;
            label.textContent = tag;

            skillContainer.appendChild(checkbox);
            skillContainer.appendChild(label);
        });
    } catch (err) {
        console.error('Failed to load tags', err);
    }

    // Handle toggling btn-primary when checkbox changes
    skillContainer.addEventListener('change', function(event) {
        const checkbox = event.target;

        if (checkbox.classList.contains('btn-check')) {
            const label = checkbox.nextElementSibling;
            if (checkbox.checked) {
                label.classList.add('btn-primary');
            } else {
                label.classList.remove('btn-primary');
            }
        }
    });
});