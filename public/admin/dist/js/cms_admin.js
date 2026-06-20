(function () {
    'use strict';

    const token = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function clearErrors(form) {
        form.querySelectorAll('.is-invalid').forEach((field) => {
            field.classList.remove('is-invalid');
        });

        const box = form.querySelector('[data-cms-errors]');
        if (box) {
            box.classList.remove('is-visible');
            box.innerHTML = '';
        }
    }

    function showErrors(form, errors, fallback) {
        const box = form.querySelector('[data-cms-errors]');
        const messages = [];

        Object.entries(errors || {}).forEach(([field, fieldMessages]) => {
            const input = form.querySelector(`[name="${field}"], [name="${field}[]"]`);
            if (input) {
                input.classList.add('is-invalid');
            }
            (Array.isArray(fieldMessages) ? fieldMessages : [fieldMessages])
                .forEach((message) => messages.push(message));
        });

        if (messages.length === 0 && fallback) {
            messages.push(fallback);
        }

        if (box && messages.length) {
            box.innerHTML = `<strong>Please correct the following:</strong><ul class="mb-0 mt-2">${
                messages.map((message) => `<li>${escapeHtml(message)}</li>`).join('')
            }</ul>`;
            box.classList.add('is-visible');
            box.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    function escapeHtml(value) {
        const element = document.createElement('div');
        element.textContent = String(value);
        return element.innerHTML;
    }

    async function send(endpoint, body) {
        const response = await fetch(endpoint, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body
        });

        let payload = {};
        if (response.status !== 204) {
            payload = await response.json().catch(() => ({}));
        }

        if (!response.ok) {
            const error = new Error(payload.message || 'The request could not be completed.');
            error.status = response.status;
            error.errors = payload.errors || {};
            throw error;
        }

        return payload;
    }

    document.querySelectorAll('[data-cms-form]').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            clearErrors(form);

            const submit = form.querySelector('[type="submit"]');
            const originalText = submit?.innerHTML;
            if (submit) {
                submit.disabled = true;
                submit.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving';
            }

            const body = new FormData(form);
            form.querySelectorAll('[data-array-field]').forEach((field) => {
                body.delete(field.name);
                field.value.split(/[\n,]+/)
                    .map((value) => value.trim())
                    .filter(Boolean)
                    .forEach((value) => body.append(field.name, value));
            });

            if (form.dataset.method && form.dataset.method !== 'POST') {
                body.set('_method', form.dataset.method);
            }

            try {
                await send(form.dataset.endpoint, body);
                window.location.assign(form.dataset.redirect);
            } catch (error) {
                showErrors(form, error.errors, error.message);
            } finally {
                if (submit) {
                    submit.disabled = false;
                    submit.innerHTML = originalText;
                }
            }
        });
    });

    document.querySelectorAll('[data-cms-delete]').forEach((button) => {
        button.addEventListener('click', async () => {
            const confirmed = window.Swal
                ? (await Swal.fire({
                    title: 'Delete this item?',
                    text: 'This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    confirmButtonText: 'Delete'
                })).isConfirmed
                : window.confirm('Delete this item? This action cannot be undone.');

            if (!confirmed) {
                return;
            }

            const body = new FormData();
            body.set('_method', 'DELETE');

            try {
                await send(button.dataset.endpoint, body);
                window.location.reload();
            } catch (error) {
                if (window.toastr) {
                    toastr.error(error.message);
                } else {
                    window.alert(error.message);
                }
            }
        });
    });

    document.querySelectorAll('[data-cms-publish]').forEach((button) => {
        button.addEventListener('click', async () => {
            const body = new FormData();
            body.set('_method', 'PATCH');
            button.disabled = true;

            try {
                await send(button.dataset.endpoint, body);
                window.location.reload();
            } catch (error) {
                button.disabled = false;
                if (window.toastr) {
                    toastr.error(error.message);
                } else {
                    window.alert(error.message);
                }
            }
        });
    });
})();
