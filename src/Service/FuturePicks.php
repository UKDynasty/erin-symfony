<?php
namespace App\Service;

use App\Entity\DraftPick;
use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;

class FuturePicks
{
    private const SHEET_JSON_URL = 'https://spreadsheets.google.com/feeds/list/1tsYQSMBHSD3nFUQS6urrnqYPJQs94c-IdjJi1CznX1c/3/public/values?alt=json';

    private $rows;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $file= file_get_contents(static::SHEET_JSON_URL);
        $json = json_decode($file, true);
        $this->rows = $json["feed"]["entry"];
    }

    public function updatePicks()
    {
        $pickRepository = $this->entityManager->getRepository(DraftPick::class);
        $franchiseRepository = $this->entityManager->getRepository(Franchise::class);
        $franchises = $franchiseRepository->findAll();
        $ret = [];
        foreach($franchises as $franchise) {
            $ret[$franchise->getId()] = [];
            $ret[$franchise->getId()]['picks'] = [];
            $slugname = str_replace(' ', '', $franchise->getName());
            $cellRef = strtolower(sprintf('gsx$%s', $slugname));
            foreach($this->rows as $row) {
                if (mb_strlen($row[$cellRef]['$t']) > 0) {
                    $parsedPickText = $this->parsePickText($row[$cellRef]['$t']);
                    $originalOwner = $franchiseRepository->findOneBy(['name' => $parsedPickText['franchiseName']]);
                    $pick = $pickRepository->getPickByYearRoundOriginalOwnerFranchise($parsedPickText['year'], $parsedPickText['round'], $originalOwner);
                    $pick->setOwner($franchise);
                }
            }
        }
        $this->entityManager->flush();
    }

    private function parsePickText(string $text)
    {
        // Year is first four characters
        $ret['year'] = (int)mb_substr($text, 0, 4);
        // Pick round is 6th character
        $ret['round'] = (int)$text[5];
        // Owning franchise is last two words
        $exploded = explode(' ', $text);
        $size = \count($exploded);
        $ret['franchiseName'] = sprintf('%s %s', $exploded[$size-2], $exploded[$size-1]);

        return $ret;
    }
}