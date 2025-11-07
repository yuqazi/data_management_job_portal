document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");

    const nameInput = document.getElementById("nameInput");
    const emailInput = document.getElementById("emailInput");
    const phoneInput = document.getElementById("phoneInput");
    const passwordInput = document.getElementById("passwordInput");
    const confirmPasswordInput = document.getElementById("confirmPasswordInput");
    const aboutInput = document.getElementById("aboutInput");
    const passwordError = document.getElementById("passwordError");

    // PHONE NUMBER: allow only digits and format as (123) 456-7890
    phoneInput.addEventListener("input", (e) => {
        let digits = e.target.value.replace(/\D/g, ""); // remove non-digits
        if (digits.length > 10) digits = digits.substring(0, 10); // limit to 10 digits

        let formatted = digits;
        if (digits.length > 6) {
            formatted = `(${digits.substring(0, 3)}) ${digits.substring(3, 6)}-${digits.substring(6)}`;
        } else if (digits.length > 3) {
            formatted = `(${digits.substring(0, 3)}) ${digits.substring(3)}`;
        } else if (digits.length > 0) {
            formatted = `(${digits}`;
        }

        e.target.value = formatted;
    });

    // Hide all errors when user starts typing again
    form.querySelectorAll("input, textarea").forEach(input => {
        input.addEventListener("input", () => {
            const errorDiv = input.parentElement.querySelector(".error-text");
            if (errorDiv) errorDiv.style.display = "none";
        });
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Clear all old error messages
        form.querySelectorAll(".error-text").forEach(div => div.style.display = "none");

        let hasError = false;

        // Validation
        if (nameInput.value.trim() === "") {
            showError(nameInput, "Name is required.");
            hasError = true;
        }

        if (emailInput.value.trim() === "") {
            showError(emailInput, "Email is required.");
            hasError = true;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            showError(emailInput, "Please enter a valid email address.");
            hasError = true;
        }

        const phoneDigits = phoneInput.value.replace(/\D/g, "");
        if (phoneDigits.length !== 10) {
            showError(phoneInput, "Please enter a valid 10-digit phone number.");
            hasError = true;
        }

        if (passwordInput.value.length < 6) {
            showError(passwordInput, "Password must be at least 6 characters long.");
            hasError = true;
        }

        if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.style.display = "block";
            hasError = true;
        } else {
            passwordError.style.display = "none";
        }

        if (aboutInput.value.trim() === "") {
            showError(aboutInput, "Please write something about yourself.");
            hasError = true;
        }

        if (hasError) return;

        // Collect skills
        const skillElements = document.querySelectorAll("#skillTags input[type='checkbox']:checked");
        const skills = Array.from(skillElements).map(el => el.value);

        const data = {
            name: nameInput.value.trim(),
            email: emailInput.value.trim(),
            phone: phoneDigits, // only store numeric digits
            password: passwordInput.value,
            about: aboutInput.value.trim(),
            skills,
        };

        try {
            // Post to the controller using an absolute path so the request resolves
            // correctly from the web root.
            const response = await fetch("/app/Controllers/Create-accountController.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
                // Redirect to the index/search page after successful account creation
                window.location.href = "/index.html";
            } else {
                showError(emailInput, result.error || "An error occurred.");
            }
        } catch (err) {
            console.error(err);
            showError(emailInput, "Failed to connect to the server.");
        }
    });

    // Function to display inline errors
    function showError(inputElement, message) {
        let errorDiv = inputElement.parentElement.querySelector(".error-text");
        if (!errorDiv) {
            errorDiv = document.createElement("div");
            errorDiv.className = "error-text text-danger mt-1";
            errorDiv.style.fontSize = "0.9em";
            inputElement.parentElement.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
        errorDiv.style.display = "block";
    }
});
