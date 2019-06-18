<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190618155753 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE franchise DROP espn_franchise_id, DROP espn_roster_count_total, DROP espn_roster_count_regular, DROP espn_roster_count_ir, DROP taxi_squad_count');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE franchise ADD espn_franchise_id VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, ADD espn_roster_count_total INT NOT NULL, ADD espn_roster_count_regular INT NOT NULL, ADD espn_roster_count_ir INT NOT NULL, ADD taxi_squad_count INT NOT NULL');
    }
}
