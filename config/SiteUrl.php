<?php
/**
 * SiteUrl helper — construit les URLs publiques du site.
 *
 * Priorité :
 *   1) Le paramètre "site_url" dans la table settings (ex: https://monsite.ci)
 *   2) Fallback automatique vers HTTP_HOST (localhost en dev)
 *
 * Usage :
 *   $url = SiteUrl::to('/annonce/' . $id);           // lien vers une page
 *   $img = SiteUrl::to($annonce['image_path']);       // URL absolue d'une image
 */

class SiteUrl {
    private static ?string $base = null;

    /** Retourne la base URL publique du site (sans slash de fin). */
    public static function base(): string {
        if (self::$base !== null) {
            return self::$base;
        }

        // 1) Essayer de charger depuis les paramètres (BDD)
        try {
            require_once __DIR__ . '/../models/SettingsModel.php';
            $settings = (new SettingsModel())->getAll();
            $siteUrl  = $settings['site_url'] ?? '';
            if (!empty($siteUrl)) {
                self::$base = rtrim($siteUrl, '/');
                return self::$base;
            }
        } catch (\Exception $e) {
            // Silencieux — on tombe dans le fallback
        }

        // 2) Fallback : construction automatique
        $proto = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
        $host  = $_SERVER['HTTP_HOST'] ?? 'localhost';
        self::$base = $proto . '://' . $host . (defined('BASE_URL') ? BASE_URL : '');

        return self::$base;
    }

    /** Retourne une URL absolue publique vers un chemin relatif. */
    public static function to(string $path): string {
        return self::base() . '/' . ltrim($path, '/');
    }

    /** URL vers la fiche d'une annonce. */
    public static function annonce(int $id): string {
        return self::to('annonce/' . $id);
    }

    /** URL absolue vers un fichier media (image/vidéo). */
    public static function media(?string $path): string {
        if (empty($path)) {
            return self::to('assets/images/placeholder.jpg');
        }
        return self::to(ltrim($path, '/'));
    }
}
