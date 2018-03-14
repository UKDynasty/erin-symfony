<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180314200011 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE position (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, priority INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE franchise (id INT AUTO_INCREMENT NOT NULL, owner_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, mfl_franchise_id VARCHAR(255) NOT NULL, espn_franchise_id VARCHAR(255) NOT NULL, identifiers LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', UNIQUE INDEX UNIQ_66F6CE2A7E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owner (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, group_me_user_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, franchise_id INT DEFAULT NULL, position_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birthdate DATE DEFAULT NULL, draft_year INT DEFAULT NULL, draft_round INT DEFAULT NULL, draft_pick INT DEFAULT NULL, draft_team VARCHAR(255) DEFAULT NULL, weight INT DEFAULT NULL, height INT DEFAULT NULL, jersey INT DEFAULT NULL, college VARCHAR(255) DEFAULT NULL, external_id_mfl VARCHAR(255) DEFAULT NULL, external_id_nfl VARCHAR(255) DEFAULT NULL, external_id_rotoworld VARCHAR(255) DEFAULT NULL, external_id_stats VARCHAR(255) DEFAULT NULL, external_id_stats_global VARCHAR(255) DEFAULT NULL, external_id_fleaflicker VARCHAR(255) DEFAULT NULL, external_id_kffl VARCHAR(255) DEFAULT NULL, external_id_espn VARCHAR(255) DEFAULT NULL, external_id_sportsdata VARCHAR(255) DEFAULT NULL, external_id_cbs VARCHAR(255) DEFAULT NULL, external_id_gsis VARCHAR(255) DEFAULT NULL, external_id_esb VARCHAR(255) DEFAULT NULL, twitter_handle VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_98197A6522CA1780 (external_id_mfl), INDEX IDX_98197A65523CAB89 (franchise_id), INDEX IDX_98197A65DD842E46 (position_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE franchise ADD CONSTRAINT FK_66F6CE2A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65DD842E46 FOREIGN KEY (position_id) REFERENCES position (id)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65DD842E46');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65523CAB89');
        $this->addSql('ALTER TABLE franchise DROP FOREIGN KEY FK_66F6CE2A7E3C61F9');
        $this->addSql('DROP TABLE position');
        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE owner');
        $this->addSql('DROP TABLE player');
    }
}
