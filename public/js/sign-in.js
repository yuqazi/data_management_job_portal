document.getElementById('signinForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('emailInput').value;
    const password = document.getElementById('passwordInput').value;
    const message = document.getElementById('signinMessage');

    try {
        const res = await fetch('sign-inController.php', { // make sure file name matches
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if (data.success) {
            message.textContent = 'Login successful! Redirecting...';
            message.className = 'text-success';
            setTimeout(() => window.location.href = 'index.html', 1000);
        } else {
            message.textContent = data.message;
            message.className = 'text-danger';
        }
    } catch (err) {
        message.textContent = 'Error signing in. Please try again.';
        message.className = 'text-danger';
    }
});
