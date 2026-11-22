import Modal from 'bootstrap/js/dist/modal';

/** "View details" on the destinations page: fills one shared modal from embedded JSON. */
export function initDestinationModal() {
    const el = document.getElementById('destinationModal');
    const source = document.getElementById('destinations-data');
    if (!el || !source) return;

    const data = JSON.parse(source.textContent);
    const modal = Modal.getOrCreateInstance(el);
    const $ = (sel) => el.querySelector(sel);

    const list = (target, items, render) => {
        target.replaceChildren(...items.map((item) => {
            const li = document.createElement('li');
            render(li, item);
            return li;
        }));
    };

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-destination]');
        if (!trigger) return;
        const d = data[trigger.dataset.destination];
        if (!d) return;
        event.preventDefault();

        $('[data-d="flag"]').src = d.flag;
        $('[data-d="flag"]').alt = `Flag of ${d.name}`;
        $('[data-d="title"]').textContent = `Study in ${d.name}`;
        $('[data-d="headline"]').textContent = d.headline;
        $('[data-d="overview"]').textContent = d.overview;
        $('[data-d="tuition"]').textContent = d.tuition_range;
        $('[data-d="living"]').textContent = d.living_cost;
        $('[data-d="work"]').textContent = d.work_rights;
        list($('[data-d="highlights"]'), d.highlights, (li, text) => { li.textContent = text; });
        list($('[data-d="universities"]'), d.top_universities, (li, u) => {
            const strong = document.createElement('strong');
            strong.textContent = u.name;
            const span = document.createElement('span');
            span.textContent = [u.city, u.rank].filter(Boolean).join(' · ');
            li.append(strong, span);
        });
        const page = $('[data-d="page"]');
        page.href = d.url;
        page.querySelector('span').textContent = `Explore ${d.name}`;
        const book = $('[data-d="book"]');
        book.dataset.country = d.name;
        book.querySelector('span').textContent = `Book free counselling for ${d.name}`;
        modal.show(trigger);
    });

}
