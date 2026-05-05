<?php
// cron/publish.php

require_once __DIR__ . '/../models/PublicationModel.php';

$pubModel = new PublicationModel();
$pending = $pubModel->getPending();

foreach ($pending as $pub) {
    echo "Processing publication ID: " . $pub['id'] . " on platform: " . $pub['platform'] . "\n";
    
    $success = false;
    $error = null;

    try {
        switch ($pub['platform']) {
            case 'facebook':
                $success = postToFacebook($pub['generated_text']);
                break;
            case 'whatsapp':
                $success = postToWhatsApp($pub['generated_text'], $pub['whatsapp_contact']);
                break;
            default:
                $error = "Platform not supported yet.";
        }

        if ($success) {
            $pubModel->updateStatus($pub['id'], 'published');
            echo "Successfully published!\n";
        } else {
            $pubModel->updateStatus($pub['id'], 'failed', $error ?? 'Unknown error');
            echo "Failed to publish.\n";
        }
    } catch (Exception $e) {
        $pubModel->updateStatus($pub['id'], 'failed', $e->getMessage());
        echo "Error: " . $e->getMessage() . "\n";
    }
}

// Dummy functions for demonstration
function postToFacebook($text) {
    // Integration with Meta Graph API
    // Need ACCESS_TOKEN and PAGE_ID
    return true; 
}

function postToWhatsApp($text, $contact) {
    // Integration with WhatsApp Business API
    return true;
}
