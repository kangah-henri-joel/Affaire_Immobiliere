/**
 * ux-helpers.js
 * Améliorations UX pour les utilisateurs débutants :
 *  - Guide interactif (Onboarding Tour) à la première visite
 *  - Bouton Remonter en haut
 *  - Toggle visibilité mot de passe
 *  - Bouton d'aide flottant avec FAQ rapide
 *  - Tooltips explicatifs sur les champs de formulaire
 */

(function () {
    'use strict';

    // ── 1. BOUTON « REMONTER EN HAUT » ─────────────────────────────────────────
    function createScrollTopButton() {
        const btn = document.createElement('button');
        btn.id = 'scrollTopBtn';
        btn.innerHTML = '<i class="fas fa-chevron-up"></i>';
        btn.setAttribute('title', 'Remonter en haut');
        btn.setAttribute('aria-label', 'Remonter en haut de la page');
        document.body.appendChild(btn);

        window.addEventListener('scroll', () => {
            btn.classList.toggle('visible', window.scrollY > 400);
        });

        btn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // ── 2. TOGGLE VISIBILITÉ MOT DE PASSE ──────────────────────────────────────
    function addPasswordToggles() {
        document.querySelectorAll('input[type="password"]').forEach(input => {
            // Ne pas doubler si déjà un toggle
            if (input.parentElement.querySelector('.pwd-toggle')) return;

            const wrapper = document.createElement('div');
            wrapper.className = 'pwd-input-wrapper';
            wrapper.style.position = 'relative';

            input.parentNode.insertBefore(wrapper, input);
            wrapper.appendChild(input);

            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'pwd-toggle';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            toggleBtn.setAttribute('aria-label', 'Afficher/masquer le mot de passe');
            toggleBtn.setAttribute('title', 'Cliquez pour voir votre mot de passe');
            wrapper.appendChild(toggleBtn);

            toggleBtn.addEventListener('click', () => {
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                toggleBtn.innerHTML = isPassword
                    ? '<i class="fas fa-eye-slash"></i>'
                    : '<i class="fas fa-eye"></i>';
            });
        });
    }

    // ── 3. BOUTON D'AIDE FLOTTANT + ACTIONS DE LA PAGE ───────────────────────
    function getPageActions() {
        const path = window.location.pathname.toLowerCase().replace(/\/+$/, '');
        const baseUrl = (typeof BASE_URL !== 'undefined') ? BASE_URL : '';

        // 1. CARTE INTERACTIVE
        if (path.includes('/annonces/map')) {
            return {
                pageName: "Carte Interactive",
                actions: [
                    {
                        icon: 'fa-map-marker-alt',
                        color: '#f59e0b',
                        title: 'Cliquer sur un marqueur',
                        desc: 'Cliquez sur les repères V (Vente) ou L (Location) pour afficher le résumé et la photo du bien.',
                        target: '#global-map',
                        btnText: 'Voir la carte'
                    },
                    {
                        icon: 'fa-crosshairs',
                        color: '#6366f1',
                        title: 'Centrer sur ma position',
                        desc: 'Cliquez sur "Ma position" pour voir automatiquement les annonces géolocalisées près de vous.',
                        target: '.btn-my-location',
                        actionType: 'click',
                        btnText: 'Ma position'
                    },
                    {
                        icon: 'fa-layer-group',
                        color: '#10b981',
                        title: 'Basculer en vue Satellite',
                        desc: 'Utilisez le sélecteur en bas à droite pour passer en mode satellite HD.',
                        target: '.leaflet-control-layers',
                        btnText: 'Changer la vue'
                    },
                    {
                        icon: 'fa-list-ul',
                        color: '#06b6d4',
                        title: 'Parcourir la liste des biens',
                        desc: 'Cliquez sur un bien dans le panneau latéral pour que la carte s\'y rende immédiatement.',
                        target: '.map-annonce-list',
                        btnText: 'Liste latérale'
                    }
                ]
            };
        }

        // 2. DÉTAIL ANNONCE (/annonce/{id})
        if (path.includes('/annonce/')) {
            return {
                pageName: "Fiche d'Annonce",
                actions: [
                    {
                        icon: 'fa-whatsapp',
                        color: '#25d366',
                        title: 'Discuter sur WhatsApp',
                        desc: 'Ouvre un chat direct pré-rempli avec le vendeur pour poser vos questions.',
                        target: '.wa-btn, .btn-whatsapp-detail, a[href*="wa.me"]',
                        btnText: 'Ecrire sur WhatsApp'
                    },
                    {
                        icon: 'fa-phone-alt',
                        color: '#6366f1',
                        title: 'Appeler le propriétaire',
                        desc: 'Appelez directement l\'agent responsable sur son téléphone.',
                        target: 'a[href^="tel:"]',
                        btnText: 'Appeler'
                    },
                    {
                        icon: 'fa-images',
                        color: '#f59e0b',
                        title: 'Galerie Photos & Vidéos',
                        desc: 'Cliquez sur les aperçus pour voir toutes les photos et la vidéo de l\'annonce.',
                        target: '.annonce-media, .main-image, img',
                        btnText: 'Voir les images'
                    }
                ]
            };
        }

        // 3. ANNONCES LISTE (/annonces)
        if (path.includes('/annonces')) {
            return {
                pageName: "Catalogue des Annonces",
                actions: [
                    {
                        icon: 'fa-search',
                        color: '#6366f1',
                        title: 'Filtrer les résultats',
                        desc: 'Indiquez un budget maximum, une ville ou une commune pour affiner.',
                        target: '.filter-card, form',
                        btnText: 'Accéder aux filtres'
                    },
                    {
                        icon: 'fa-map-marked-alt',
                        color: '#06b6d4',
                        title: 'Afficher la carte',
                        desc: 'Visualisez l\'emplacement de tous les biens sur le plan d\'Abidjan et de Côte d\'Ivoire.',
                        url: baseUrl + '/annonces/map',
                        btnText: 'Voir la carte'
                    },
                    {
                        icon: 'fa-hand-pointer',
                        color: '#10b981',
                        title: 'Ouvrir une annonce',
                        desc: 'Cliquez sur n\'importe quelle photo ou titre de carte pour ouvrir la fiche détaillée.',
                        target: '.annonce-card, .annonce-grid',
                        btnText: 'Parcourir les cartes'
                    }
                ]
            };
        }

        // 4. DEMANDES (/demandes)
        if (path.includes('/demandes')) {
            return {
                pageName: "Recherches & Achats",
                actions: [
                    {
                        icon: 'fa-plus-circle',
                        color: '#10b981',
                        title: 'Publier votre recherche',
                        desc: 'Postez gratuitement ce que vous cherchez (terrain, maison, budget) pour recevoir des offres.',
                        target: '#btnOpenDemandeModal, .btn-primary',
                        actionType: 'click',
                        btnText: 'Formulaire de demande'
                    },
                    {
                        icon: 'fa-list-ol',
                        color: '#6366f1',
                        title: 'Consulter les besoins des clients',
                        desc: 'Découvrez ce que les autres utilisateurs recherchent actuellement sur la plateforme.',
                        target: '.demande-card',
                        btnText: 'Voir les recherches'
                    }
                ]
            };
        }

        // 5. CONNEXION / INSCRIPTION (/login, /register)
        if (path.includes('/login') || path.includes('/register')) {
            return {
                pageName: "Espace Membre",
                actions: [
                    {
                        icon: 'fa-sign-in-alt',
                        color: '#6366f1',
                        title: 'Se connecter',
                        desc: 'Saisissez vos identifiants pour gérer vos annonces et messages.',
                        target: '#tab-login, form',
                        btnText: 'Se connecter'
                    },
                    {
                        icon: 'fa-user-plus',
                        color: '#10b981',
                        title: 'Créer un compte (Client ou Agent)',
                        desc: 'Basculez sur l\'onglet Inscription pour créer un compte rapidement.',
                        target: '.tab-btn[data-tab="register"]',
                        actionType: 'click',
                        btnText: 'Créer un compte'
                    },
                    {
                        icon: 'fa-eye',
                        color: '#f59e0b',
                        title: 'Afficher / Masquer le mot de passe',
                        desc: 'Cliquez sur l\'icône œil à droite pour vérifier votre mot de passe.',
                        target: '.pwd-toggle',
                        btnText: 'Voir l\'œil'
                    }
                ]
            };
        }

        // 6. ESPACE ADMIN / SUPER-ADMIN (/admin, /super-admin)
        if (path.includes('/admin') || path.includes('/super-admin')) {
            return {
                pageName: "Espace Administration",
                actions: [
                    {
                        icon: 'fa-plus-circle',
                        color: '#10b981',
                        title: 'Publier une nouvelle annonce',
                        desc: 'Ajoutez une annonce avec photos, prix, type et votre numéro de contact.',
                        url: baseUrl + '/admin/annonces',
                        btnText: 'Créer une annonce'
                    },
                    {
                        icon: 'fa-bullhorn',
                        color: '#f59e0b',
                        title: 'Partager sur les réseaux sociaux',
                        desc: 'Générez des liens et visuels optimisés pour WhatsApp, Facebook et TikTok.',
                        url: baseUrl + '/admin/publications',
                        btnText: 'Outil de diffusion'
                    },
                    {
                        icon: 'fa-comment-dots',
                        color: '#6366f1',
                        title: 'Consulter les messages clients',
                        desc: 'Lisez et répondez aux messages laissés par les visiteurs du site.',
                        url: baseUrl + '/admin/client-messages',
                        btnText: 'Messages'
                    },
                    {
                        icon: 'fa-user-circle',
                        color: '#06b6d4',
                        title: 'Modifier votre profil',
                        desc: 'Mettez à jour vos coordonnées WhatsApp, photo et informations d\'agence.',
                        url: baseUrl + '/admin/profil',
                        btnText: 'Mon Profil'
                    }
                ]
            };
        }

        // 7. PAGE D'ACCUEIL (PAR DÉFAUT)
        return {
            pageName: "Accueil",
            actions: [
                {
                    icon: 'fa-search',
                    color: '#6366f1',
                    title: 'Rechercher un bien',
                    desc: 'Tapez une ville ou un quartier, puis choisissez la catégorie (Maison, Terrain, Engins).',
                    target: '.search-container-glass, .search-form-pro',
                    btnText: 'Barre de recherche'
                },
                {
                    icon: 'fa-filter',
                    color: '#f59e0b',
                    title: 'Filtrer par type (Vente / Location)',
                    desc: 'Affichez uniquement les opportunités à vendre ou à louer en un clic.',
                    target: '.smart-filter-wrap',
                    btnText: 'Filtres de la page'
                },
                {
                    icon: 'fa-hand-pointer',
                    color: '#10b981',
                    title: 'Consulter une annonce',
                    desc: 'Cliquez directement sur n\'importe quelle photo pour ouvrir les détails.',
                    target: '.annonce-card-pro, .annonce-card',
                    btnText: 'Parcourir les biens'
                },
                {
                    icon: 'fa-map-marked-alt',
                    color: '#06b6d4',
                    title: 'Explorer la carte interactive',
                    desc: 'Visualisez l\'emplacement géographique de tous les biens sur le plan.',
                    url: baseUrl + '/annonces/map',
                    btnText: 'Ouvrir la carte'
                }
            ]
        };
    }

    function createHelpButton() {
        // Bouton flottant
        const helpBtn = document.createElement('button');
        helpBtn.id = 'helpFloatBtn';
        helpBtn.innerHTML = `
            <i class="fas fa-lightbulb"></i>
            <span class="help-btn-badge">Aide</span>
        `;
        helpBtn.setAttribute('title', 'Actions possibles sur cette page');
        helpBtn.setAttribute('aria-label', 'Actions et Aide');
        document.body.appendChild(helpBtn);

        const pageInfo = getPageActions();

        // Panneau Aide & Actions
        const panel = document.createElement('div');
        panel.id = 'helpPanel';
        
        let actionsHtml = '';
        pageInfo.actions.forEach((act, idx) => {
            actionsHtml += `
                <div class="page-action-card" data-idx="${idx}">
                    <div class="action-icon" style="background:${act.color}15; color:${act.color};">
                        <i class="fas ${act.icon}"></i>
                    </div>
                    <div class="action-details">
                        <h4>${act.title}</h4>
                        <p>${act.desc}</p>
                        <button class="action-trigger-btn" data-idx="${idx}">
                            ${act.btnText || 'Effectuer'} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        panel.innerHTML = `
            <div class="help-panel-header">
                <div>
                    <h3><i class="fas fa-lightbulb"></i> Actions & Aide</h3>
                    <span class="help-page-tag">📍 Page : ${pageInfo.pageName}</span>
                </div>
                <button class="help-close-btn" aria-label="Fermer">&times;</button>
            </div>
            <div class="help-panel-nav">
                <button class="help-tab-btn active" data-tab="actions">
                    <i class="fas fa-bolt"></i> Actions de cette page
                </button>
                <button class="help-tab-btn" data-tab="faq">
                    <i class="fas fa-question-circle"></i> FAQ & Guide
                </button>
            </div>
            <div class="help-panel-body">
                <div class="help-tab-content active" id="helpTabActions">
                    <div class="page-actions-list">
                        ${actionsHtml}
                    </div>
                </div>
                <div class="help-tab-content" id="helpTabFaq">
                    <div class="faq-item">
                        <button class="faq-question">
                            <i class="fas fa-search"></i> Comment chercher un bien ?
                        </button>
                        <div class="faq-answer">
                            <p>Utilisez la barre de recherche ou la carte interactive pour trouver des maisons, terrains ou véhicules par ville et quartier.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <i class="fab fa-whatsapp"></i> Comment contacter un vendeur ?
                        </button>
                        <div class="faq-answer">
                            <p>Cliquez sur le bouton vert <strong>WhatsApp</strong> présent sous chaque annonce pour ouvrir un chat direct pré-rempli.</p>
                        </div>
                    </div>
                    <div class="faq-item">
                        <button class="faq-question">
                            <i class="fas fa-user-plus"></i> Dois-je créer un compte ?
                        </button>
                        <div class="faq-answer">
                            <p>Non ! La consultation et la prise de contact sont 100% libres. Vous n'avez besoin d'un compte que pour <strong>publier vos annonces</strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(panel);

        // Gestion des onglets
        panel.querySelectorAll('.help-tab-btn').forEach(tabBtn => {
            tabBtn.addEventListener('click', () => {
                panel.querySelectorAll('.help-tab-btn').forEach(b => b.classList.remove('active'));
                panel.querySelectorAll('.help-tab-content').forEach(c => c.classList.remove('active'));
                
                tabBtn.classList.add('active');
                const targetTab = tabBtn.dataset.tab === 'actions' ? 'helpTabActions' : 'helpTabFaq';
                document.getElementById(targetTab)?.classList.add('active');
            });
        });

        // Clic sur une action
        panel.querySelectorAll('.action-trigger-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const idx = parseInt(btn.dataset.idx, 10);
                const act = pageInfo.actions[idx];
                if (!act) return;

                panel.classList.remove('open');
                helpBtn.classList.remove('active');

                if (act.url) {
                    window.location.href = act.url;
                    return;
                }

                if (act.target) {
                    const el = document.querySelector(act.target);
                    if (el) {
                        if (act.actionType === 'click') {
                            el.click();
                        }
                        el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        el.classList.add('ux-target-highlight');
                        setTimeout(() => el.classList.remove('ux-target-highlight'), 2200);
                    }
                }
            });
        });

        // Ouvrir / Fermer
        helpBtn.addEventListener('click', () => {
            panel.classList.toggle('open');
            helpBtn.classList.toggle('active');
        });

        panel.querySelector('.help-close-btn').addEventListener('click', () => {
            panel.classList.remove('open');
            helpBtn.classList.remove('active');
        });

        // Accordéon FAQ
        panel.querySelectorAll('.faq-question').forEach(btn => {
            btn.addEventListener('click', () => {
                const item = btn.parentElement;
                const wasOpen = item.classList.contains('open');
                panel.querySelectorAll('.faq-item').forEach(i => i.classList.remove('open'));
                if (!wasOpen) item.classList.add('open');
            });
        });

        // Fermer au clic extérieur
        document.addEventListener('click', (e) => {
            if (!panel.contains(e.target) && !helpBtn.contains(e.target)) {
                panel.classList.remove('open');
                helpBtn.classList.remove('active');
            }
        });
    }

    // ── 4. GUIDE INTERACTIF (ONBOARDING TOUR) ─────────────────────────────────
    function createOnboardingTour() {
        // Ne montrer que la première fois (localStorage)
        if (localStorage.getItem('immo_onboarding_done') === '1') return;

        // Ne déclencher QUE sur la page d'accueil (pas admin, pas annonces, etc.)
        const path = window.location.pathname.replace(/\/+$/, '');
        const isHome = (path === '' || path === '/' || /\/(Projet_Affaire|index\.php)?$/.test(path));
        if (!isHome) return;

        // Vérifier que les éléments clés de l'accueil sont bien présents
        if (!document.querySelector('.hero') || !document.querySelector('.search-container-glass')) return;

        const steps = [
            {
                target: '.search-container-glass',
                title: '🔍 Cherchez facilement',
                text: 'Tapez un pays, une ville ou un quartier ici pour trouver un bien. Les suggestions apparaissent automatiquement !',
                position: 'bottom'
            },
            {
                target: '.smart-filter-wrap',
                title: '🏷️ Filtrez par catégorie',
                text: 'Cliquez sur « Vente » ou « Location », puis sur « Terrain », « Maison » ou « Engins » pour affiner vos résultats.',
                position: 'top'
            },
            {
                target: '.annonce-card-pro',
                title: '🏠 Consultez les annonces',
                text: 'Chaque carte affiche le prix, la photo et la localisation. Cliquez sur « Détails » pour en savoir plus, ou contactez directement le vendeur via WhatsApp !',
                position: 'top'
            },
            {
                target: '.nav-links',
                title: '📋 Naviguez facilement',
                text: '« Annonces » pour voir tout, « Carte » pour voir sur le plan, « Contact » pour nous écrire. Pas besoin de compte !',
                position: 'bottom'
            }
        ];

        // Créer l'overlay
        const overlay = document.createElement('div');
        overlay.id = 'onboardingOverlay';
        overlay.innerHTML = `
            <div class="onboard-spotlight"></div>
            <div class="onboard-tooltip">
                <div class="onboard-tooltip-arrow"></div>
                <h4 class="onboard-title"></h4>
                <p class="onboard-text"></p>
                <div class="onboard-actions">
                    <span class="onboard-counter"></span>
                    <div class="onboard-btns">
                        <button class="onboard-skip">Passer</button>
                        <button class="onboard-next">Suivant <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);

        const spotlight = overlay.querySelector('.onboard-spotlight');
        const tooltip = overlay.querySelector('.onboard-tooltip');
        const titleEl = overlay.querySelector('.onboard-title');
        const textEl = overlay.querySelector('.onboard-text');
        const counterEl = overlay.querySelector('.onboard-counter');
        const nextBtn = overlay.querySelector('.onboard-next');
        const skipBtn = overlay.querySelector('.onboard-skip');

        let currentStep = 0;

        function positionStep(step, el) {
            // L'overlay est position:fixed → utiliser uniquement les coords viewport
            const rect = el.getBoundingClientRect();
            const MARGIN = 10;

            // --- SPOTLIGHT (position absolue dans la page, l'overlay est fixed donc il couvre tout)
            // Le spotlight doit être fixé visuellement sur l'élément → on utilise position fixed aussi
            spotlight.style.position = 'fixed';
            spotlight.style.top    = (rect.top    - 8)  + 'px';
            spotlight.style.left   = (rect.left   - 8)  + 'px';
            spotlight.style.width  = (rect.width  + 16) + 'px';
            spotlight.style.height = (rect.height + 16) + 'px';

            // --- CONTENU DU TOOLTIP
            titleEl.textContent = step.title;
            textEl.textContent  = step.text;
            counterEl.textContent = `${currentStep + 1} / ${steps.length}`;

            if (currentStep === steps.length - 1) {
                nextBtn.innerHTML = 'Terminer <i class="fas fa-check"></i>';
            } else {
                nextBtn.innerHTML = 'Suivant <i class="fas fa-arrow-right"></i>';
            }

            // --- POSITION DU TOOLTIP (viewport-relative, aussi fixed)
            tooltip.style.position = 'fixed';
            const ttW = 350;
            const ttH = tooltip.offsetHeight || 180;
            const vW  = window.innerWidth;
            const vH  = window.innerHeight;

            // Gauche : centré sur l'élément, mais jamais hors écran
            let left = rect.left + rect.width / 2 - ttW / 2;
            left = Math.max(MARGIN, Math.min(left, vW - ttW - MARGIN));
            tooltip.style.left = left + 'px';

            // Haut / Bas : préférence step.position, avec fallback si trop près du bord
            let top;
            if (step.position === 'bottom') {
                top = rect.bottom + 16;
                if (top + ttH > vH - MARGIN) {
                    // Pas assez de place en bas → afficher au-dessus
                    top = rect.top - ttH - 16;
                }
            } else {
                top = rect.top - ttH - 16;
                if (top < MARGIN) {
                    // Pas assez de place en haut → afficher en bas
                    top = rect.bottom + 16;
                }
            }
            // Sécurité absolue : jamais hors viewport
            top = Math.max(MARGIN, Math.min(top, vH - ttH - MARGIN));
            tooltip.style.top = top + 'px';

            overlay.classList.add('active');
        }

        function showStep(index) {
            const step = steps[index];
            const el = document.querySelector(step.target);

            if (!el) {
                // Passer à la prochaine étape si l'élément n'existe pas
                if (index < steps.length - 1) {
                    showStep(index + 1);
                } else {
                    finishTour();
                }
                return;
            }

            // Cacher l'overlay pendant le scroll pour éviter le glissement visuel
            overlay.classList.remove('active');

            // Scroll vers l'élément
            el.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Attendre que le scroll se termine PUIS positionner
            // On attend 600ms (durée réaliste du scroll smooth)
            setTimeout(() => positionStep(step, el), 650);
        }

        function finishTour() {
            overlay.classList.remove('active');
            setTimeout(() => overlay.remove(), 400);
            localStorage.setItem('immo_onboarding_done', '1');
        }

        nextBtn.addEventListener('click', () => {
            currentStep++;
            if (currentStep >= steps.length) {
                finishTour();
            } else {
                showStep(currentStep);
            }
        });

        skipBtn.addEventListener('click', finishTour);

        // Démarrer après un court délai
        setTimeout(() => showStep(0), 1500);
    }

    // ── 5. TOOLTIPS SUR LES CHAMPS D'INSCRIPTION ──────────────────────────────
    function addFormHints() {
        const hints = {
            'input[name="username"]': '💡 Choisissez un pseudo unique (ex: jean2026)',
            'input[name="password"]': '💡 Minimum 4 caractères. Utilisez l\'icône œil pour vérifier.',
            'input[name="email"]': '💡 Votre email pour récupérer votre compte.',
            'input[name="phone"]': '💡 Numéro avec indicatif pays (ex: +225 07...)',
            'input[name="country"]': '💡 Tapez les premières lettres, les suggestions apparaissent !',
        };

        const registerForm = document.getElementById('tab-register');
        if (!registerForm) return;

        Object.entries(hints).forEach(([selector, hintText]) => {
            const inputs = registerForm.querySelectorAll(selector);
            inputs.forEach(input => {
                if (input.closest('.form-group')?.querySelector('.field-hint')) return;

                const hint = document.createElement('small');
                hint.className = 'field-hint';
                hint.textContent = hintText;
                input.closest('.form-group')?.appendChild(hint);
            });
        });
    }

    // ── 6. INITIALISATION ──────────────────────────────────────────────────────
    function initUXHelpers() {
        createScrollTopButton();
        addPasswordToggles();
        createHelpButton();
        createOnboardingTour();
        addFormHints();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initUXHelpers);
    } else {
        initUXHelpers();
    }
})();
