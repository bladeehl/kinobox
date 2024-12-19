
function toggleDropdown() {
    const dropdown = document.getElementById('user-dropdown');
    dropdown.classList.toggle('show');
}

// Закрываем меню при клике вне его области
window.onclick = function(event) {
    const dropdown = document.getElementById('user-dropdown');
    if (!event.target.matches('.user-icon')) {
        if (dropdown && dropdown.classList.contains('show')) {
            dropdown.classList.remove('show');
        }
    }
}
