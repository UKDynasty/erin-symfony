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

class CompleteDraft extends Command
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
            ->setName("app:completedraft")
            ->setDescription("End the draft (set it as complete so that it's no longer the 'current' draft)")
            ->addArgument("year", InputArgument::REQUIRED, "The calendar year in which the draft occurred")
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument("year");
        $draft = $this->em->getRepository(Draft::class)->findOneBy([
            'year' => $year,
        ]);

        if ($draft) {
            $draft->setComplete(true);
        }

        $this->em->flush();
    }
}
