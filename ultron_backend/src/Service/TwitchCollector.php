<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class TwitchCollector
{
    private HttpClientInterface $client;
    private string $clientId;
    private string $accessToken;
    private string $outputDir;

    public function __construct(HttpClientInterface $client, string $clientId, string $accessToken, string $outputDir = 'data')
    {
        $this->client = $client;
        $this->clientId = $clientId;
        $this->accessToken = $accessToken;
        $this->outputDir = $outputDir;
    }

    public function collect(string $gameId, string $categoryName): void
    {
        $url = 'https://api.twitch.tv/helix/streams?game_id=' . $gameId . '&language=fr&first=100';

        $response = $this->client->request('GET', $url, [
            'headers' => [
                'Client-ID' => $this->clientId,
                'Authorization' => 'Bearer ' . $this->accessToken,
            ],
        ]);

        $data = $response->toArray();

        $timestamp = date('Y-m-d H:i:s');
        $rows = [];
        $rank = 1;

        foreach ($data['data'] as $stream) {
            $rows[] = [
                $timestamp,
                $stream['user_name'],
                $stream['viewer_count'],
                $stream['title'],
                $stream['started_at'],
                $rank,
            ];
            $rank++;
        }

        // Sauvegarde CSV dans un dossier par catégorie
        $dir = $this->outputDir . '/' . $categoryName;
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $file = fopen($dir . '/streams.csv', 'a');
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }
}
