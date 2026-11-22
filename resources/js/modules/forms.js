/**
 * Shared AJAX form submission with Laravel validation error rendering.
 * Forms still work without JavaScript (normal POST + redirect).
 */
const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

export function clearErrors(form) {
    form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback[data-js]').forEach((el) => el.remove());
    form.querySelector('[data-form-alert]')?.replaceChildren();
}

function fieldFor(form, key) {
    // "interests.0" -> "interests[]"
    const base = key.split('.')[0];
    return form.querySelector(`[name="${key}"]`) || form.querySelector(`[name="${base}"]`) || form.querySelector(`[name="${base}[]"]`);
}

export function showErrors(form, errors) {
    let first = null;
    Object.entries(errors).forEach(([key, messages]) => {
        const field = fieldFor(form, key);
        if (!field) return;
        const group = field.closest('[data-field]') || field.parentElement;
        if (field.type !== 'checkbox') field.classList.add('is-invalid');
        if (!group.querySelector('.invalid-feedback[data-js]')) {
            const fb = document.createElement('div');
            fb.className = 'invalid-feedback d-block';
            fb.dataset.js = '';
            fb.textContent = messages[0];
            group.appendChild(fb);
        }
        first ??= field;
    });
    first?.focus({ preventScroll: false });
}

function showAlert(form, message) {
    const box = form.querySelector('[data-form-alert]');
    if (!box) return;
    const div = document.createElement('div');
    div.className = 'alert-ef is-error mb-3';
    div.setAttribute('role', 'alert');
    div.innerHTML = '<i class="bi bi-exclamation-circle" aria-hidden="true"></i>';
    const span = document.createElement('span');
    span.textContent = message;
    div.appendChild(span);
    box.replaceChildren(div);
}

/** Submit a form via fetch. Resolves with the JSON body on success, null on handled failure. */
export async function submitForm(form) {
    clearErrors(form);
    form.classList.add('is-submitting');
    form.setAttribute('aria-busy', 'true');

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: { Accept: 'application/json', 'X-CSRF-TOKEN': csrf(), 'X-Requested-With': 'XMLHttpRequest' },
            body: new FormData(form),
            credentials: 'same-origin',
        });
        const data = await response.json().catch(() => ({}));

        if (response.ok) return data;
        if (response.status === 422) {
            showErrors(form, data.errors || {});
            showAlert(form, 'Please check the highlighted fields and try again.');
        } else if (response.status === 429) {
            showAlert(form, 'You have sent several requests in a short time. Please wait a minute and try again, or call us directly.');
        } else if (response.status === 419) {
            showAlert(form, 'Your session expired. Please refresh the page and submit again.');
        } else {
            showAlert(form, 'Something went wrong on our side. Please try again, or reach us on WhatsApp.');
        }
    } catch {
        showAlert(form, 'We could not reach the server. Please check your connection and try again.');
    } finally {
        form.classList.remove('is-submitting');
        form.removeAttribute('aria-busy');
    }
    return null;
}

/** Inline page forms (contact, B2B): swap the form for its success panel. */
export function initAjaxForms() {
    document.querySelectorAll('form[data-ajax-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const data = await submitForm(form);
            if (!data) return;

            const success = document.getElementById(form.dataset.ajaxForm);
            if (success) {
                success.querySelectorAll('[data-fill]').forEach((el) => {
                    const value = data[el.dataset.fill];
                    if (value) { el.textContent = value; el.hidden = false; }
                    else if (el.dataset.hideEmpty !== undefined) el.hidden = true;
                });
                form.hidden = true;
                success.hidden = false;
                success.focus();
                success.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            form.reset();
        });
    });
}
