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
use Symfony\Component\Console\Question\Question;

class SetDraftOrder extends Command
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
            ->setName("app:setdraftorder")
            ->setDescription("Set the draft order for a draft")
            ->addArgument("year", InputArgument::REQUIRED, "The calendar year in which the draft will occur")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        $year = $input->getArgument("year");
        $franchises = $this->em->getRepository(Franchise::class)->findAll();
        $draft = $this->em->getRepository(Draft::class)->findOneBy([
            "year" => $year,
        ]);

        /** @var Franchise $franchise */
        foreach($franchises as $franchise) {

            $picks = $this->em->getRepository(DraftPick::class)->findBy([
                "originalOwner" => $franchise,
                "draft" => $draft,
            ]);

            $question = new Question(sprintf("What position do the %s have in this draft?", $franchise->getName()));

            $position = $helper->ask($input, $output, $question);

            /** @var DraftPick $pick */
            foreach($picks as $pick) {
                $pick->setNumber($position);
                $pick->setOverall(($pick->getRound()-1)*12+$position);
            }
        }

        $this->em->flush();
    }
}