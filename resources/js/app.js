const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

const requestJson = async (url, options = {}) => {
    const response = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            ...options.headers,
        },
        ...options,
    });

    const data = await response.json();

    if (!response.ok) {
        const error = new Error(data.message || 'Something went wrong.');
        error.data = data;
        throw error;
    }

    return data;
};

const commentsSection = document.querySelector('[data-comments]');

if (commentsSection) {
    const form = commentsSection.querySelector('[data-comment-form]');
    const list = commentsSection.querySelector('[data-comment-list]');
    const count = commentsSection.querySelector('[data-comment-count]');

    const clearCommentErrors = () => {
        form.querySelectorAll('[data-field-error]').forEach((element) => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    };

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        clearCommentErrors();

        const button = form.querySelector('button[type="submit"], button:not([type])');
        button.disabled = true;

        try {
            const loadMoreButton = commentsSection.querySelector('[data-load-more-comments]');
            const data = await requestJson(form.action, {
                method: 'POST',
                body: new FormData(form),
            });

            list.querySelector('[data-no-comments], .comments-empty')?.remove();
            list.insertAdjacentHTML('afterbegin', data.html);

            if (loadMoreButton) {
                const visibleComments = list.querySelectorAll('.comment-item');
                visibleComments[visibleComments.length - 1]?.remove();
            }

            count.textContent = String(Number(count.textContent) + 1);
            form.reset();
        } catch (error) {
            if (error.data?.errors) {
                Object.entries(error.data.errors).forEach(([field, messages]) => {
                    const element = form.querySelector(`[data-field-error="${field}"]`);
                    if (element) {
                        element.textContent = messages[0];
                        element.classList.remove('hidden');
                    }
                });
            }
        } finally {
            button.disabled = false;
        }
    });

    commentsSection.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-load-more-comments]');
        if (!button) return;

        button.disabled = true;

        try {
            const data = await requestJson(button.dataset.nextUrl);
            list.insertAdjacentHTML('beforeend', data.html);

            if (data.next_page_url) {
                button.dataset.nextUrl = data.next_page_url;
                button.disabled = false;
            } else {
                button.remove();
            }
        } catch {
            button.textContent = 'Could not load comments';
            button.disabled = false;
        }
    });
}

const tagsSection = document.querySelector('[data-tags]');

if (tagsSection) {
    const panel = tagsSection.querySelector('[data-tag-panel]');
    const badges = tagsSection.querySelector('[data-attached-tags]');
    const error = tagsSection.querySelector('[data-tag-error]');

    tagsSection.querySelector('[data-tag-toggle]').addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    tagsSection.querySelectorAll('[data-tag-checkbox]').forEach((checkbox) => {
        checkbox.addEventListener('change', async () => {
            const shouldAttach = checkbox.checked;
            checkbox.disabled = true;
            error.classList.add('hidden');

            try {
                await requestJson(shouldAttach ? checkbox.dataset.attachUrl : checkbox.dataset.detachUrl, {
                    method: shouldAttach ? 'POST' : 'DELETE',
                });

                badges.querySelector('[data-no-tags]')?.remove();
                badges.querySelector(`[data-tag-badge="${checkbox.value}"]`)?.remove();

                if (shouldAttach) {
                    const badge = document.createElement('span');
                    badge.dataset.tagBadge = checkbox.value;
                    badge.className = 'rounded-full px-3 py-1 text-xs font-medium text-white';
                    badge.style.backgroundColor = checkbox.dataset.tagColor;
                    badge.textContent = checkbox.dataset.tagName;
                    badges.append(badge);
                } else if (!badges.querySelector('[data-tag-badge]')) {
                    badges.insertAdjacentHTML('beforeend', '<p class="text-sm text-slate-500" data-no-tags>No tags attached.</p>');
                }
            } catch (requestError) {
                checkbox.checked = !shouldAttach;
                error.textContent = requestError.message;
                error.classList.remove('hidden');
            } finally {
                checkbox.disabled = false;
            }
        });
    });
}

const membersSection = document.querySelector('[data-members]');

if (membersSection) {
    const panel = membersSection.querySelector('[data-member-panel]');
    const cards = membersSection.querySelector('[data-assigned-members]');
    const error = membersSection.querySelector('[data-member-error]');

    membersSection.querySelector('[data-member-toggle]').addEventListener('click', () => {
        panel.classList.toggle('hidden');
    });

    membersSection.querySelectorAll('[data-member-checkbox]').forEach((checkbox) => {
        checkbox.addEventListener('change', async () => {
            const shouldAttach = checkbox.checked;
            checkbox.disabled = true;
            error.classList.add('hidden');

            try {
                await requestJson(shouldAttach ? checkbox.dataset.attachUrl : checkbox.dataset.detachUrl, {
                    method: shouldAttach ? 'POST' : 'DELETE',
                });

                cards.querySelector('[data-no-members]')?.remove();
                cards.querySelector(`[data-member-card="${checkbox.value}"]`)?.remove();

                if (shouldAttach) {
                    const card = document.createElement('div');
                    card.dataset.memberCard = checkbox.value;
                    card.className = 'flex items-center gap-3 rounded-xl bg-slate-50 p-3';

                    const avatar = document.createElement('span');
                    avatar.className = 'grid size-9 shrink-0 place-items-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700';
                    avatar.textContent = checkbox.dataset.memberName.charAt(0).toUpperCase();

                    const details = document.createElement('div');
                    details.className = 'min-w-0';

                    const name = document.createElement('p');
                    name.className = 'truncate text-sm font-medium';
                    name.textContent = checkbox.dataset.memberName;

                    const email = document.createElement('p');
                    email.className = 'truncate text-xs text-slate-500';
                    email.textContent = checkbox.dataset.memberEmail;

                    details.append(name, email);
                    card.append(avatar, details);
                    cards.append(card);
                } else if (!cards.querySelector('[data-member-card]')) {
                    cards.insertAdjacentHTML('beforeend', '<p class="text-sm text-slate-500" data-no-members>No members assigned.</p>');
                }
            } catch (requestError) {
                checkbox.checked = !shouldAttach;
                error.textContent = requestError.message;
                error.classList.remove('hidden');
            } finally {
                checkbox.disabled = false;
            }
        });
    });
}

const issueFilters = document.querySelector('[data-issue-filters]');

if (issueFilters) {
    const search = issueFilters.querySelector('[data-issue-search]');
    const results = document.querySelector('[data-issue-results]');
    const spinner = issueFilters.querySelector('[data-search-spinner]');
    let debounceTimer;
    let activeRequest;

    search.addEventListener('input', () => {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(async () => {
            activeRequest?.abort();
            activeRequest = new AbortController();
            spinner.classList.remove('hidden');

            const params = new URLSearchParams(new FormData(issueFilters));
            const url = `${issueFilters.action || window.location.pathname}?${params.toString()}`;

            try {
                const response = await fetch(url, {
                    headers: { Accept: 'application/json' },
                    signal: activeRequest.signal,
                });
                const data = await response.json();

                if (!response.ok) throw new Error(data.message);

                results.innerHTML = data.html;
                window.history.replaceState({}, '', url);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    results.innerHTML = '<div class="mt-6 rounded-xl bg-red-50 p-4 text-sm text-red-700">Search could not be loaded.</div>';
                }
            } finally {
                spinner.classList.add('hidden');
            }
        }, 350);
    });
}
