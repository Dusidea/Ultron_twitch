<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


#[AsCommand(
    name: 'twitch:collect-viewers',
    description: 'Appelle l\'API Twitch et écrit dans un fichier CSV',
)]
class TwitchCollectCommand extends Command
{
    private HttpClientInterface $client;
    private string $clientId;
    private string $accessToken;

    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        parent::__construct();

        $this->client = $client;
        $this->clientId = $params->get('TWITCH_CLIENT_ID');
        $this->accessToken = $params->get('TWITCH_ACCESS_TOKEN');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $gameId = '512998'; // TOTK
        $url = 'https://api.twitch.tv/helix/streams?game_id=' . $gameId . '&language=fr&first=100';
        // where parameters are 
            // gameID (allows to find the right category), 
            // language: filters streams in french, 
            // first=100 makes sure we get up to 100 results to avoid pagination every 20 results
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

        // Writing in the CSV output file
        $file = fopen('data/streams.csv', 'a');
        foreach ($rows as $row) {
            fputcsv($file, $row);
        }
        fclose($file);

        $output->writeln('✅ Données enregistrées à ' . $timestamp);

        return Command::SUCCESS;
    }
}
