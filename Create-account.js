document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Make sure passwords match
        const password = document.getElementById('passwordInput').value;
        const confirmPassword = document.getElementById('confirmPasswordInput').value;

        if (password !== confirmPassword) {
            alert('Passwords do not match');
            return;
        }

        // Get selected skills (assuming your tags are already handled)
        const skillContainer = document.getElementById('skillTags');
        const selectedSkills = Array.from(skillContainer.querySelectorAll('input:checked'))
                                    .map(input => input.nextElementSibling.textContent);

        const payload = {
            name: document.getElementById('nameInput').value,
            email: document.getElementById('emailInput').value,
            password: password,
            skills: selectedSkills
        };

        try {
            const res = await fetch('SignUpController.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(payload)
            });

            const data = await res.json();
            alert(data.message);

            if (data.success) form.reset();
        } catch (err) {
            console.error(err);
            alert('Error creating account');
        }
    });
});
