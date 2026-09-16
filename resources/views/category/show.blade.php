<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $currentCategory['name'] }} - Sistema de Compliance</title>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous"
          referrerpolicy="no-referrer" />
    @if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
    <header class="bg-gradient-to-r from-slate-800 via-slate-900 to-slate-800 text-white">
        <div class="max-w-7xl mx-auto px-4 py-5 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 rounded-lg bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-900/30 group-hover:scale-105 transition-transform">
                        <i class="fas fa-shield-halved text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold leading-tight">Sistema de Compliance</h1>
                        <p class="text-xs sm:text-sm text-slate-300">Portal de Documentos</p>
                    </div>
                </a>
                <nav id="breadcrumb" class="hidden sm:flex items-center gap-2 text-sm text-slate-300">
                    <a href="{{ route('home') }}" class="hover:text-white transition"><i class="fas fa-home"></i></a>
                    <i class="fas fa-chevron-right text-xs text-slate-500"></i>
                    <a href="{{ route('home') }}" class="hover:text-white transition">Categorias</a>
                    <i class="fas fa-chevron-right text-xs text-slate-500"></i>
                    <span class="text-white font-medium">{{ $currentCategory['name'] }}</span>
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
        <section class="mb-10">
            <div class="bg-gradient-to-r {{ $currentCategory['color'] }} rounded-2xl overflow-hidden shadow-lg">
                <div class="relative p-6 sm:p-8 text-white">
                    <div class="absolute -right-8 -top-8 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>
                    <div class="relative flex items-start justify-between gap-4 flex-wrap">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-16 h-16 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center">
                                <i class="fas {{ $currentCategory['icon'] }} text-2xl"></i>
                            </div>
                            <div class="min-w-0">
                                <h2 class="text-2xl sm:text-3xl font-bold flex items-center gap-2">
                                    {{ $currentCategory['name'] }}
                                    <span class="text-base font-normal bg-white/15 backdrop-blur px-2.5 py-1 rounded-full align-middle">
                                        ID {{ $currentCategory['id'] }}
                                    </span>
                                </h2>
                                <p class="text-white/85 mt-1 max-w-2xl">
                                    {{ $currentCategory['description'] ?? 'Documentos da categoria ' . $currentCategory['name'] . '.' }}
                                </p>
                                <div class="mt-3 flex flex-wrap items-center gap-3 text-sm">
                                    <span class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur px-3 py-1 rounded-full">
                                        <i class="fas fa-file-lines"></i>
                                        {{ $currentCategory['files_count'] }} {{ $currentCategory['files_count'] === 1 ? 'arquivo' : 'arquivos' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-white text-slate-800 font-medium shadow hover:shadow-lg hover:-translate-y-0.5 transition">
                            <i class="fas fa-arrow-left"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <aside class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="px-4 py-3 bg-slate-50 border-b border-slate-200">
                        <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-list text-blue-600"></i>
                            Todas as Categorias
                        </h3>
                    </div>
                    <ul class="divide-y divide-slate-100 max-h-[520px] overflow-y-auto">
                        @foreach($allCategories as $cat)
                            @php
                                $active = $cat['id'] === $currentCategory['id'];
                            @endphp
                            <li>
                                <a href="{{ $cat['show_url'] }}"
                                   class="flex items-center gap-3 px-4 py-3 text-sm transition group {{ $active ? 'bg-blue-50 border-l-4 border-l-blue-600' : 'border-l-4 border-l-transparent hover:bg-slate-50' }}">
                                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg {{ $cat['icon_bg'] }}">
                                        <i class="fas {{ $cat['icon'] }}"></i>
                                    </span>
                                    <span class="flex-1 min-w-0">
                                        <span class="block font-medium {{ $active ? 'text-blue-900' : 'text-slate-800 group-hover:text-slate-900' }} truncate">
                                            {{ $cat['name'] }}
                                        </span>
                                        <span class="text-xs text-slate-500">ID: {{ $cat['id'] }}</span>
                                    </span>
                                    @if($active)
                                        <i class="fas fa-chevron-right text-xs text-blue-600"></i>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            <section class="lg:col-span-3">
                <div class="flex items-center justify-between flex-wrap gap-4 mb-4">
                    <div>
                        <h3 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fas fa-folder-open text-blue-600"></i>
                            Diretório de Arquivos
                            <span class="text-sm font-normal text-slate-500">(Categoria ID {{ $currentCategory['id'] }})</span>
                        </h3>
                        <p class="text-sm text-slate-500 mt-1">
                            Selecione um arquivo para abrir ou baixar.
                        </p>
                    </div>
                    <div class="relative w-full sm:w-80">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input id="search-input"
                               type="text"
                               placeholder="Buscar arquivo..."
                               class="w-full pl-10 pr-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition">
                    </div>
                </div>

                <div id="doc-count" class="mb-3 text-sm text-slate-500"></div>

                <div id="files-root" class="bg-white rounded-xl shadow-sm border border-slate-200 divide-y divide-slate-100 overflow-hidden">
                    @if($files->isEmpty())
                        <div class="p-12 text-center">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                                <i class="fas fa-folder-open text-2xl"></i>
                            </div>
                            <h4 class="text-lg font-semibold text-slate-700 mb-1">Nenhum arquivo encontrado</h4>
                            <p class="text-sm text-slate-500">Esta categoria (ID {{ $currentCategory['id'] }}) ainda não possui arquivos publicados.</p>
                        </div>
                    @else
                        @foreach($files as $file)
                            @php
                                $ext = strtolower($file['extension'] ?? '');
                                switch ($ext) {
                                    case 'pdf':
                                        $fi = ['icon' => 'fa-file-pdf', 'type' => 'PDF', 'color' => 'text-red-500 bg-red-50'];
                                        break;
                                    case 'docx':
                                    case 'doc':
                                        $fi = ['icon' => 'fa-file-word', 'type' => 'DOC', 'color' => 'text-blue-600 bg-blue-50'];
                                        break;
                                    case 'xlsx':
                                    case 'xls':
                                        $fi = ['icon' => 'fa-file-excel', 'type' => 'XLS', 'color' => 'text-green-600 bg-green-50'];
                                        break;
                                    case 'pptx':
                                    case 'ppt':
                                        $fi = ['icon' => 'fa-file-powerpoint', 'type' => 'PPT', 'color' => 'text-orange-600 bg-orange-50'];
                                        break;
                                    case 'jpg':
                                    case 'jpeg':
                                    case 'png':
                                    case 'gif':
                                    case 'svg':
                                    case 'webp':
                                        $fi = ['icon' => 'fa-file-image', 'type' => strtoupper($ext), 'color' => 'text-purple-600 bg-purple-50'];
                                        break;
                                    default:
                                        $fi = ['icon' => 'fa-file', 'type' => $ext ? strtoupper($ext) : 'FILE', 'color' => 'text-slate-500 bg-slate-50'];
                                }
                            @endphp
                            <article class="file-row group p-4 sm:p-5 transition hover:bg-slate-50"
                                     data-file-id="{{ $file['id'] }}"
                                     data-name="{{ $file['display_name'] }}"
                                     data-desc="{{ $file['description'] ?? '' }}">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 flex items-center justify-center w-12 h-12 rounded-xl {{ $fi['color'] }}">
                                        <i class="fas {{ $fi['icon'] }} text-xl"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-start justify-between gap-3 flex-wrap">
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase {{ $fi['color'] }}">
                                                        {{ $fi['type'] }}
                                                    </span>
                                                    <span class="inline-flex items-center gap-1 text-xs text-slate-500">
                                                        <i class="fas fa-id-badge text-slate-400"></i>
                                                        Arquivo ID {{ $file['id'] }}
                                                    </span>
                                                </div>
                                                <h4 class="mt-1 font-semibold text-slate-800 text-base truncate">{{ $file['display_name'] }}</h4>
                                                <p class="text-sm text-slate-500 mt-0.5 line-clamp-2">
                                                    {{ $file['description'] ?? 'Sem descrição.' }}
                                                </p>
                                                <div class="mt-2 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500">
                                                    @if(filled($file['user_name']))
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-user-circle text-slate-400"></i>
                                                            {{ $file['user_name'] }}
                                                        </span>
                                                    @endif
                                                    @if(filled($file['created_at']))
                                                        <span class="inline-flex items-center gap-1.5">
                                                            <i class="fas fa-calendar text-slate-400"></i>
                                                            {{ $file['created_at'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                <a href="{{ $file['url'] }}"
                                                   target="_blank"
                                                   rel="noopener noreferrer"
                                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-blue-50 text-blue-700 hover:bg-blue-100 transition"
                                                   title="Abrir em nova aba">
                                                    <i class="fas fa-up-right-from-square"></i>
                                                    Abrir
                                                </a>
                                                <button type="button"
                                                        class="sign-btn inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-emerald-50 text-emerald-700 hover:bg-emerald-100 transition"
                                                        data-file-id="{{ $file['id'] }}"
                                                        data-file-name="{{ $file['display_name'] }}"
                                                        title="Assinar Termo de Ciência">
                                                    <i class="fas fa-signature"></i>
                                                    Assinar
                                                </button>
                                                <a href="{{ $file['url'] }}"
                                                   download
                                                   class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-medium bg-slate-100 text-slate-700 hover:bg-slate-200 transition"
                                                   title="Baixar arquivo">
                                                    <i class="fas fa-download"></i>
                                                    Baixar
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    @endif
                </div>
            </section>
        </div>
    </main>

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

        const searchInput = document.getElementById('search-input');
        const docCount = document.getElementById('doc-count');
        const rows = Array.from(document.querySelectorAll('.file-row'));

        function render(term) {
            term = (term || '').trim().toLowerCase();
            let visible = 0;
            rows.forEach(row => {
                const name = (row.dataset.name || '').toLowerCase();
                const desc = (row.dataset.desc || '').toLowerCase();
                const match = !term || name.includes(term) || desc.includes(term);
                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });
            if (docCount) {
                docCount.textContent = `Exibindo ${visible} de ${rows.length} arquivo(s) da categoria ID {{ $currentCategory['id'] }}.`;
            }
        }

        if (searchInput) {
            searchInput.addEventListener('input', (e) => render(e.target.value));
        }
        render('');

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
