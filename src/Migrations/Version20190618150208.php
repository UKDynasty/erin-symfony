<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Player;
use App\Entity\Team;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618150208 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player ADD team_entity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65362063BA FOREIGN KEY (team_entity_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_98197A65362063BA ON player (team_entity_id)');
    }

    public function postUp(Schema $schema)
    {
        /** @var EntityManagerInterface $em */
        $em = $this->container->get('doctrine.orm.entity_manager');

        $stmt = $em->createQuery('SELECT DISTINCT(player.team) FROM App\\Entity\\Player player');
        $stmt->execute();
        $teamAbbr = array_column($stmt->getResult(), 1);
        foreach($teamAbbr as $abbr) {
            if ($abbr === 'FA') {
                continue;
            }
            $team = new Team();
            $team->setAbbreviation($abbr);
            $team->setName($abbr);
            $em->persist($team);
        }
        $em->flush();

        foreach($em->getRepository(Player::class)->findAll() as $player) {
            if ($player->getTeam() === null) {
                continue;
            }
            $team = $em->getRepository(Team::class)->findOneBy(['abbreviation' => $player->getTeam()]);
            $player->setTeamEntity($team);
        }

        $em->flush();

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65362063BA');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP INDEX IDX_98197A65362063BA ON player');
        $this->addSql('ALTER TABLE player DROP team_entity_id');
    }
}
