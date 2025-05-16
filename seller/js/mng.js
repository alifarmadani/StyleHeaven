const openForm = document.getElementById('openForm');
const closeForm = document.getElementById('closeForm');
const overlayForm = document.getElementById('overlayForm');

openForm.addEventListener('click', () => {
    overlayForm.style.display = 'flex';
});

closeForm.addEventListener('click', () => {
    overlayForm.style.display = 'none';
});

window.addEventListener('click', (e) => {
    if (e.target === overlayForm) {
        overlayForm.style.display = 'none';
    }
});
