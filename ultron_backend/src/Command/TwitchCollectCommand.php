<?php

namespace App\Command;

use App\Service\TwitchCollector;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'twitch:collect-viewers',
    description: 'Collecte les données Twitch pour plusieurs catégories',
)]
class TwitchCollectCommand extends Command
{
    private TwitchCollector $collector;

    // Categories list, 1 game => 1 folder, several categories accepted for 1 game
    // private array $categories = [
    //     'TOTK' => ['512998', '1981388235'],   
    //     'Skyrim' => ['30028 ', '1050003477', '1258270417'],   
    //     'Isaac' => ['32207', '94073', '201557326', '436344698','906830759', '1414860634'],
    // ];

    public function __construct(TwitchCollector $collector, array $categories)
    {
        parent::__construct();
        $this->collector = $collector;
        $this->categories = $categories;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

      foreach ($this->categories as $categoryName => $gameIds) {
        try {
            $this->collector->collect($gameIds, $categoryName);
            $io->success("✅ Données enregistrées pour $categoryName");
        } catch (\Throwable $e) {
            $io->error("❌ Erreur sur $categoryName : " . $e->getMessage());
        }
    }

        return Command::SUCCESS;
    }
}
