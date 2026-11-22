import Modal from 'bootstrap/js/dist/modal';
import Offcanvas from 'bootstrap/js/dist/offcanvas';
import { submitForm, clearErrors } from './forms';

const COPY = {
    counselling: {
        title: 'Book a free counselling session',
        lead: 'Tell us about your background. We understand the student first and recommend the pathway second.',
        submit: 'Book my free session',
    },
    evaluation: {
        title: 'Get a free profile evaluation',
        lead: 'Share your academic profile and we will map realistic universities, programmes and timelines for you.',
        submit: 'Evaluate my profile',
    },
};

/**
 * Counselling / profile-evaluation modal. Any element with data-enquiry="counselling|evaluation"
 * opens it (optional data-country preselects the destination). Links keep a /contact fallback href.
 */
export function initEnquiryModal() {
    const el = document.getElementById('enquiryModal');
    if (!el) return;

    const modal = Modal.getOrCreateInstance(el);
    const form = el.querySelector('form');
    const formView = el.querySelector('[data-view="form"]');
    const successView = el.querySelector('[data-view="success"]');
    const typeInput = form.querySelector('[name="type"]');
    const countrySelect = form.querySelector('[name="preferred_country"]');

    const setType = (type) => {
        const t = COPY[type] ? type : 'counselling';
        typeInput.value = t;
        el.querySelector('[data-copy="title"]').textContent = COPY[t].title;
        el.querySelector('[data-copy="lead"]').textContent = COPY[t].lead;
        el.querySelector('[data-copy="submit"]').textContent = COPY[t].submit;
        el.querySelectorAll('[data-switch]').forEach((b) => {
            const active = b.dataset.switch === t;
            b.classList.toggle('is-active', active);
            b.setAttribute('aria-pressed', String(active));
        });
    };

    const reset = () => {
        formView.hidden = false;
        successView.hidden = true;
        clearErrors(form);
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-enquiry]');
        if (!trigger) return;
        event.preventDefault();
        reset();
        setType(trigger.dataset.enquiry);
        const country = trigger.dataset.country || document.body.dataset.country;
        if (country && countrySelect) {
            const option = [...countrySelect.options].find((o) => o.value === country);
            if (option) countrySelect.value = country;
        }
        // Opened from the mobile menu or another modal: close that first so only one overlay owns focus.
        const drawer = trigger.closest('.offcanvas.show');
        const other = trigger.closest('.modal.show');
        if (drawer) {
            drawer.addEventListener('hidden.bs.offcanvas', () => modal.show(), { once: true });
            Offcanvas.getInstance(drawer)?.hide();
        } else if (other && other !== el) {
            const otherModal = Modal.getOrCreateInstance(other);
            const tryHide = () => otherModal.hide();
            other.addEventListener('hidden.bs.modal', () => {
                other.removeEventListener('shown.bs.modal', tryHide);
                modal.show();
            }, { once: true });
            // Bootstrap ignores hide() while the other modal is still animating in: retry once it settles.
            other.addEventListener('shown.bs.modal', tryHide, { once: true });
            tryHide();
        } else {
            modal.show(trigger);
        }
    });

    el.querySelectorAll('[data-switch]').forEach((b) => b.addEventListener('click', () => setType(b.dataset.switch)));
    el.addEventListener('shown.bs.modal', () => form.querySelector('[name="full_name"]')?.focus());

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        form.querySelector('[name="source_url"]').value = window.location.href;
        const data = await submitForm(form);
        if (!data) return;

        const country = countrySelect?.value;
        successView.querySelector('[data-fill="name"]').textContent = data.name || 'there';
        const ref = successView.querySelector('[data-fill="reference"]');
        ref.textContent = data.reference ? `Reference ${data.reference}` : '';
        ref.hidden = !data.reference;
        successView.querySelector('[data-fill="country"]').textContent = country || 'your chosen destination';
        formView.hidden = true;
        successView.hidden = false;
        successView.focus();
        form.reset();
        setType(typeInput.value);
    });
}
