<?php declare(strict_types = 1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180625182806 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_me_message (id INT AUTO_INCREMENT NOT NULL, sender_id INT DEFAULT NULL, group_me_message_id VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, text LONGTEXT NOT NULL, INDEX IDX_A17D4C84F624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_me_message_owner (group_me_message_id INT NOT NULL, owner_id INT NOT NULL, INDEX IDX_1C95A670F2057B45 (group_me_message_id), INDEX IDX_1C95A6707E3C61F9 (owner_id), PRIMARY KEY(group_me_message_id, owner_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_me_message ADD CONSTRAINT FK_A17D4C84F624B39D FOREIGN KEY (sender_id) REFERENCES owner (id)');
        $this->addSql('ALTER TABLE group_me_message_owner ADD CONSTRAINT FK_1C95A670F2057B45 FOREIGN KEY (group_me_message_id) REFERENCES group_me_message (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE group_me_message_owner ADD CONSTRAINT FK_1C95A6707E3C61F9 FOREIGN KEY (owner_id) REFERENCES owner (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE group_me_message_owner DROP FOREIGN KEY FK_1C95A670F2057B45');
        $this->addSql('DROP TABLE group_me_message');
        $this->addSql('DROP TABLE group_me_message_owner');
    }
}
