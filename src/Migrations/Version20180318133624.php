<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180318133624 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE draft (id INT AUTO_INCREMENT NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE draft_pick (id INT AUTO_INCREMENT NOT NULL, draft_id INT DEFAULT NULL, original_owner_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, player_id INT DEFAULT NULL, round INT NOT NULL, number INT DEFAULT NULL, overall INT DEFAULT NULL, INDEX IDX_838D399FE2F3C5D1 (draft_id), INDEX IDX_838D399F5D6386E8 (original_owner_id), INDEX IDX_838D399F7E3C61F9 (owner_id), INDEX IDX_838D399F99E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE draft_pick ADD CONSTRAINT FK_838D399FE2F3C5D1 FOREIGN KEY (draft_id) REFERENCES draft (id)');
        $this->addSql('ALTER TABLE draft_pick ADD CONSTRAINT FK_838D399F5D6386E8 FOREIGN KEY (original_owner_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE draft_pick ADD CONSTRAINT FK_838D399F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE draft_pick ADD CONSTRAINT FK_838D399F99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE draft_pick DROP FOREIGN KEY FK_838D399FE2F3C5D1');
        $this->addSql('DROP TABLE draft');
        $this->addSql('DROP TABLE draft_pick');
    }
}
