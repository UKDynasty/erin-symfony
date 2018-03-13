<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180313233021 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE franchise (id INTEGER NOT NULL, owner_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, mfl_franchise_id VARCHAR(255) NOT NULL, espn_franchise_id VARCHAR(255) NOT NULL, identifiers CLOB NOT NULL --(DC2Type:simple_array)
        , PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66F6CE2A7E3C61F9 ON franchise (owner_id)');
        $this->addSql('CREATE TABLE owner (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, group_me_user_id VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE player (id INTEGER NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birthdate DATE DEFAULT NULL, draft_year INTEGER DEFAULT NULL, draft_round INTEGER DEFAULT NULL, draft_pick INTEGER DEFAULT NULL, draft_team VARCHAR(255) DEFAULT NULL, position VARCHAR(255) DEFAULT NULL, weight INTEGER DEFAULT NULL, height INTEGER DEFAULT NULL, jersey INTEGER DEFAULT NULL, college VARCHAR(255) DEFAULT NULL, external_id_mfl VARCHAR(255) DEFAULT NULL, external_id_nfl VARCHAR(255) DEFAULT NULL, external_id_rotoworld VARCHAR(255) DEFAULT NULL, external_id_stats VARCHAR(255) DEFAULT NULL, external_id_stats_global VARCHAR(255) DEFAULT NULL, external_id_fleaflicker VARCHAR(255) DEFAULT NULL, external_id_kffl VARCHAR(255) DEFAULT NULL, external_id_espn VARCHAR(255) DEFAULT NULL, external_id_sportsdata VARCHAR(255) DEFAULT NULL, external_id_cbs VARCHAR(255) DEFAULT NULL, external_id_gsis VARCHAR(255) DEFAULT NULL, external_id_esb VARCHAR(255) DEFAULT NULL, twitter_handle VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_98197A6522CA1780 ON player (external_id_mfl)');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE franchise');
        $this->addSql('DROP TABLE owner');
        $this->addSql('DROP TABLE player');
    }
}
