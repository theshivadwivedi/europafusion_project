import ScrollSpy from 'bootstrap/js/dist/scrollspy';
import 'bootstrap/js/dist/collapse';

/** Fade-in on scroll and animated cost bars. Content is visible when JS or IO is unavailable. */
export function initReveal() {
    const targets = document.querySelectorAll('[data-reveal], .cost-chart');
    if (!('IntersectionObserver' in window)) {
        targets.forEach((t) => t.classList.add('is-visible'));
        return;
    }
    const io = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                io.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    targets.forEach((t) => io.observe(t));
}

/** Highlights the current section in in-page navigations and keeps the active pill visible. */
export function initScrollSpy() {
    document.querySelectorAll('[data-spy-nav]').forEach((nav) => {
        new ScrollSpy(document.body, { target: nav, rootMargin: '0px 0px -55%', smoothScroll: false });
    });
    const strip = document.querySelector('.page-subnav .nav');
    if (strip) {
        window.addEventListener('activate.bs.scrollspy', () => {
            const active = strip.querySelector('.active');
            if (active) strip.scrollTo({ left: active.offsetLeft - strip.clientWidth / 2 + active.clientWidth / 2, behavior: 'smooth' });
        });
    }
}

export function initBackToTop() {
    const btn = document.querySelector('.back-to-top');
    if (!btn) return;
    window.addEventListener('scroll', () => btn.classList.toggle('is-visible', window.scrollY > 900), { passive: true });
    btn.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));
}
