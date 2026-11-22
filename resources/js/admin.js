import '../scss/admin.scss';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fontsource-variable/fraunces';
import '@fontsource-variable/manrope';

// Confirm destructive actions.
document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-confirm]');
    if (form && !window.confirm(form.dataset.confirm)) event.preventDefault();
});
