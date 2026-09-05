<?php include __DIR__ . '/../layout_header.php'; ?>

<section class="demandes-section">
    <div class="container">
        
        <!-- Header Page -->
        <div class="demandes-header-box">
            <div class="demandes-header-content">
                <h1>Demandes de <span class="highlight">Biens Immobilier & Véhicules</span></h1>
                <p>Les acheteurs et locataires expriment leurs besoins ici. Les agents et propriétaires leur proposent directement les meilleures offres sur WhatsApp.</p>
            </div>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="#form-demande" class="btn-create-demande"><i class="fas fa-plus-circle"></i> Publier ma demande</a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>/login" class="btn-create-demande"><i class="fas fa-user-plus"></i> S'inscrire pour faire une demande</a>
            <?php endif; ?>
        </div>

        <?php if (!empty($success)): ?>
            <?php if ($success === 'created'): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> Votre demande a été publiée avec succès ! Les agents vous contacteront bientôt sur WhatsApp.</div>
            <?php elseif ($success === 'closed'): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle"></i> Votre demande a été retirée avec succès.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- SECTION MES DEMANDES (Si connecté) -->
        <?php if (!empty($_SESSION['user_id']) && !empty($mesDemandes)): ?>
            <div class="my-demandes-container">
                <h3 class="section-sub-title"><i class="fas fa-folder-open"></i> Mes demandes publiées (<?php echo count($mesDemandes); ?>)</h3>
                <div class="my-demandes-grid">
                    <?php foreach ($mesDemandes as $md): ?>
                        <div class="my-demande-card <?php echo $md['status'] === 'closed' ? 'is-closed' : ''; ?>">
                            <div class="my-demande-badge">
                                <span class="tag-cat"><?php echo htmlspecialchars(ucfirst($md['category'] ?? 'Général')); ?></span>
                                <span class="tag-type <?php echo $md['type']; ?>"><?php echo strtoupper($md['type']); ?></span>
                                <span class="status-badge <?php echo $md['status']; ?>"><?php echo $md['status'] === 'active' ? 'En cours' : 'Satisfaite / Retirée'; ?></span>
                            </div>
                            <h4><?php echo htmlspecialchars($md['description']); ?></h4>
                            <div class="my-demande-details">
                                <?php if (!empty($md['budget_max'])): ?>
                                    <span><i class="fas fa-wallet"></i> Budget: <strong><?php echo number_format($md['budget_max'], 0, ',', ' '); ?> FCFA</strong></span>
                                <?php endif; ?>
                                <?php if (!empty($md['ville']) || !empty($md['commune'])): ?>
                                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars(implode(', ', array_filter([$md['commune'], $md['ville'], $md['pays']]))); ?></span>
                                <?php endif; ?>
                                <span><i class="far fa-clock"></i> <?php echo date('d/m/Y à H:i', strtotime($md['created_at'])); ?></span>
                            </div>
                            <?php if ($md['status'] === 'active'): ?>
                                <form action="<?php echo BASE_URL; ?>/demandes/close" method="POST" onsubmit="return confirm('Avez-vous trouvé votre bien ? Votre demande sera retirée de la liste.');" style="margin-top:12px;">
                                    <input type="hidden" name="demande_id" value="<?php echo $md['id']; ?>">
                                    <button type="submit" class="btn-close-demande"><i class="fas fa-check-double"></i> Retirer ma demande (Bien trouvé)</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- SECTION FORMULAIRE OU INVITE DE CONNEXION -->
        <div class="demande-form-wrapper" id="form-demande">
            <?php if (empty($_SESSION['user_id'])): ?>
                <!-- Avertissement Visiteur Non Connecté -->
                <div class="auth-prompt-card">
                    <div class="prompt-icon"><i class="fas fa-lock"></i></div>
                    <h3>Vous cherchez un bien spécifique (Terrain, Maison, Véhicule, Moto...) ?</h3>
                    <p>Pour publier votre demande afin que nos agents et propriétaires vous fassent des propositions directement sur WhatsApp, vous devez d'abord créer un compte ou vous connecter.</p>
                    <div class="prompt-buttons">
                        <a href="<?php echo BASE_URL; ?>/login" class="btn-prompt-primary"><i class="fas fa-user-plus"></i> Créer un compte / S'inscrire</a>
                        <a href="<?php echo BASE_URL; ?>/login" class="btn-prompt-secondary"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Formulaire de création de demande pour utilisateur connecté -->
                <div class="demande-form-card">
                    <h2><i class="fas fa-paper-plane"></i> Publier une nouvelle demande</h2>
                    <p class="form-subtext">Décrivez précisément ce que vous cherchez. Les agents de la plateforme consulteront votre besoin et vous écriront directement par WhatsApp.</p>
                    
                    <form action="<?php echo BASE_URL; ?>/demandes/save" method="POST">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label><i class="fas fa-th-large"></i> Catégorie du bien</label>
                                <select name="category" required class="form-control-pro">
                                    <option value="terrain">Terrain</option>
                                    <option value="maison">Maison / Villa / Appartement</option>
                                    <option value="studio">Studio / Magasin</option>
                                    <option value="vehicule">Véhicule (Voiture, Camion)</option>
                                    <option value="moto">Moto / Engin</option>
                                    <option value="autre">Autre matériel</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-tag"></i> Type d'opération</label>
                                <select name="type" required class="form-control-pro">
                                    <option value="achat">Achat / Payer</option>
                                    <option value="location">Location / Louer</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label><i class="fas fa-wallet"></i> Budget maximum (FCFA)</label>
                                <input type="number" name="budget_max" placeholder="Ex: 15 000 000 ou 150 000 (mensuel)" class="form-control-pro">
                            </div>
                            <div class="form-group">
                                <label><i class="fab fa-whatsapp"></i> Téléphone / WhatsApp de contact *</label>
                                <input type="text" name="client_phone" required placeholder="Ex: +225 07 00 00 00 00" value="<?php echo htmlspecialchars($_SESSION['phone_tel'] ?? ''); ?>" class="form-control-pro">
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label><i class="fas fa-globe-africa"></i> Pays désiré</label>
                                <input type="text" name="pays" id="demandePays" list="list-pays" placeholder="Ex: Côte d'Ivoire" class="form-control-pro">
                                <datalist id="list-pays"></datalist>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-city"></i> Ville désiré</label>
                                <input type="text" name="ville" id="demandeVille" list="list-villes" placeholder="Ex: Abidjan" class="form-control-pro">
                                <datalist id="list-villes"></datalist>
                            </div>
                        </div>

                        <div class="form-row-2">
                            <div class="form-group">
                                <label><i class="fas fa-map"></i> Commune désiré</label>
                                <input type="text" name="commune" id="demandeCommune" list="list-communes" placeholder="Ex: Cocody, Yopougon..." class="form-control-pro">
                                <datalist id="list-communes"></datalist>
                            </div>
                            <div class="form-group">
                                <label><i class="fas fa-map-pin"></i> Quartier souhaité</label>
                                <input type="text" name="quartier" id="demandeQuartier" list="list-quartiers" placeholder="Ex: Angré 8ème tranche..." class="form-control-pro">
                                <datalist id="list-quartiers"></datalist>
                            </div>
                        </div>

                        <div class="form-group">
                            <label><i class="fas fa-pencil-alt"></i> Description détaillée de votre besoin *</label>
                            <textarea name="description" rows="4" required placeholder="Décrivez en détail ce que vous recherchez. Exemple: Je cherche un terrain de 500m² avec ACD approuvé à Cocody Angré ou une maison duplex 4 pièces à louer à Riviera 3..." class="form-control-pro"></textarea>
                        </div>

                        <button type="submit" class="btn-submit-demande"><i class="fas fa-paper-plane"></i> Publier ma demande d'achat / location</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>

        <!-- LISTE DE TOUTES LES DEMANDES ACTIVES -->
        <div class="demandes-list-wrapper">
            <div class="list-section-header">
                <h2><i class="fas fa-list-ul"></i> Demandes récentes des clients (<span class="count-badge"><?php echo count($demandes); ?></span>)</h2>
                <p>Agents & Propriétaires : consultez les besoins ci-dessous et proposez vos biens directement sur WhatsApp aux demandeurs !</p>
            </div>

            <?php if (!empty($demandes)): ?>
                <div class="demandes-grid">
                    <?php foreach ($demandes as $d): ?>
                        <div class="demande-card-public">
                            <div class="card-head-public">
                                <div class="user-info">
                                    <div class="avatar-circle">
                                        <?php echo strtoupper(substr($d['client_name'] ?? 'C', 0, 1)); ?>
                                    </div>
                                    <div>
                                        <strong class="user-name"><?php echo htmlspecialchars($d['client_name']); ?></strong>
                                        <span class="post-date"><i class="far fa-clock"></i> <?php echo date('d/m/Y', strtotime($d['created_at'])); ?></span>
                                    </div>
                                </div>
                                <div class="badges-row">
                                    <span class="badge-cat"><?php echo htmlspecialchars(ucfirst($d['category'] ?? 'Bien')); ?></span>
                                    <span class="badge-type <?php echo $d['type']; ?>"><?php echo strtoupper($d['type']); ?></span>
                                </div>
                            </div>

                            <div class="card-body-public">
                                <p class="demand-text"><?php echo nl2br(htmlspecialchars($d['description'])); ?></p>
                                
                                <div class="demand-specs">
                                    <?php if (!empty($d['budget_max'])): ?>
                                        <div class="spec-item">
                                            <i class="fas fa-wallet"></i> Budget Max: <strong><?php echo number_format($d['budget_max'], 0, ',', ' '); ?> FCFA</strong>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $locParts = array_filter([$d['quartier'], $d['commune'], $d['ville'], $d['pays']]);
                                    if (!empty($locParts)): 
                                    ?>
                                        <div class="spec-item">
                                            <i class="fas fa-map-marker-alt"></i> Zone: <strong><?php echo htmlspecialchars(implode(', ', $locParts)); ?></strong>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="card-footer-public">
                                <?php
                                $waPhone = preg_replace('/[^0-9]/', '', $d['client_phone']);
                                $waMsg   = "Bonjour " . $d['client_name'] . ",\n\n"
                                         . "J'ai vu votre demande sur ImmoAffaire concernant : *" . ($d['category'] ?? 'un bien') . " (" . strtoupper($d['type']) . ")*.\n"
                                         . "J'ai des opportunités correspondant à votre besoin ! Pouvons-nous échanger ?";
                                ?>
                                <a href="https://wa.me/<?php echo $waPhone; ?>?text=<?php echo rawurlencode($waMsg); ?>" target="_blank" class="btn-wa-proposer">
                                    <i class="fab fa-whatsapp"></i> Proposer un bien au client sur WhatsApp
                                </a>

                                <?php 
                                $isOwner = !empty($_SESSION['user_id']) && ((int)$_SESSION['user_id'] === (int)$d['user_id']);
                                $isAdmin = !empty($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'super_admin']);
                                if ($isOwner || $isAdmin): 
                                ?>
                                    <form action="<?php echo BASE_URL; ?>/demandes/close" method="POST" onsubmit="return confirm('Voulez-vous retirer cette demande ?');" style="margin-top:8px;">
                                        <input type="hidden" name="demande_id" value="<?php echo $d['id']; ?>">
                                        <button type="submit" class="btn-delete-demande"><i class="fas fa-trash-alt"></i> Supprimer la demande</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="empty-state-demandes">
                    <i class="fas fa-clipboard-list"></i>
                    <h3>Aucune demande enregistrée pour le moment</h3>
                    <p>Soyez le premier à exprimer ce que vous recherchez !</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<style>
/* ── DEMANDES SECTION STYLES ───────────────────────────────────────── */
.demandes-section {
    padding: 40px 0 80px;
    background: #f8fafc;
    min-height: 80vh;
}

.demandes-header-box {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 24px;
    padding: 40px;
    color: white;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 30px;
    margin-bottom: 40px;
    box-shadow: 0 20px 40px rgba(15, 23, 42, 0.15);
}
.demandes-header-content h1 {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 10px;
}
.demandes-header-content p {
    color: #94a3b8;
    max-width: 600px;
    font-size: 0.98rem;
    line-height: 1.6;
}
.btn-create-demande {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #0f172a;
    padding: 16px 28px;
    border-radius: 16px;
    font-weight: 800;
    font-size: 0.95rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    white-space: nowrap;
    transition: all 0.3s ease;
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.3);
}
.btn-create-demande:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(245, 158, 11, 0.45);
    color: #0f172a;
}

/* Auth Prompt Card pour visiteurs non connectés */
.auth-prompt-card {
    background: white;
    border-radius: 20px;
    padding: 45px 30px;
    text-align: center;
    max-width: 700px;
    margin: 0 auto 50px;
    border: 2px dashed #cbd5e1;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
}
.prompt-icon {
    width: 70px;
    height: 70px;
    background: #fef3c7;
    color: #d97706;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    margin: 0 auto 20px;
}
.auth-prompt-card h3 {
    font-size: 1.3rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 12px;
}
.auth-prompt-card p {
    color: #64748b;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 25px;
}
.prompt-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}
.btn-prompt-primary {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white;
    padding: 14px 28px;
    border-radius: 14px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
}
.btn-prompt-secondary {
    background: #f1f5f9;
    color: #334155;
    padding: 14px 28px;
    border-radius: 14px;
    font-weight: 700;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-prompt-primary:hover, .btn-prompt-secondary:hover {
    transform: translateY(-2px);
}

/* Form Card */
.demande-form-card {
    background: white;
    border-radius: 24px;
    padding: 40px;
    margin-bottom: 50px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.05);
    border: 1px solid #e2e8f0;
}
.demande-form-card h2 {
    font-size: 1.4rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.form-subtext {
    color: #64748b;
    font-size: 0.9rem;
    margin-bottom: 25px;
}
.form-control-pro {
    width: 100%;
    padding: 14px 18px;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    font-size: 0.95rem;
    color: #0f172a;
    background: #ffffff;
    font-family: inherit;
}
.form-control-pro:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
}
.btn-submit-demande {
    width: 100%;
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    color: #f59e0b;
    padding: 16px;
    border: none;
    border-radius: 14px;
    font-size: 1rem;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 15px;
    transition: all 0.3s ease;
}
.btn-submit-demande:hover {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    color: #0f172a;
}

/* Mes Demandes */
.my-demandes-container {
    margin-bottom: 40px;
}
.section-sub-title {
    font-size: 1.2rem;
    font-weight: 800;
    color: #0f172a;
    margin-bottom: 15px;
}
.my-demandes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 15px;
}
.my-demande-card {
    background: white;
    padding: 20px;
    border-radius: 16px;
    border: 1.5px solid #e2e8f0;
}
.my-demande-card.is-closed {
    opacity: 0.6;
    background: #f8fafc;
}
.my-demande-badge {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-bottom: 10px;
}
.tag-cat { background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
.tag-type.achat { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
.tag-type.location { background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
.status-badge { font-size: 0.72rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; margin-left: auto; }
.status-badge.active { background: #dcfce7; color: #166534; }
.status-badge.closed { background: #f1f5f9; color: #64748b; }
.my-demande-card h4 { font-size: 0.95rem; color: #0f172a; margin-bottom: 10px; }
.my-demande-details { font-size: 0.82rem; color: #64748b; display: flex; flex-direction: column; gap: 4px; }
.btn-close-demande { background: #fee2e2; color: #991b1b; border: none; padding: 8px 14px; border-radius: 10px; font-size: 0.8rem; font-weight: 700; cursor: pointer; width: 100%; }
.btn-close-demande:hover { background: #fca5a5; }

/* Demandes Publiques Grid */
.list-section-header {
    margin-bottom: 25px;
}
.list-section-header h2 { font-size: 1.4rem; font-weight: 800; color: #0f172a; }
.count-badge { background: #f59e0b; color: #0f172a; padding: 2px 10px; border-radius: 20px; font-size: 1.1rem; }
.list-section-header p { color: #64748b; font-size: 0.92rem; }

.demandes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
    gap: 20px;
}
.demande-card-public {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.04);
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.demande-card-public:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 35px rgba(0,0,0,0.08);
}

.card-head-public {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;

}
.user-info { display: flex; gap: 10px; align-items: center; }
.avatar-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    font-weight: 800;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
}
.user-name { display: block; font-size: 0.95rem; color: #0f172a; }
.post-date { font-size: 0.78rem; color: #94a3b8; }

.badges-row { display: flex; gap: 6px; flex-direction: column; align-items: flex-end; }
.badge-cat { background: #eff6ff; color: #1d4ed8; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
.badge-type.achat { background: #dcfce7; color: #15803d; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }
.badge-type.location { background: #fef3c7; color: #b45309; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; }

.card-body-public { margin-bottom: 20px; flex: 1; }
.demand-text { font-size: 0.95rem; color: #334155; line-height: 1.6; margin-bottom: 15px; }

.demand-specs { background: #f8fafc; padding: 12px 16px; border-radius: 12px; font-size: 0.85rem; display: flex; flex-direction: column; gap: 6px; }
.spec-item { color: #475569; }
.spec-item i { color: #6366f1; margin-right: 6px; width: 16px; }

.btn-wa-proposer {
    background: #25d366;
    color: white;
    text-decoration: none;
    padding: 14px;
    border-radius: 14px;
    font-weight: 700;
    font-size: 0.88rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
    transition: all 0.2s ease;
}
.btn-wa-proposer:hover {
    background: #1eb956;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 12px 25px rgba(37, 211, 102, 0.45);
}
.btn-delete-demande {
    background: none;
    border: none;
    color: #ef4444;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    width: 100%;
    text-align: center;
    padding: 6px;
}
.btn-delete-demande:hover { text-decoration: underline; }

.empty-state-demandes {
    background: white;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    color: #94a3b8;
}
.empty-state-demandes i { font-size: 3rem; margin-bottom: 15px; color: #cbd5e1; }
.empty-state-demandes h3 { font-size: 1.2rem; color: #0f172a; margin-bottom: 6px; }

@media (max-width: 768px) {
    .demandes-section {
        padding: 20px 0 60px;
    }
    .demandes-header-box {
        flex-direction: column;
        text-align: center;
        padding: 25px 20px;
        border-radius: 18px;
        gap: 20px;
    }
    .demandes-header-content h1 {
        font-size: 1.5rem;
    }
    .demandes-header-content p {
        font-size: 0.9rem;
    }
    .btn-create-demande {
        width: 100%;
        justify-content: center;
        padding: 14px 20px;
    }
    .demande-form-card {
        padding: 24px 18px;
        border-radius: 18px;
    }
    .form-row-2 {
        grid-template-columns: 1fr !important;
        gap: 0;
    }
    .auth-prompt-card {
        padding: 30px 18px;
        border-radius: 18px;
    }
    .auth-prompt-card h3 {
        font-size: 1.15rem;
    }
    .prompt-buttons {
        flex-direction: column;
        width: 100%;
    }
    .btn-prompt-primary, .btn-prompt-secondary {
        width: 100%;
        justify-content: center;
    }
    .demandes-grid {
        grid-template-columns: 1fr;
    }
    .my-demandes-grid {
        grid-template-columns: 1fr;
    }
    .demande-card-public {
        padding: 18px;
        border-radius: 16px;
    }
    .card-head-public {
        flex-direction: row;
        align-items: flex-start;
    }
    .badges-row {
        flex-direction: row;
        flex-wrap: wrap;
        align-items: flex-start;
        justify-content: flex-end;
    }
    .btn-wa-proposer {
        padding: 12px;
        font-size: 0.85rem;
    }
}

@media (max-width: 480px) {
    .demandes-header-content h1 {
        font-size: 1.35rem;
    }
    .card-head-public {
        flex-direction: column;
        gap: 10px;
    }
    .badges-row {
        justify-content: flex-start;
        align-self: flex-start;
    }
}
</style>

<?php include __DIR__ . '/../layout_footer.php'; ?>
