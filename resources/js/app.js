import '../scss/app.scss';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@fontsource-variable/fraunces/opsz.css';
import '@fontsource-variable/fraunces/opsz-italic.css';
import '@fontsource-variable/manrope';

import { initHeader } from './modules/header';
import { initEnquiryModal } from './modules/enquiry-modal';
import { initAjaxForms } from './modules/forms';
import { initDestinationModal } from './modules/destinations';
import { initReveal, initScrollSpy, initBackToTop } from './modules/ui';

document.addEventListener('DOMContentLoaded', () => {
    initHeader();
    initEnquiryModal();
    initAjaxForms();
    initDestinationModal();
    initReveal();
    initScrollSpy();
    initBackToTop();
});
