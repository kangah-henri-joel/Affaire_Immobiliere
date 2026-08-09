<?php
// controllers/AIController.php

require_once __DIR__ . '/Controller.php';

class AIController extends Controller
{
    public function generate()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            $this->json(['error' => 'Method not allowed']);
        }

        $description = $_POST['description'] ?? '';

        // Call the local Python API
        $apiUrl = 'http://localhost:8000/generate-ads';

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['text' => $description]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode === 200) {
            echo $response;
        } else {
            // Fallback plus compact (moins de 10 lignes / mots limités)
            $compactDesc = trim(preg_replace('/\s+/', ' ', $description));
            // Limiter à environ 30 mots ou 200 caractères
            if (mb_strlen($compactDesc) > 200) {
                $compactDesc = mb_substr($compactDesc, 0, 197) . '...';
            }
            $hashtags = "#immobilierCI #Abidjan";
            $generated = "🎯 OPPORTUNITÉ !\n" . $compactDesc . "\n\n📲 WhatsApp : Contactez-nous !\n" . $hashtags;
            $this->json(['generated_text' => $generated, 'status' => 'fallback']);
        }
    }
}
