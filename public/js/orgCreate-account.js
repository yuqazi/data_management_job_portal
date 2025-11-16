document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("orgCreateForm");

    if (!form) {
        console.error("Form #orgCreateForm not found!");
        return;
    }

    const nameInput = document.getElementById("nameInput");
    const emailInput = document.getElementById("emailInput");
    const phoneInput = document.getElementById("phoneInput");
    const passwordInput = document.getElementById("passwordInput");
    const confirmPasswordInput = document.getElementById("confirmPasswordInput");
    const aboutInput = document.getElementById("aboutInput");
    const passwordError = document.getElementById("passwordError");

    // PHONE FORMATTER
    phoneInput.addEventListener("input", (e) => {
        let digits = e.target.value.replace(/\D/g, "");
        if (digits.length > 10) digits = digits.substring(0, 10);

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

    // Submit Handler
    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // Password validation
        if (passwordInput.value !== confirmPasswordInput.value) {
            passwordError.style.display = "block";
            return;
        } else {
            passwordError.style.display = "none";
        }

        const phoneDigits = phoneInput.value.replace(/\D/g, "");

        const data = {
            name: nameInput.value.trim(),
            email: emailInput.value.trim(),
            phone: phoneDigits,
            password: passwordInput.value,
            location: aboutInput.value.trim(),
        };

        try {
            const response = await fetch("/api/org-create-account", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = "/sign-in";
            } else {
                alert(result.error || "An error occurred.");
            }
        } catch (err) {
            console.error(err);
            alert("Failed to connect to server.");
        }
    });
});
