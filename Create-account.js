document.addEventListener('DOMContentLoaded', function() {
    const skillTags = document.getElementById('skillTags');

    skillTags.addEventListener('change', function(event) {
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
