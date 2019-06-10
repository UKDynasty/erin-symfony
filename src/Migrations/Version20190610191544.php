<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190610191544 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE franchise_snapshot_roster_player (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, snapshot_id INT NOT NULL, value INT NOT NULL, INDEX IDX_5676756D99E6F5DF (player_id), INDEX IDX_5676756D7B39395E (snapshot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE franchise_snapshot_best_lineup_player (id INT AUTO_INCREMENT NOT NULL, player_id INT NOT NULL, snapshot_id INT NOT NULL, value INT NOT NULL, INDEX IDX_1AB39FDE99E6F5DF (player_id), INDEX IDX_1AB39FDE7B39395E (snapshot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE franchise_snapshot (id INT AUTO_INCREMENT NOT NULL, franchise_id INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', roster_value INT NOT NULL, roster_value_average DOUBLE PRECISION NOT NULL, roster_count INT NOT NULL, best_lineup_value INT NOT NULL, best_lineup_value_average DOUBLE PRECISION NOT NULL, INDEX IDX_A37DEEC9523CAB89 (franchise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE franchise_snapshot_roster_player ADD CONSTRAINT FK_5676756D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE franchise_snapshot_roster_player ADD CONSTRAINT FK_5676756D7B39395E FOREIGN KEY (snapshot_id) REFERENCES franchise_snapshot (id)');
        $this->addSql('ALTER TABLE franchise_snapshot_best_lineup_player ADD CONSTRAINT FK_1AB39FDE99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE franchise_snapshot_best_lineup_player ADD CONSTRAINT FK_1AB39FDE7B39395E FOREIGN KEY (snapshot_id) REFERENCES franchise_snapshot (id)');
        $this->addSql('ALTER TABLE franchise_snapshot ADD CONSTRAINT FK_A37DEEC9523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE franchise_snapshot_roster_player DROP FOREIGN KEY FK_5676756D7B39395E');
        $this->addSql('ALTER TABLE franchise_snapshot_best_lineup_player DROP FOREIGN KEY FK_1AB39FDE7B39395E');
        $this->addSql('DROP TABLE franchise_snapshot_roster_player');
        $this->addSql('DROP TABLE franchise_snapshot_best_lineup_player');
        $this->addSql('DROP TABLE franchise_snapshot');
    }
}
