document.addEventListener('DOMContentLoaded', function () {
    const switchInput = document.getElementById('darkModeSwitch');
    const body = document.body;

    if (!switchInput) return; // Evita errores si el switch no existe

    // Inicializar el switch según preferencia guardada
    if (localStorage.getItem('darkMode') === 'on') {
        body.classList.add('dark-mode');
        switchInput.checked = true;
    }

    switchInput.addEventListener('change', function () {
        if (this.checked) {
            body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'on');
        } else {
            body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'off');
        }
    });
});