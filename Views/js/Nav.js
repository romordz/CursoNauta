document.querySelectorAll('.profile-toggle').forEach(element => {
    element.addEventListener('click', function(e) {
        e.preventDefault();
        const dropdownMenu = document.querySelector('.dropdown-menu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
});

window.addEventListener('click', function(e) {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const profileToggle = document.querySelector('.profile-toggle');

    if (!profileToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.style.display = 'none';
    }
});
