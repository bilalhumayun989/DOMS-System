function initializePageControls() {
    if (matchMedia('(max-width: 767px)').matches) document.body.classList.add('sidebar-collapsed');
    document.querySelectorAll('[data-sidebar-toggle]').forEach(button => {
        button.addEventListener('click', () => document.body.classList.toggle('sidebar-collapsed'));
    });
    const search = document.getElementById('page-search');
    search?.addEventListener('input', () => {
        const query = search.value.trim().toLocaleLowerCase();
        document.querySelectorAll('main table tbody tr').forEach(row => {
            row.hidden = query !== '' && !row.textContent.toLocaleLowerCase().includes(query);
        });
    });
    document.addEventListener('keydown', event => {
        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            search?.focus();
        }
    });
    document.addEventListener('submit', event => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.method.toLowerCase() !== 'post') return;
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = '_form_path';
        input.value = new URL(form.action, location.href).pathname;
        form.append(input);
    });
    restoreForm();
}

let formRestored = false;

async function restoreForm() {
    const previous = document.getElementById('previous-form-input');
    if (!previous || !window.Alpine || formRestored) return;
    const values = JSON.parse(previous.textContent);
    if (!values._form_path) return;
    const root = document.querySelector('main [x-data]');
    if (root && !root._x_dataStack) return;
    formRestored = true;
    if (root) {
        const state = window.Alpine.$data(root);
        const id = values._form_path.match(/\/(\d+)$/)?.[1];
        if ('mode' in state) {
            state.mode = values._method === 'PUT' ? 'edit' : 'create';
            state.selected = { ...values, id };
            state.open = true;
            if ('selectedAreas' in state) state.selectedAreas = values.assigned_areas ?? [];
        }
        if ('transactionOpen' in state && values._form_path.includes('bank-transactions')) {
            if ('selected' in state) state.selected = id ? { ...values, id } : null;
            state.transactionOpen = true;
        }
        if ('addBankOpen' in state && values._form_path.endsWith('/banks')) state.addBankOpen = true;
        await window.Alpine.nextTick();
    }
    document.querySelectorAll('main form').forEach(form => {
        if (new URL(form.action, location.href).pathname !== values._form_path) return;
        const alert = document.querySelector('main > [role=alert]');
        if (alert && form.closest('[x-show]')) form.prepend(alert.cloneNode(true));
        Object.entries(values).forEach(([name, value]) => {
            if (name.startsWith('_')) return;
            for (const field of form.elements) {
                if (field.name !== name || field.type === 'file') continue;
                if (field.type === 'radio' || field.type === 'checkbox') field.checked = field.value === value;
                else field.value = value ?? '';
                field.dispatchEvent(new Event('input', { bubbles: true }));
                field.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
}

document.addEventListener('alpine:initialized', restoreForm, { once: true });

if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initializePageControls);
else initializePageControls();
