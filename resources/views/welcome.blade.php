<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias de Documentos - Sistema de Compliance</title>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <header class="bg-gradient-to-r from-slate-800 via-slate-900 to-slate-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-900/30 group-hover:scale-105 transition-transform">
                        <i class="fas fa-shield-halved text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold leading-tight">Sistema de Compliance</h1>
                        <p class="text-xs sm:text-sm text-slate-300">Portal de Documentos</p>
                    </div>
                </div>
                <nav id="breadcrumb" class="hidden sm:flex items-center gap-2 text-sm text-slate-300">
                    <span><i class="fas fa-home"></i></span>
                    <i class="fas fa-chevron-right text-xs text-slate-500"></i>
                    <span class="text-white font-medium">Categorias</span>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-slate-800 mb-2 flex items-center gap-2">
                <i class="fas fa-folder-open text-blue-600"></i>
                Categorias de Documentos
            </h2>
            <p class="text-slate-600">Selecione uma categoria para visualizar o diretório de arquivos.</p>
        </div>

        @if($categories->isEmpty())
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                <i class="fas fa-folder-open text-5xl text-slate-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-slate-700 mb-2">Nenhuma categoria cadastrada</h3>
                <p class="text-slate-500">Acesse o painel administrativo para cadastrar categorias e enviar arquivos.</p>
            </div>
        @else
            <div id="folders-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($categories as $cat)
                    <a href="{{ $cat['show_url'] }}"
                       class="category-card group bg-white rounded-xl shadow-sm border border-slate-200 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden block"
                       data-category-id="{{ (int) $cat['id'] }}"
                       data-card-link="{{ $cat['show_url'] }}">
                        <div class="bg-gradient-to-r {{ $cat['color'] }} p-5 text-white relative overflow-hidden">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full group-hover:scale-125 transition-transform duration-500"></div>
                            <div class="relative flex items-start justify-between">
                                <div class="w-12 h-12 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center">
                                    <i class="fas {{ $cat['icon'] }} text-xl"></i>
                                </div>
                                <div class="flex flex-col gap-1.5 items-end">
                                    <span class="bg-white/20 backdrop-blur px-2.5 py-1 rounded-full text-xs font-medium">
                                        {{ (int) $cat['files_count'] }} docs
                                    </span>
                                    <span class="bg-black/20 backdrop-blur px-2.5 py-1 rounded-full text-[11px] font-medium">
                                        ID {{ (int) $cat['id'] }}
                                    </span>
                                </div>
                            </div>
                            <h3 class="mt-4 font-bold text-lg leading-tight">{{ $cat['name'] }}</h3>
                        </div>
                        <div class="p-5">
                            <p class="text-sm text-slate-600 mb-4 line-clamp-2 min-h-[2.5rem]">
                                {{ $cat['description'] ?? 'Documentos da categoria ' . $cat['name'] . '.' }}
                            </p>
                            <div class="flex items-center justify-between text-sm">
                                <span class="inline-flex items-center gap-1.5 text-blue-600 font-medium group-hover:gap-2.5 transition-all">
                                    Ver diretório
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </span>
                                <button type="button"
                                        class="open-page-btn text-xs text-slate-500 hover:text-slate-700 hover:underline inline-flex items-center gap-1"
                                        data-category-id="{{ (int) $cat['id'] }}"
                                        title="Abrir página dedicada">
                                    <i class="fas fa-up-right-from-square"></i>
                                    Página
                                </button>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

    @unless($categories->isEmpty())
        <div id="category-modal-root" aria-live="polite"></div>
    @endunless
    <div id="signature-modal-root" aria-live="polite"></div>

    <footer class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8 border-t border-slate-200 mt-12">
        <p class="text-center text-sm text-slate-500">
            <i class="fas fa-copyright mr-1"></i>
            <span id="year"></span>
            Sistema de Compliance - Todos os direitos reservados
        </p>
    </footer>

    <script>
        document.getElementById('year').textContent = new Date().getFullYear();

        const categoriesById = {!! $categoriesByIdJson !!};
        const categoriesFilesById = {};

        function htmlEscape(str) {
            return String(str == null ? '' : str)
                .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;').replace(/'/g, '&#039;');
        }

        function getFileInfo(filename) {
            const ext = (String(filename || '').split('.').pop() || '').toLowerCase();
            switch (ext) {
                case 'pdf':
                    return { icon: 'fa-file-pdf', type: 'PDF', color: 'text-red-500 bg-red-50' };
                case 'docx':
                case 'doc':
                    return { icon: 'fa-file-word', type: 'DOC', color: 'text-blue-600 bg-blue-50' };
                case 'xlsx':
                case 'xls':
                    return { icon: 'fa-file-excel', type: 'XLS', color: 'text-green-600 bg-green-50' };
                case 'pptx':
                case 'ppt':
                    return { icon: 'fa-file-powerpoint', type: 'PPT', color: 'text-orange-600 bg-orange-50' };
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                case 'svg':
                case 'webp':
                    return { icon: 'fa-file-image', type: ext.toUpperCase(), color: 'text-purple-600 bg-purple-50' };
                default:
                    return { icon: 'fa-file', type: ext ? ext.toUpperCase() : 'FILE', color: 'text-slate-500 bg-slate-50' };
            }
        }

        function buildFilesRows(files, categoryId) {
            if (!files.length) {
                return '' +
                    '<div class="p-12 text-center">' +
                        '<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">' +
                            '<i class="fas fa-folder-open text-2xl"></i>' +
                        '</div>' +
                        '<h4 class="text-lg font-semibold text-slate-700 mb-1">Nenhum arquivo encontrado</h4>' +
                        '<p class="text-sm text-slate-500">Categoria ID ' + htmlEscape(categoryId) + ' ainda não possui arquivos.</p>' +
                    '</div>';
            }

            return files.map(function (f) {
                const info = getFileInfo(f.display_name || f.name);
                return '' +
                    '<article class="modal-file-row group p-4 sm:p-5 transition hover:bg-slate-50 border-b last:border-b-0 border-slate-100" ' +
                             'data-file-id="' + htmlEscape(f.id) + '" ' +
                             'data-name="' + htmlEscape(f.display_name || f.name) + '" ' +
                             'data-desc="' + htmlEscape(f.description || '') + '">' +
                        '<div class="flex items-start gap-4">' +
                            '<div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl ' + info.color + '">' +
                                '<i class="fas ' + info.icon + ' text-xl"></i>' +
                            '</div>' +
                            '<div class="min-w-0 flex-1">' +
                                '<div class="flex items-start justify-between gap-3 flex-wrap">' +
                                    '<div class="min-w-0">' +
                                        '<div class="flex items-center gap-2 flex-wrap">' +
                                            '<span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase ' + info.color + '">' +
                                                htmlEscape(info.type) +
                                            '</span>' +
                                            '<span class="inline-flex items-center gap-1 text-xs text-slate-500">' +
                                                '<i class="fas fa-id-badge text-slate-400"></i>' +
                                                'Arquivo ID ' + htmlEscape(f.id) +
                                            '</span>' +
                                        '</div>' +
                                        '<h4 class="mt-1 font-semibold text-slate-800 text-base truncate">' +
                                            htmlEscape(f.display_name || f.name) +
                                        '</h4>' +
                                        '<p class="text-sm text-slate-500 mt-0.5 line-clamp-2">' +
                                            htmlEscape(f.description || 'Sem descrição.') +
                                        '</p>' +
                                        '<div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">' +
                                            (f.user_name ? ('<span class="inline-flex items-center gap-1.5"><i class="fas fa-user-circle text-slate-400"></i>' + htmlEscape(f.user_name) + '</span>') : '') +
                                            (f.created_at ? ('<span class="inline-flex items-center gap-1.5"><i class="fas fa-calendar text-slate-400"></i>' + htmlEscape(f.created_at) + '</span>') : '') +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="flex items-center gap-2 flex-shrink-0">' +
                                        '<a href="' + htmlEscape(f.url) + '" target="_blank" rel="noopener noreferrer" ' +
                                           'class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition">' +
                                            '<i class="fas fa-up-right-from-square"></i>Abrir' +
                                        '</a>' +
                                        '<button type="button" ' +
                                           'class="sign-btn inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition" ' +
                                           'data-file-id="' + htmlEscape(f.id) + '" ' +
                                           'data-file-name="' + htmlEscape(f.display_name || f.name) + '" ' +
                                           'title="Assinar Termo de Ciência">' +
                                            '<i class="fas fa-signature"></i>Assinar' +
                                        '</button>' +
                                        '<a href="' + htmlEscape(f.url) + '" download ' +
                                           'class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition">' +
                                            '<i class="fas fa-download"></i>Baixar' +
                                        '</a>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</article>';
            }).join('');
        }

        function renderFilesInContainer(listRoot, countRoot, searchInput, files, categoryId) {
            listRoot.innerHTML = buildFilesRows(files, categoryId);
            const rows = listRoot.querySelectorAll('.modal-file-row');
            const render = function (term) {
                term = (term || '').trim().toLowerCase();
                let visible = 0;
                rows.forEach(function (row) {
                    const name = (row.dataset.name || '').toLowerCase();
                    const desc = (row.dataset.desc || '').toLowerCase();
                    const match = !term || name.includes(term) || desc.includes(term);
                    row.style.display = match ? '' : 'none';
                    if (match) visible++;
                });
                if (countRoot) {
                    countRoot.textContent = 'Exibindo ' + visible + ' de ' + rows.length + ' arquivo(s) da categoria ID ' + categoryId + '.';
                }
            };
            if (searchInput) {
                searchInput.addEventListener('input', function (e) {
                    render(e.target.value);
                });
            }
            bindSignButtons(listRoot);
            render('');
        }

        async function fetchCategoryFiles(categoryId) {
            if (categoriesFilesById[categoryId]) {
                return categoriesFilesById[categoryId];
            }
            const url = '/api/categories/' + encodeURIComponent(categoryId) + '/files';
            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('HTTP ' + res.status);
                const json = await res.json();
                categoriesFilesById[categoryId] = Array.isArray(json) ? json : (json.files || []);
                return categoriesFilesById[categoryId];
            } catch (err) {
                console.warn('Falha ao carregar arquivos da categoria ' + categoryId + ': ', err);
                categoriesFilesById[categoryId] = [];
                return [];
            }
        }

        function openCategoryModalInline(cat) {
            const root = document.getElementById('category-modal-root');
            if (!root) return;

            const modalId = 'category-modal-' + String(cat.id);
            let modalEl = document.getElementById(modalId);
            if (modalEl) {
                modalEl.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                return;
            }

            const html = '' +
                '<div id="' + modalId + '" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6">' +
                    '<div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-modal="' + modalId + '"></div>' +
                    '<div class="relative w-full max-w-5xl max-h-[90vh] bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col" role="dialog" aria-modal="true">' +
                        '<div class="bg-gradient-to-r ' + htmlEscape(cat.color) + ' text-white relative overflow-hidden flex-shrink-0">' +
                            '<div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>' +
                            '<div class="relative p-5 sm:p-6">' +
                                '<div class="flex items-start justify-between gap-4 flex-wrap">' +
                                    '<div class="flex items-start gap-4">' +
                                        '<div class="flex-shrink-0 w-14 h-14 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">' +
                                            '<i class="fas ' + htmlEscape(cat.icon) + ' text-xl"></i>' +
                                        '</div>' +
                                        '<div class="min-w-0">' +
                                            '<div class="flex flex-wrap items-center gap-2">' +
                                                '<h3 class="text-xl sm:text-2xl font-bold leading-tight">' + htmlEscape(cat.name) + '</h3>' +
                                                '<span class="bg-white/15 backdrop-blur px-2.5 py-1 rounded-full text-[11px] font-medium">ID ' + htmlEscape(cat.id) + '</span>' +
                                                '<span class="bg-white/20 backdrop-blur px-2.5 py-1 rounded-full text-xs font-medium">' + htmlEscape(cat.files_count ?? 0) + ' docs</span>' +
                                            '</div>' +
                                            '<p class="text-white/85 mt-1 max-w-2xl">' + htmlEscape(cat.description || ('Documentos da categoria ' + cat.name + '.')) + '</p>' +
                                        '</div>' +
                                    '</div>' +
                                    '<div class="flex items-center gap-2 flex-shrink-0">' +
                                        '<a href="' + htmlEscape(cat.show_url) + '" class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white text-slate-800 font-medium shadow hover:shadow-lg transition">' +
                                            '<i class="fas fa-expand"></i>Página' +
                                        '</a>' +
                                        '<button type="button" data-close-modal="' + modalId + '" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white/15 hover:bg-white/25 backdrop-blur text-white transition" aria-label="Fechar">' +
                                            '<i class="fas fa-times"></i>' +
                                        '</button>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                        '<div class="px-5 sm:px-6 pt-4 pb-2 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between gap-4 flex-wrap flex-shrink-0">' +
                            '<div class="relative w-full sm:w-80">' +
                                '<i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>' +
                                '<input type="text" placeholder="Buscar arquivo..." class="modal-search-input w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">' +
                            '</div>' +
                            '<span class="modal-doc-count text-xs sm:text-sm text-slate-500 min-w-[180px] text-right sm:text-left">Carregando...</span>' +
                        '</div>' +
                        '<div class="modal-doc-list flex-1 overflow-y-auto">' +
                            '<div class="p-10 text-center text-sm text-slate-500">' +
                                '<i class="fas fa-spinner fa-spin text-slate-400 mr-2"></i>Carregando arquivos da categoria ID ' + htmlEscape(cat.id) + '...' +
                            '</div>' +
                        '</div>' +
                        '<div class="px-5 sm:px-6 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between flex-shrink-0">' +
                            '<button type="button" data-close-modal="' + modalId + '" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-medium hover:bg-slate-200 transition">' +
                                '<i class="fas fa-arrow-left"></i>Voltar' +
                            '</button>' +
                            '<a href="' + htmlEscape(cat.show_url) + '" class="inline-flex sm:hidden items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-medium shadow hover:bg-blue-700 transition">' +
                                'Diretório completo<i class="fas fa-up-right-from-square"></i>' +
                            '</a>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            root.insertAdjacentHTML('beforeend', html);
            modalEl = document.getElementById(modalId);

            modalEl.addEventListener('click', function (ev) {
                const closeTarget = ev.target.closest('[data-close-modal="' + modalId + '"]');
                if (closeTarget) {
                    modalEl.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });

            document.addEventListener('keydown', function escHandler(e) {
                if (e.key === 'Escape') {
                    if (modalEl && !modalEl.classList.contains('hidden')) {
                        modalEl.classList.add('hidden');
                        document.body.style.overflow = '';
                    }
                    document.removeEventListener('keydown', escHandler);
                }
            });

            const listRoot = modalEl.querySelector('.modal-doc-list');
            const countRoot = modalEl.querySelector('.modal-doc-count');
            const searchInput = modalEl.querySelector('.modal-search-input');

            fetchCategoryFiles(cat.id).then(function (files) {
                renderFilesInContainer(listRoot, countRoot, searchInput, files, cat.id);
            });

            document.body.style.overflow = 'hidden';
        }

        function openCategoryModalFromId(categoryId, event) {
            const cat = categoriesById[categoryId];
            if (!cat) return false;

            if (event) {
                if (event.metaKey || event.ctrlKey || event.shiftKey || event.button === 1) {
                    return true;
                }
                if (event.preventDefault) event.preventDefault();
            }

            openCategoryModalInline(cat);
            return false;
        }

        window.openCategoryModalFromId = openCategoryModalFromId;

        (function bindCardClicks() {
            const grid = document.getElementById('folders-grid');
            if (!grid) return;

            grid.addEventListener('click', function (ev) {
                const pageBtn = ev.target.closest('.open-page-btn');
                const card = ev.target.closest('.category-card');
                if (!card) return;

                const categoryId = parseInt(card.dataset.categoryId, 10);
                const cardLink = card.getAttribute('data-card-link');

                if (pageBtn) {
                    if (cardLink) {
                        ev.preventDefault();
                        ev.stopPropagation();
                        window.location.href = cardLink;
                    }
                    return;
                }

                const shouldOpenPage = ev.metaKey || ev.ctrlKey || ev.shiftKey || ev.button === 1;
                if (shouldOpenPage) return;

                ev.preventDefault();
                openCategoryModalFromId(categoryId, null);
            }, false);
        })();

        (function refreshCategoriesById() {
            try {
                fetch('/api/categories', { headers: { 'Accept': 'application/json' } })
                    .then(function (r) { return r.ok ? r.json() : null; })
                    .then(function (data) {
                        if (!data || !Array.isArray(data)) return;
                        data.forEach(function (row) {
                            if (!row || row.id == null) return;
                            if (!categoriesById[row.id]) {
                                categoriesById[row.id] = {
                                    id: row.id,
                                    name: row.name,
                                    description: row.description,
                                    color: row.color || 'from-slate-500 to-slate-600',
                                    icon: row.icon || 'fa-folder',
                                    icon_bg: row.icon_bg || 'bg-slate-100 text-slate-600',
                                    files_count: row.files_count ?? 0,
                                    show_url: row.show_url || ('/categorias/' + row.id),
                                };
                            }
                        });
                    })
                    .catch(function () {});
            } catch (e) {}
        })();

        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.getAttribute('content') : '';
        }

        function bindSignButtons(rootEl) {
            const scope = rootEl || document;
            const btns = scope.querySelectorAll('.sign-btn');
            btns.forEach(function (btn) {
                if (btn.dataset.bound === '1') return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const fileId = btn.getAttribute('data-file-id');
                    const fileName = btn.getAttribute('data-file-name') || '';
                    openSignatureModal(fileId, fileName);
                });
            });
        }

        function openSignatureModal(fileId, fileName) {
            const root = document.getElementById('signature-modal-root');
            if (!root) return;
            const modalId = 'signature-modal';
            let modalEl = document.getElementById(modalId);
            if (!modalEl) {
                const html = '' +
                    '<div id="' + modalId + '" class="fixed inset-0 z-[60] hidden flex items-center justify-center p-4 sm:p-6">' +
                        '<div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-signature="' + modalId + '"></div>' +
                        '<div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col" role="dialog" aria-modal="true">' +
                            '<div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-5 relative overflow-hidden">' +
                                '<div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full blur-2xl"></div>' +
                                '<div class="relative flex items-start justify-between gap-4">' +
                                    '<div class="flex items-start gap-3">' +
                                        '<div class="w-11 h-11 rounded-lg bg-white/20 backdrop-blur flex items-center justify-center flex-shrink-0">' +
                                            '<i class="fas fa-signature text-lg"></i>' +
                                        '</div>' +
                                        '<div class="min-w-0">' +
                                            '<h3 class="text-lg font-bold leading-tight">Assinar Termo de Ciência</h3>' +
                                            '<p class="signature-doc-name text-sm text-white/85 mt-0.5 truncate max-w-xs"></p>' +
                                        '</div>' +
                                    '</div>' +
                                    '<button type="button" data-close-signature="' + modalId + '" class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white/15 hover:bg-white/25 backdrop-blur text-white transition flex-shrink-0" aria-label="Fechar">' +
                                        '<i class="fas fa-times"></i>' +
                                    '</button>' +
                                '</div>' +
                            '</div>' +
                            '<form class="signature-form flex flex-col flex-1" novalidate>' +
                                '<input type="hidden" name="file_id" value="">' +
                                '<div class="p-5 sm:p-6 space-y-4 flex-1 overflow-y-auto">' +
                                    '<div class="signature-alert hidden rounded-lg px-4 py-3 text-sm"></div>' +
                                    '<div>' +
                                        '<label class="block text-sm font-medium text-slate-700 mb-1.5">Nome Completo <span class="text-red-500">*</span></label>' +
                                        '<input type="text" name="name" required maxlength="255" ' +
                                               'class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" ' +
                                               'placeholder="Informe seu nome completo">' +
                                        '<p class="signature-error-name mt-1 text-xs text-red-600 hidden"></p>' +
                                    '</div>' +
                                    '<div>' +
                                        '<label class="block text-sm font-medium text-slate-700 mb-1.5">E-mail <span class="text-red-500">*</span></label>' +
                                        '<input type="email" name="email" required maxlength="255" ' +
                                               'class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" ' +
                                               'placeholder="seu@email.com">' +
                                        '<p class="signature-error-email mt-1 text-xs text-red-600 hidden"></p>' +
                                    '</div>' +
                                    '<div>' +
                                        '<label class="block text-sm font-medium text-slate-700 mb-1.5">CPF <span class="text-red-500">*</span></label>' +
                                        '<input type="text" name="cpf" required maxlength="14" ' +
                                               'class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none transition" ' +
                                               'placeholder="000.000.000-00">' +
                                        '<p class="signature-error-cpf mt-1 text-xs text-red-600 hidden"></p>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="px-5 sm:px-6 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between gap-3">' +
                                    '<button type="button" data-close-signature="' + modalId + '" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 text-slate-700 font-medium hover:bg-slate-200 transition">' +
                                        '<i class="fas fa-arrow-left"></i>Cancelar' +
                                    '</button>' +
                                    '<button type="submit" class="signature-submit inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white font-medium shadow hover:bg-emerald-700 transition disabled:opacity-60 disabled:cursor-not-allowed">' +
                                        '<i class="fas fa-pen"></i>Confirmar Assinatura' +
                                    '</button>' +
                                '</div>' +
                            '</form>' +
                        '</div>' +
                    '</div>';
                root.insertAdjacentHTML('beforeend', html);
                modalEl = document.getElementById(modalId);

                modalEl.addEventListener('click', function (ev) {
                    const closeTarget = ev.target.closest('[data-close-signature="' + modalId + '"]');
                    if (closeTarget) closeSignatureModal();
                });

                const cpfInput = modalEl.querySelector('input[name="cpf"]');
                if (cpfInput) {
                    cpfInput.addEventListener('input', function () {
                        let v = cpfInput.value.replace(/\D/g, '').substring(0, 11);
                        if (v.length >= 9) v = v.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2})$/, '$1.$2.$3-$4');
                        else if (v.length >= 6) v = v.replace(/^(\d{3})(\d{3})(\d{0,3})$/, '$1.$2.$3');
                        else if (v.length >= 3) v = v.replace(/^(\d{3})(\d{0,3})$/, '$1.$2');
                        cpfInput.value = v;
                    });
                }

                const form = modalEl.querySelector('.signature-form');
                if (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        submitSignatureForm(form);
                    });
                }
            }

            modalEl.querySelector('input[name="file_id"]').value = fileId || '';
            const nameEl = modalEl.querySelector('.signature-doc-name');
            if (nameEl) nameEl.textContent = fileName ? ('Documento: ' + fileName) : '';
            const form = modalEl.querySelector('.signature-form');
            if (form) {
                form.reset();
                form.querySelector('input[name="file_id"]').value = fileId || '';
                form.querySelectorAll('[class*="signature-error-"]').forEach(function (p) {
                    p.classList.add('hidden');
                    p.textContent = '';
                });
                form.querySelectorAll('input').forEach(function (i) {
                    i.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    i.classList.add('border-slate-300', 'focus:ring-emerald-500', 'focus:border-emerald-500');
                });
                const alert = modalEl.querySelector('.signature-alert');
                if (alert) {
                    alert.classList.add('hidden');
                    alert.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
                    alert.textContent = '';
                }
                const submitBtn = modalEl.querySelector('.signature-submit');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-pen"></i>Confirmar Assinatura';
                }
            }
            modalEl.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            const firstInput = modalEl.querySelector('input[name="name"]');
            if (firstInput) setTimeout(function () { firstInput.focus(); }, 50);
        }

        function closeSignatureModal() {
            const modalEl = document.getElementById('signature-modal');
            if (modalEl) modalEl.classList.add('hidden');
            document.body.style.overflow = '';
        }

        function showSignatureAlert(type, message) {
            const modalEl = document.getElementById('signature-modal');
            if (!modalEl) return;
            const alert = modalEl.querySelector('.signature-alert');
            if (!alert) return;
            alert.classList.remove('hidden', 'bg-red-50', 'text-red-700', 'border', 'border-red-200', 'bg-emerald-50', 'text-emerald-700', 'border-emerald-200');
            if (type === 'error') {
                alert.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            } else {
                alert.classList.add('bg-emerald-50', 'text-emerald-700', 'border', 'border-emerald-200');
            }
            alert.textContent = message;
        }

        function setFieldError(fieldName, message) {
            const modalEl = document.getElementById('signature-modal');
            if (!modalEl) return;
            const input = modalEl.querySelector('input[name="' + fieldName + '"]');
            const errP = modalEl.querySelector('.signature-error-' + fieldName);
            if (message) {
                if (input) {
                    input.classList.remove('border-slate-300', 'focus:ring-emerald-500', 'focus:border-emerald-500');
                    input.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                }
                if (errP) {
                    errP.textContent = message;
                    errP.classList.remove('hidden');
                }
            } else {
                if (input) {
                    input.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    input.classList.add('border-slate-300', 'focus:ring-emerald-500', 'focus:border-emerald-500');
                }
                if (errP) {
                    errP.textContent = '';
                    errP.classList.add('hidden');
                }
            }
        }

        function isValidEmail(v) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(String(v || ''));
        }

        function isValidCpf(v) {
            const digits = String(v || '').replace(/\D/g, '');
            return digits.length === 11;
        }

        async function submitSignatureForm(form) {
            const data = new FormData(form);
            const fileId = (data.get('file_id') || '').toString().trim();
            const name = (data.get('name') || '').toString().trim();
            const email = (data.get('email') || '').toString().trim();
            const cpf = (data.get('cpf') || '').toString().trim();

            setFieldError('name', null);
            setFieldError('email', null);
            setFieldError('cpf', null);

            let hasError = false;
            if (!fileId) {
                showSignatureAlert('error', 'ID do documento não identificado. Tente novamente.');
                return;
            }
            if (!name || name.length < 2) {
                setFieldError('name', 'Informe seu nome completo.');
                hasError = true;
            }
            if (!email || !isValidEmail(email)) {
                setFieldError('email', 'Informe um e-mail válido.');
                hasError = true;
            }
            if (!cpf || !isValidCpf(cpf)) {
                setFieldError('cpf', 'Informe um CPF válido (11 dígitos).');
                hasError = true;
            }
            if (hasError) {
                showSignatureAlert('error', 'Por favor, revise os campos destacados.');
                return;
            }

            const submitBtn = form.querySelector('.signature-submit');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>Enviando...';
            }
            showSignatureAlert('', '');

            try {
                const url = '/arquivos/' + encodeURIComponent(fileId) + '/assinar';
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                    },
                    body: JSON.stringify({ name: name, email: email, cpf: cpf }),
                });

                let json = null;
                try { json = await res.json(); } catch (_) {}

                if (res.status === 201) {
                    showSignatureAlert('success', (json && json.message) || 'Assinatura realizada com sucesso!');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-check"></i>Assinado';
                    }
                    setTimeout(function () {
                        closeSignatureModal();
                    }, 1800);
                } else if (res.status === 409) {
                    showSignatureAlert('error', (json && json.message) || 'Este e-mail já assinou este documento.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-pen"></i>Confirmar Assinatura';
                    }
                } else if (res.status === 422 && json && json.errors) {
                    const errs = json.errors || {};
                    if (errs.name && errs.name[0]) setFieldError('name', errs.name[0]);
                    if (errs.email && errs.email[0]) setFieldError('email', errs.email[0]);
                    if (errs.cpf && errs.cpf[0]) setFieldError('cpf', errs.cpf[0]);
                    showSignatureAlert('error', 'Verifique os dados e tente novamente.');
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-pen"></i>Confirmar Assinatura';
                    }
                } else {
                    showSignatureAlert('error', (json && json.message) || ('Erro ao enviar (HTTP ' + res.status + '). Tente novamente.'));
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = '<i class="fas fa-pen"></i>Confirmar Assinatura';
                    }
                }
            } catch (err) {
                console.warn('Erro no envio da assinatura:', err);
                showSignatureAlert('error', 'Falha na conexão. Verifique sua internet e tente novamente.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-pen"></i>Confirmar Assinatura';
                }
            }
        }

        bindSignButtons(document);
        document.addEventListener('keydown', function escSignature(e) {
            if (e.key === 'Escape') {
                const modalEl = document.getElementById('signature-modal');
                if (modalEl && !modalEl.classList.contains('hidden')) {
                    closeSignatureModal();
                }
            }
        });
    </script>
</body>
</html>
