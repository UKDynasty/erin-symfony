<?php
namespace App\Command;

use App\Entity\Draft;
use App\Entity\DraftPick;
use App\Entity\Franchise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateDraft extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(?string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);

        $this->em = $em;
    }

    public function configure()
    {
        $this
            ->setName("app:createdraft")
            ->setDescription("Create a draft and picks for each franchise")
            ->addArgument("year", InputArgument::REQUIRED, "The calendar year in which the draft will occur")
            ->addArgument("rounds", InputArgument::OPTIONAL, "The number of rounds in the draft", 4)
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument("year");
        $rounds = $input->getArgument("rounds");

        $franchises = $this->em->getRepository(Franchise::class)->findAll();

        $draft = new Draft();
        $draft->setYear($year);
        $this->em->persist($draft);

        for ($i = 1; $i <= $rounds; $i++) {
            foreach($franchises as $franchise) {
                $pick = new DraftPick();
                $pick->setDraft($draft);
                $pick->setOwner($franchise);
                $pick->setOriginalOwner($franchise);
                $pick->setRound($i);
                $this->em->persist($pick);
            }
        }

        $this->em->flush();
    }
}