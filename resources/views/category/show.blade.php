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
    </script>
</body>
</html>
