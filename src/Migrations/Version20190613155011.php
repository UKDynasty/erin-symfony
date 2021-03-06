<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\FranchiseSnapshotRosterPlayer;
use App\Entity\PlayerValueSnapshot;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190613155011 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player_value_snapshot (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, value INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_ADCD41F199E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player_value_snapshot ADD CONSTRAINT FK_ADCD41F199E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function postUp(Schema $schema)
    {
        parent::postUp($schema); // TODO: Change the autogenerated stub
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
        $rosterPlayerSnapshot = $em->getRepository(FranchiseSnapshotRosterPlayer::class)->findAll();
        foreach($rosterPlayerSnapshot as $rosterPlayer) {
            $playerValueSnapshot = new PlayerValueSnapshot();
            $playerValueSnapshot->setPlayer($rosterPlayer->getPlayer());
            $playerValueSnapshot->setValue($rosterPlayer->getValue());
            $playerValueSnapshot->setDate($rosterPlayer->getSnapshot()->getDate());
            $em->persist($playerValueSnapshot);
        }
        $em->flush();
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE player_value_snapshot');
    }
}
