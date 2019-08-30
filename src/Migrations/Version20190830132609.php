<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190830132609 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE matchup_player (id INT AUTO_INCREMENT NOT NULL, matchup_franchise_id INT NOT NULL, score NUMERIC(5, 2) DEFAULT NULL, INDEX IDX_FF91DECA5AA47295 (matchup_franchise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matchup_franchise (id INT AUTO_INCREMENT NOT NULL, matchup_id INT NOT NULL, franchise_id INT NOT NULL, score NUMERIC(5, 2) DEFAULT NULL, winner TINYINT(1) DEFAULT NULL, INDEX IDX_9DDAA6EF3965E575 (matchup_id), INDEX IDX_9DDAA6EF523CAB89 (franchise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matchup (id INT AUTO_INCREMENT NOT NULL, week_id INT NOT NULL, complete TINYINT(1) NOT NULL, regular_season TINYINT(1) NOT NULL, INDEX IDX_D5ED5651C86F3B2F (week_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week (id INT AUTO_INCREMENT NOT NULL, season_id INT NOT NULL, number INT NOT NULL, INDEX IDX_5B5A69C04EC001D1 (season_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matchup_player ADD CONSTRAINT FK_FF91DECA5AA47295 FOREIGN KEY (matchup_franchise_id) REFERENCES matchup_franchise (id)');
        $this->addSql('ALTER TABLE matchup_franchise ADD CONSTRAINT FK_9DDAA6EF3965E575 FOREIGN KEY (matchup_id) REFERENCES matchup (id)');
        $this->addSql('ALTER TABLE matchup_franchise ADD CONSTRAINT FK_9DDAA6EF523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE matchup ADD CONSTRAINT FK_D5ED5651C86F3B2F FOREIGN KEY (week_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE week ADD CONSTRAINT FK_5B5A69C04EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE matchup_player DROP FOREIGN KEY FK_FF91DECA5AA47295');
        $this->addSql('ALTER TABLE matchup_franchise DROP FOREIGN KEY FK_9DDAA6EF3965E575');
        $this->addSql('ALTER TABLE matchup DROP FOREIGN KEY FK_D5ED5651C86F3B2F');
        $this->addSql('ALTER TABLE week DROP FOREIGN KEY FK_5B5A69C04EC001D1');
       $this->addSql('DROP TABLE matchup_player');
        $this->addSql('DROP TABLE matchup_franchise');
        $this->addSql('DROP TABLE matchup');
        $this->addSql('DROP TABLE week');
        $this->addSql('DROP TABLE season');
    }
}
