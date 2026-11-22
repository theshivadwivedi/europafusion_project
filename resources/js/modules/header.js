import Dropdown from 'bootstrap/js/dist/dropdown';
import Offcanvas from 'bootstrap/js/dist/offcanvas';

/**
 * Sticky header state, header-height CSS variable and hover-intent mega menus
 * (hover opens on devices with a fine pointer; click/keyboard always work).
 */
export function initHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    const setHeight = () => document.documentElement.style.setProperty('--ef-header-h', `${header.querySelector('.mainbar').offsetHeight}px`);
    setHeight();
    window.addEventListener('resize', setHeight, { passive: true });

    let ticking = false;
    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            header.classList.toggle('is-scrolled', window.scrollY > 40);
            ticking = false;
        });
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    // Close the mobile menu when one of its plain links is followed (e.g. /services#visa-guidance).
    document.querySelectorAll('.mobile-nav a[href]:not([data-enquiry])').forEach((link) => {
        link.addEventListener('click', () => Offcanvas.getInstance(link.closest('.offcanvas'))?.hide());
    });

    if (!window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

    header.querySelectorAll('.mega').forEach((item) => {
        const toggle = item.querySelector('[data-bs-toggle="dropdown"]');
        const dropdown = Dropdown.getOrCreateInstance(toggle);
        let closeTimer;
        let openedByHover = false;

        // A click on a label whose menu was opened by hovering goes to the section page
        // (instead of closing the menu). Keyboard users still open/close it normally.
        toggle.addEventListener('click', (event) => {
            if (openedByHover && toggle.dataset.href) {
                event.preventDefault();
                event.stopImmediatePropagation();
                window.location.href = toggle.dataset.href;
            }
        }, true);
        item.addEventListener('hidden.bs.dropdown', () => { openedByHover = false; });

        item.addEventListener('mouseenter', () => {
            clearTimeout(closeTimer);
            header.querySelectorAll('.mega [data-bs-toggle="dropdown"][aria-expanded="true"]').forEach((other) => {
                if (other !== toggle) Dropdown.getOrCreateInstance(other).hide();
            });
            if (!item.querySelector('.dropdown-menu.show')) openedByHover = true;
            dropdown.show();
        });
        item.addEventListener('mouseleave', () => {
            closeTimer = setTimeout(() => dropdown.hide(), 180);
        });
    });
}
