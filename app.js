document.querySelectorAll('.dropdown-menu').forEach(menu => {
  menu.addEventListener('click', function (e) {
    // Only prevent closing when clicking on checkbox or its label
    if (e.target.tagName === 'INPUT' || e.target.tagName === 'LABEL') {
      e.stopPropagation();
    }
  });
});

  const range = document.getElementById('priceRange');
  const value = document.getElementById('priceValue');

  range.addEventListener('input', () => {
    value.textContent = range.value;
  });