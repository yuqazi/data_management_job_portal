document.getElementById('signinForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('emailInput').value;
    const password = document.getElementById('passwordInput').value;
    const message = document.getElementById('signinMessage');

    try {
        // Call the sign-in controller via absolute path so the request resolves
        // correctly from the web root.
        const res = await fetch('/app/Controllers/sign-inController.php', { // make sure file name matches
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });

        const data = await res.json();

        if (data.success) {
            message.textContent = 'Login successful! Redirecting...';
            message.className = 'text-success';
            // Use absolute path for redirect to match other pages
            setTimeout(() => window.location.href = 'resources/views/index.html', 1000);
        } else {
            message.textContent = data.message;
            message.className = 'text-danger';
        }
    } catch (err) {
        message.textContent = 'Error signing in. Please try again.';
        message.className = 'text-danger';
    }
});
