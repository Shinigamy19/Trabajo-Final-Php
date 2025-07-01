document.addEventListener('DOMContentLoaded', function () {
    const body = document.body;
    const darkMode = localStorage.getItem('darkMode');

    if (darkMode === 'on') {
        body.classList.add('dark-mode');
    } else {
        body.classList.remove('dark-mode');
    }

    const switchInput = document.getElementById('darkModeSwitch');
    if (!switchInput) return;

    switchInput.checked = darkMode === 'on';

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