@extends('layout.app')

@section('title', 'Portfolio - Développeur Web & Mobile')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=Inter:wght@400;500&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Climate+Crisis&family=Revalia&display=swap');

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

    /* ── HERO ─────────────────────────────────────────── */
    .hero {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 2.5rem;
        align-items: center;
        box-shadow: 25px -45px 20px 0px inset rgba(0, 0, 0, 0.81);
        padding: 2.5rem 3rem;
        position: relative;
        overflow: hidden;
    }

    .hero-content { 
        background-color: white;
        padding: 1.75rem;
        border-radius: var(--radius-lg);
        box-shadow: 5px 5px 1px #4d43b9;
        position: relative; 
        z-index: 1; 
    }

    .avail-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Inter', sans-serif;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: .06em;
        text-transform: uppercase;
        color: #166534;
        background: #EAF3DE;
        border: 1px solid #C0DD97;
        padding: 5px 12px;
        border-radius: 40px;
        margin-bottom: 1.2rem;
    }

    .avail-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        background: #3B6D11;
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { opacity: 1; transform: scale(1); }
        50%       { opacity: .5; transform: scale(.8); }
    }

    .hero h1 {
        font-family: 'Syne', sans-serif;
        font-size: clamp(28px, 4vw, 40px);
        font-weight: 800;
        line-height: 1.15;
        color: #0f0e1a;
        margin-bottom: .6rem;
    }

    .hero h1 em {
        font-style: normal;
        color: var(--indigo);
    }

    .hero-sub {
        font-size: 15px;
        color: #555;
        line-height: 1.7;
        max-width: 380px;
        margin-bottom: 1.75rem;
    }

    .hero-btns {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

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

    .hero-avatar {
        position: relative;
        z-index: 1;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(135deg, #AFA9EC 0%, #534AB7 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 8px 32px rgba(83,74,183,.28);
    }

    .hero-avatar i {
        font-size: 44px;
        color: rgba(255,255,255,.92);
    }

    /* ── STATS ────────────────────────────────────────── */
    .stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
    }

    .stat-card {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: var(--radius-md);
        padding: 1.1rem 1.25rem;
        text-align: center;
        transition: transform .2s, box-shadow .2s;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: var(--shadow-md); }

    .stat-num {
        font-family: 'Syne', sans-serif;
        font-size: 26px;
        font-weight: 800;
        color: var(--indigo);
        display: block;
    }

    .stat-lbl {
        font-size: 12px;
        color: #888;
        margin-top: 2px;
        display: block;
    }

    /* ── SECTION LABEL ────────────────────────────────── */
    .section-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'Syne', sans-serif;
        font-size: 18px;
        font-weight: 700;
        color: #0f0e1a;
        margin-bottom: 1rem;
    }

    .section-label i {
        color: var(--indigo);
        font-size: 20px;
    }

    /* ── SKILLS ───────────────────────────────────────── */
    .skills-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 12px;
    }

    .skill-card {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: var(--radius-md);
        padding: 1.4rem;
        transition: transform .2s, box-shadow .2s;
    }
    .skill-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }

    .skill-icon {
        width: 42px;
        height: 42px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 19px;
        margin-bottom: 1rem;
    }

    .si-blue   { background: var(--blue-light);  color: var(--blue); }
    .si-green  { background: var(--green-light); color: var(--green); }
    .si-purple { background: var(--indigo-light); color: var(--indigo); }

    .skill-card h3 {
        font-family: 'Syne', sans-serif;
        font-size: 14px;
        font-weight: 700;
        color: #0f0e1a;
        margin-bottom: .4rem;
    }

    .skill-card p {
        font-size: 12.5px;
        color: #666;
        line-height: 1.6;
        margin-bottom: .8rem;
    }

    .tags { display: flex; flex-wrap: wrap; gap: 5px; }

    .tag {
        font-size: 11px;
        font-weight: 500;
        color: var(--indigo);
        background: var(--indigo-light);
        padding: 3px 9px;
        border-radius: 20px;
    }

    /* ── PROJECTS ─────────────────────────────────────── */
    .projects-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .proj-card {
        background: #fff;
        border: 1px solid #ebebeb;
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: transform .2s, box-shadow .2s;
    }
    .proj-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }

    .proj-thumb {
        height: 140px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .pt-purple { background: var(--indigo-light); }
    .pt-blue   { background: var(--blue-light); }

    .proj-thumb i { font-size: 46px; color: var(--indigo-mid); }
    .pt-blue .proj-thumb i { color: #85B7EB; }

    .proj-type-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        font-size: 10.5px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
        letter-spacing: .03em;
    }

    .ptb-mobile { background: var(--indigo-light); color: var(--indigo); }
    .ptb-web    { background: var(--blue-light);   color: var(--blue); }

    .proj-body { padding: 1.2rem; }

    .proj-body h3 {
        font-family: 'Syne', sans-serif;
        font-size: 15px;
        font-weight: 700;
        color: #0f0e1a;
        margin-bottom: .35rem;
    }

    .proj-body p {
        font-size: 12.5px;
        color: #666;
        line-height: 1.6;
        margin-bottom: .75rem;
    }

    .proj-tags { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 1rem; }

    .proj-tag {
        font-size: 11px;
        color: #555;
        background: #f4f4f4;
        padding: 3px 9px;
        border-radius: 5px;
    }

    .proj-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f0f0f0;
        padding-top: .85rem;
    }

    .proj-link {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 13px;
        font-weight: 600;
        color: var(--indigo);
        text-decoration: none;
        transition: gap .15s;
    }
    .proj-link:hover { gap: 8px; }

    .proj-gh {
        font-size: 19px;
        color: #aaa;
        text-decoration: none;
        transition: color .2s;
    }
    .proj-gh:hover { color: #333; }

    /* ── CTA ──────────────────────────────────────────── */
    .cta {
        background: #fff;
        border: 1px solid #e8e6f9;
        border-radius: var(--radius-xl);
        padding: 2.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .cta::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(ellipse 70% 80% at 50% 110%, #EEEDFE 0%, transparent 65%);
        pointer-events: none;
    }

    .cta > * { position: relative; z-index: 1; }

    .cta h2 {
        font-family: 'Syne', sans-serif;
        font-size: 22px;
        font-weight: 800;
        color: #0f0e1a;
        margin-bottom: .5rem;
    }

    .cta p {
        font-size: 14px;
        color: #666;
        line-height: 1.7;
        max-width: 400px;
        margin: 0 auto 1.5rem;
    }
    
    .animated-title {
        opacity: 0;
        animation: fadeIn 1s ease-in-out forwards;
    }

    /* ── RESPONSIVE ───────────────────────────────────── */
    @media (max-width: 700px) {
        .hero {
            grid-template-columns: 1fr;
            padding: 1.75rem;
        }
        .hero-content { 
            margin-top: 60px;
        }
        .hero-avatar { display: none; }
        .skills-grid,
        .projects-grid,
        .stats { grid-template-columns: 1fr; }
    }
    @media (max-width: 500px) {
        .stats { grid-template-columns: repeat(3, 1fr); }
    }

    .float{
        animation:floating 5s ease-in-out infinite;
    }

    @keyframes floating{
        0%,100%{ transform:translateY(0px); }
        50%{ transform:translateY(-15px); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>

<div class="port">

    {{-- ── HERO ────────────────────────────────────── --}}
    <section class="hero">
        <div class="absolute top-16 left-1/2 -translate-x-1/2 w-full flex items-center justify-center gap-4" >
            <h4 style="font-family: 'Climate Crisis'" class="animated-title text-3xl md:text-6xl text-white/60 text-center transform scale-y-240">DEVELOPPEUR FULL-STACK <em class="text-[#4d43b9]">WEB & MOBILE</em></h4>
        </div>
        <div class="pt-25 relative z-10 flex flex-wrap items-center justify-center gap-6">
            <div class="hero-content">
                <h4 style="font-family: 'Revalia'" class="text-xl font-bold text-[#4d43b9]">Bonjour, je suis <em>Christ Aka</em></h4>
                <p class="hero-sub text-justify">Développeur web et mobile Passionné par l’ingénierie logicielle et la création de produits numériques. Mon quotidien ? Traduire des besoins complexes en applications fluides, sécurisées et performantes.</p>
                <div class="hero-btns">
                    <a href="#projets" class="bg-[#4d43b9] w-8 h-8 rounded-full flex items-center justify-center text-2xl text-white hover:bg-[#3a318a] transition duration-300">
                         <i class='bx bxl-facebook'></i>
                    </a>
                    <a href="#contact" class="bg-[#4d43b9] w-8 h-8 rounded-full flex items-center justify-center text-2xl text-white hover:bg-[#3a318a] transition duration-300">
                        <i class='bx bxl-whatsapp'></i>    
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- ── COMPÉTENCES ─────────────────────────────── --}}
    <section class="bg-white p-5 rounded-xl w-full">
        <h4 style="font-family: 'Climate Crisis'" class="animated-title mb-8 text-center -ml-px text-3xl md:text-8xl text-black mb-6">
            ABOUT ME
        </h4>
        <p class="text-center text-gray-700 mb-8 max-w-7xl mx-auto text-justify">
            j'ai développé une expertise solide en développement web et mobile. Passionné par l'ingénierie logicielle, j'aime relever les défis techniques et créer des solutions innovantes qui font la différence.
            Mon objectif au quotidien est simple : concevoir des applications robustes, scalables et centrées sur l'expérience utilisateur. Au fil de mes projets, j'ai appris à maîtriser l'ensemble du cycle de vie d'un produit numérique, de la modélisation de bases de données sécurisées à l'intégration d'interfaces modernes et réactives.
            Que ce soit pour développer des architectures backend complexes, concevoir des outils industriels sur-mesure ou propulser des applications mobiles intuitives, je mets un point d'honneur à écrire un code propre, performant et hautement sécurisé. Curieux de nature et orienté solutions, je vois chaque contrainte technique comme une opportunité d’innover et de dépasser les attentes.
        </p>

        <h4 style="font-family: 'Climate Crisis'" class="animated-title mb-15 text-center -ml-px text-2xl md:text-8xl text-black mb-6">
            MES COMPÉTENCES
        </h4>

        <div class="flex flex-wrap gap-10 justify-center max-w-8xl mx-auto">
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('7.webp') }}" alt="" class="w-full h-full object-contain">
            </div>
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('React-icon.svg.png') }}" alt="React" class="w-full h-full object-contain">
            </div>
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('py.png') }}" alt="Python" class="w-full h-full object-contain">
            </div>
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('Adobe_Illustrator_CC_icon.svg.png') }}" alt="Illustrator" class="w-full h-full object-contain">
            </div>
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('Adobe_Photoshop_CC_icon.svg.png') }}" alt="Photoshop" class="w-full h-full object-contain">
            </div>
            
            <div class="flex w-40 h-32 items-center justify-center hover:translate-y-[-10px] transition-transform duration-300 ease-in-out">
                <img src="{{ asset('Canva_logo.svg.png') }}" alt="Canva" class="w-full h-full object-contain">
            </div>

        </div>
    </section>

    {{-- ── CONTACT ──────────────────────────────────── --}}
    <section id="contact" class="grid grid-cols-1 md:grid-cols-2 gap-10 bg-white p-7 md:p-10 rounded-xl w-full shadow-sm">
        
        <div class="flex flex-col gap-8">
            <div>
                <h4 style="font-family: 'Climate Crisis'" class="animated-title text-3xl md:text-5xl text-black mb-4">
                    CONTACTEZ-MOI
                </h4>
                <p class="text-gray-600 text-sm md:text-base leading-relaxed text-justify">
                    Vous avez un projet en tête, une opportunité de collaboration ou simplement une question ? Remplissez le formulaire ou utilisez mes coordonnées directes. Je vous répondrai dans les plus brefs délais.
                </p>
            </div>

            <div class="flex flex-col gap-5 mt-2">
                <div class="flex items-center gap-4 group cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-[#EEEDFE] text-[#534AB7] flex items-center justify-center text-2xl transition duration-300 group-hover:scale-110">
                        <i class='bx bx-map'></i>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Localisation</span>
                        <span class="text-[#0f0e1a] font-medium">Abidjan, Côte d'Ivoire</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 group cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-[#EAF3DE] text-[#3B6D11] flex items-center justify-center text-2xl transition duration-300 group-hover:scale-110">
                        <i class='bx bx-envelope'></i>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Email</span>
                        <a href="mailto:akaahuiurvanchristmorel@gmail.com" class="text-[#0f0e1a] font-medium hover:text-[#534AB7] transition">contact@gmail.com</a>
                    </div>
                </div>

                <div class="flex items-center gap-4 group cursor-pointer">
                    <div class="w-14 h-14 rounded-full bg-[#E6F1FB] text-[#185FA5] flex items-center justify-center text-2xl transition duration-300 group-hover:scale-110">
                        <i class='bx bx-phone'></i>
                    </div>
                    <div>
                        <span class="block text-xs text-gray-500 font-semibold uppercase tracking-wider mb-1">Téléphone</span>
                        <a href="tel:+2250150663744" class="text-[#0f0e1a] font-medium hover:text-[#534AB7] transition">+225 01 50 66 37 44</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 p-6 md:p-8 rounded-[20px] border border-gray-100 shadow-sm">
            <form action="#" method="POST" class="flex flex-col gap-5">
                @csrf 
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="flex flex-col gap-1.5">
                        <label for="name" style="font-family: 'Syne', sans-serif;" class="text-sm font-bold text-[#0f0e1a]">Nom complet</label>
                        <input type="text" id="name" name="name" class="px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#534AB7] focus:ring-1 focus:ring-[#534AB7] transition bg-white" placeholder="John Doe" required>
                    </div>
                    <div class="flex flex-col gap-1.5">
                        <label for="email" style="font-family: 'Syne', sans-serif;" class="text-sm font-bold text-[#0f0e1a]">Adresse email</label>
                        <input type="email" id="email" name="email" class="px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#534AB7] focus:ring-1 focus:ring-[#534AB7] transition bg-white" placeholder="john@example.com" required>
                    </div>
                </div>
                
                <div class="flex flex-col gap-1.5">
                    <label for="subject" style="font-family: 'Syne', sans-serif;" class="text-sm font-bold text-[#0f0e1a]">Sujet</label>
                    <input type="text" id="subject" name="subject" class="px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#534AB7] focus:ring-1 focus:ring-[#534AB7] transition bg-white" placeholder="Proposition de mission" required>
                </div>

                <div class="flex flex-col gap-1.5">
                    <label for="message" style="font-family: 'Syne', sans-serif;" class="text-sm font-bold text-[#0f0e1a]">Message</label>
                    <textarea id="message" name="message" rows="5" class="px-4 py-3 border border-gray-200 rounded-lg focus:outline-none focus:border-[#534AB7] focus:ring-1 focus:ring-[#534AB7] transition bg-white resize-none" placeholder="Parlez-moi de votre projet..." required></textarea>
                </div>

                <button type="submit" class="btn-primary mt-2 justify-center py-3.5 text-base w-full group">
                    Envoyer le message 
                    <i class='bx bx-send text-lg ml-1 group-hover:translate-x-1 transition-transform'></i>
                </button>
            </form>
        </div>
         
    </section>

</div>

@endsection