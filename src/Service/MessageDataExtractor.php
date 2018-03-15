<?php

namespace App\Service;


use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;
use NlpTools\Tokenizers\WhitespaceTokenizer;

class MessageDataExtractor
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {

        $this->em = $em;
    }

    public function extractFranchise($message): ?Franchise
    {
        $franchises = $this->em->getRepository(Franchise::class)->findAll();

        $tokenizer = new WhitespaceTokenizer();
        $tokenizedMessage = $tokenizer->tokenize($message);
        $tokenizedMessage = array_map(function($el) {
            return mb_strtolower($el);
        }, $tokenizedMessage);

        /** @var Franchise $franchise */
        foreach($franchises as $franchise) {
            $overlap = array_intersect($franchise->getIdentifiers(), $tokenizedMessage);
            if (count($overlap) > 0) {
                return $franchise;
            }
        }

        return null;
    }

    public function extractFranchiseName($message): ?string
    {
        $franchise = $this->extractFranchise($message);
        if (!$franchise) {
            return null;
        }
        return $franchise->getName();
    }

}