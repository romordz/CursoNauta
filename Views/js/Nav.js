

document.querySelectorAll('.profile-toggle').forEach(element => {
    element.addEventListener('click', function(e) {
        e.preventDefault(); // Evitar que el enlace redirija inmediatamente
        const dropdownMenu = document.querySelector('.dropdown-menu');
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });
});

// Cierra el menú si se hace clic fuera de él
window.addEventListener('click', function(e) {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const profileToggle = document.querySelector('.profile-toggle');

    if (!profileToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
        dropdownMenu.style.display = 'none';
    }
});
