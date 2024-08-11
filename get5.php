<?php
$apiKey = 'AIzaSyAIxCUKY2aE7yaZE1_aVXIRQuKFH6y9QmI';
$channelId = 'UCsM82TfSVs6YDQWNhDz5gYA';  // Channel ID

// URL, um den aktuellen Livestream des Kanals abzurufen
$url = "https://www.googleapis.com/youtube/v3/search?part=snippet&channelId=$channelId&eventType=live&type=video&key=$apiKey";

// API-Aufruf
$response = @file_get_contents($url);
if ($response === FALSE) {
    echo json_encode(['error' => 'Fehler beim Abrufen der YouTube-Daten.']);
    exit;
}

$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['error' => 'Fehler beim Dekodieren der JSON-Antwort.']);
    exit;
}

// Prüfen, ob ein Live-Stream gefunden wurde
if (!empty($data['items'])) {
    $liveChatId = $data['items'][0]['id']['videoId'];
    
    // URL, um die LiveChat ID zu erhalten
    $liveChatUrl = "https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id=$liveChatId&key=$apiKey";
    $liveChatResponse = @file_get_contents($liveChatUrl);
    if ($liveChatResponse === FALSE) {
        echo json_encode(['error' => 'Fehler beim Abrufen der Video-Daten.']);
        exit;
    }

    $liveChatData = json_decode($liveChatResponse, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Fehler beim Dekodieren der JSON-Antwort für Video-Daten.']);
        exit;
    }

    if (!empty($liveChatData['items'][0]['liveStreamingDetails']['activeLiveChatId'])) {
        $chatId = $liveChatData['items'][0]['liveStreamingDetails']['activeLiveChatId'];
        
        // Chat-Nachrichten abrufen
        $chatUrl = "https://www.googleapis.com/youtube/v3/liveChat/messages?liveChatId=$chatId&part=snippet,authorDetails&key=$apiKey";
        $chatResponse = @file_get_contents($chatUrl);
        if ($chatResponse === FALSE) {
            echo json_encode(['error' => 'Fehler beim Abrufen der Chat-Nachrichten.']);
            exit;
        }

        $chatMessages = json_decode($chatResponse, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['error' => 'Fehler beim Dekodieren der JSON-Antwort für Chat-Nachrichten.']);
            exit;
        }
        
        // Nachrichten in einem Array speichern
        $messages = [];
        if (isset($chatMessages['items'])) {
            foreach ($chatMessages['items'] as $item) {
                $messages[] = [
                    "username" => $item['authorDetails']['displayName'],
                    "message" => $item['snippet']['displayMessage']
                ];
            }
        }

        // Rückgabe als JSON
        header('Content-Type: application/json');
        echo json_encode($messages);
    } else {
        echo json_encode(['error' => 'Live Chat ID nicht gefunden']);
    }
} else {
    echo json_encode(['error' => 'Kein aktiver Livestream gefunden']);
}
?>
