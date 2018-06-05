<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180605124941 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE franchise SET name = 'Flitwick Fireflies', identifiers = 'flitwick,fireflies' WHERE mfl_franchise_id = '0002'");
        $this->addSql("UPDATE owner SET name = 'Mike', group_me_user_id = '60310674' WHERE name = 'Hamza'");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
