@extends('layout.app')

@section('title', 'Services - Applications')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Climate+Crisis:YEAR@1979&family=Revalia&display=swap');

    :root {
        --indigo:       #534AB7;
        --indigo-light: #EEEDFE;
        --indigo-mid:   #AFA9EC;
        --green-light:  #EAF3DE;
        --green:        #3B6D11;
        --blue-light:   #E6F1FB;
        --blue:         #185FA5;
        --radius-sm:    8px;
        --radius-md:    14px;
        --radius-lg:    20px;
        --radius-xl:    28px;
        --shadow-sm:    0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
        --shadow-md:    0 4px 16px rgba(0,0,0,.08);
    }

    .port *,
    .port *::before,
    .port *::after { box-sizing: border-box; }

    .port {
        font-family: 'Inter', system-ui, sans-serif;
        padding: 2rem 1rem 4rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
        color: #1a1a2e;
    }

    /* ── BOUTONS ─────────────────────────────────────────── */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--indigo);
        color: #fff;
        padding: 10px 20px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        transition: background .2s, transform .15s;
        border: none;
        cursor: pointer;
    }
    .btn-primary:hover { background: #3C3489; transform: translateY(-1px); }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        color: #333;
        padding: 10px 20px;
        border-radius: var(--radius-sm);
        font-size: 13.5px;
        font-weight: 500;
        text-decoration: none;
        border: 1px solid #ddd;
        transition: border-color .2s, background .2s, transform .15s;
        cursor: pointer;
    }
    .btn-ghost:hover { border-color: var(--indigo-mid); background: var(--indigo-light); transform: translateY(-1px); }

    /* ── CARTES SERVICES (Inspiré de proj-card) ────────── */
    .app-card {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: var(--radius-lg);
        padding: 1.5rem;
        transition: transform .2s, box-shadow .2s, border-color .2s;
        display: flex;
        flex-direction: column;
        height: 100%;
        text-decoration: none;
    }
    .app-card:hover { 
        transform: translateY(-4px); 
        box-shadow: var(--shadow-md);
        border-color: var(--indigo-light);
    }

    .app-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: var(--radius-md);
        background: var(--indigo-light);
        color: var(--indigo);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-bottom: 1.2rem;
        transition: transform .3s;
    }
    .app-card:hover .app-icon-wrapper { transform: scale(1.1) rotate(-5deg); }

    .app-card h3 {
        font-family: 'Syne', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #0f0e1a;
        margin-bottom: .5rem;
    }

    .app-card p {
        font-size: 13px;
        color: #666;
        line-height: 1.6;
        flex-grow: 1;
        margin-bottom: 1.2rem;
    }

    .app-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f0f0f0;
        padding-top: 1rem;
    }

    .app-link-text {
        font-size: 13px;
        font-weight: 600;
        color: var(--indigo);
        display: flex;
        align-items: center;
        gap: 5px;
        transition: gap .15s;
    }
    .app-card:hover .app-link-text { gap: 8px; }

    /* ── CUSTOM SEARCH ─────────────────────────────────── */
    .search-container {
        background-color: white;
        padding: 2rem;
        border-radius: var(--radius-lg);
        box-shadow: 5px 5px 1px #4d43b9;
        border: 2px solid #0f0e1a;
        position: relative; 
        z-index: 1;
    }

    .search-input {
        width: 100%;
        font-family: 'Inter', sans-serif;
        padding: 15px 20px 15px 50px;
        border-radius: var(--radius-sm);
        border: 2px solid #ebebeb;
        font-size: 15px;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: #fafafa;
    }
    .search-input:focus {
        outline: none;
        border-color: var(--indigo);
        box-shadow: 0 0 0 4px var(--indigo-light);
        background: #fff;
    }

    .search-icon {
        position: absolute;
        left: 35px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: #888;
    }

    h4.climate-title {
        opacity: 0;
        animation: fadeIn 1s ease-in-out forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="port max-w-7xl mx-auto">

    {{-- ── HERO / BARRE DE RECHERCHE ────────────────────── --}}
    <section class="relative flex flex-col items-center justify-center py-10 mb-5 overflow-hidden">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full flex items-center justify-center z-0 pointer-events-none">
            <h4 style="font-family: 'Climate Crisis'" class="climate-title text-4xl md:text-8xl text-black/5 text-center transform scale-y-150 whitespace-nowrap">
                NOS SERVICES
            </h4>
        </div>
        
        <div class="relative z-10 w-full max-w-3xl text-center">
            <h1 style="font-family: 'Syne'" class="text-3xl md:text-5xl font-extrabold text-white mb-3">
                Découvrez nos <em class="text-[#4d43b9] not-italic">Applications</em>
            </h1>
            <p class="text-gray-500 mb-8 max-w-xl mx-auto text-sm md:text-base">Recherchez instantanément les meilleurs outils classés par catégories pour booster votre productivité.</p>
            
            <div class="search-container flex items-center">
                <i class='bx bx-search search-icon'></i>
                <input type="text" id="searchInput" placeholder="Rechercher une application (ex: Figma, Laravel)..." class="search-input">
            </div>
        </div>
    </section>

    {{-- ── CATÉGORIES ──────────────────────────────────── --}}
    <section class="mb-4">
        <div class="flex items-center gap-3 mb-6">
            <i class='bx bx-category text-2xl text-[#534AB7]'></i>
            <h2 style="font-family: 'Syne'" class="text-xl font-bold text-white">Filtres</h2>
        </div>

        <div class="flex flex-wrap gap-3" id="categoryFilters">
            <button class="filter-btn btn-primary" data-category="all">Toutes</button>
            @foreach($categories as $category)
                <button class="filter-btn btn-ghost" data-category="{{ $category->id }}">
                    {{ $category->nom }}
                </button>
            @endforeach
        </div>
    </section>

    {{-- ── GRILLE DES APPLICATIONS ─────────────────────── --}}
    <section>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="applicationsGrid">
            @foreach($applications as $app)
                <a href="{{ $app->lien }}" target="_blank" class="app-card" data-category="{{ $app->categorie_id }}" data-name="{{ strtolower($app->nom) }}">
                    
                    <div class="app-icon-wrapper">
                        @if($app->icone)
                            <img src="{{ asset('storage/' . $app->icone) }}" alt="{{ $app->nom }}" class="w-8 h-8 object-contain">
                        @else
                            {{-- Icône par défaut si pas d'image (utilise la première lettre du nom) --}}
                            <span class="font-bold font-['Syne']">{{ substr($app->nom, 0, 1) }}</span>
                        @endif
                    </div>
                    
                    <h3>{{ $app->nom }}</h3>
                    <p>{{ $app->description }}</p>
                    
                    <div class="app-footer">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#3B6D11] bg-[#EAF3DE] px-3 py-1 rounded-full">
                            {{ $app->category->nom ?? 'Général' }}
                        </span>
                        
                        <div class="app-link-text">
                            Ouvrir <i class='bx bx-right-arrow-alt text-lg'></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div id="noResults" class="hidden text-center py-16 bg-white border border-gray-100 rounded-[20px] mt-6">
            <div class="w-20 h-20 mx-auto bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-4xl mb-4">
                <i class='bx bx-ghost'></i>
            </div>
            <h3 style="font-family: 'Syne'" class="text-xl font-bold text-[#0f0e1a] mb-2">Aucune application trouvée</h3>
            <p class="text-gray-500 text-sm">Essayez de modifier vos termes de recherche ou de changer de catégorie.</p>
        </div>
    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const appCards = document.querySelectorAll('.app-card');
        const filterBtns = document.querySelectorAll('.filter-btn');
        const noResults = document.getElementById('noResults');

        let currentCategory = 'all';
        let searchQuery = '';

        function filterApps() {
            let visibleCount = 0;

            appCards.forEach(card => {
                const matchesCategory = currentCategory === 'all' || card.dataset.category === currentCategory;
                const matchesSearch = card.dataset.name.includes(searchQuery);

                if (matchesCategory && matchesSearch) {
                    card.style.display = 'flex'; // On garde display: flex pour la structure de la carte
                    setTimeout(() => { card.style.opacity = '1'; }, 50);
                    visibleCount++;
                } else {
                    card.style.opacity = '0';
                    setTimeout(() => { card.style.display = 'none'; }, 200); // Petit délai pour fluidité
                }
            });

            if (visibleCount === 0) {
                setTimeout(() => { noResults.classList.remove('hidden'); }, 200);
            } else {
                noResults.classList.add('hidden');
            }
        }

        searchInput.addEventListener('input', (e) => {
            searchQuery = e.target.value.toLowerCase();
            filterApps();
        });

        filterBtns.forEach(button => {
            button.addEventListener('click', (e) => {
                // Mettre tous les boutons en style inactif (btn-ghost)
                filterBtns.forEach(btn => {
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-ghost');
                });
                
                // Mettre le bouton cliqué en style actif (btn-primary)
                const target = e.currentTarget;
                target.classList.remove('btn-ghost');
                target.classList.add('btn-primary');

                currentCategory = target.dataset.category;
                filterApps();
            });
        });
    });
</script>
@endsection