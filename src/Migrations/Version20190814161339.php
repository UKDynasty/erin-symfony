<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190814161339 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE group_me_message_image_attachment (id INT AUTO_INCREMENT NOT NULL, message_id INT NOT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_D5D2ACC0537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE group_me_message (id INT AUTO_INCREMENT NOT NULL, message_id VARCHAR(255) NOT NULL, source_guid VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, avatar_url VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, system TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE group_me_message_image_attachment ADD CONSTRAINT FK_D5D2ACC0537A1329 FOREIGN KEY (message_id) REFERENCES group_me_message (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE group_me_message_image_attachment DROP FOREIGN KEY FK_D5D2ACC0537A1329');
        $this->addSql('DROP TABLE group_me_message_image_attachment');
        $this->addSql('DROP TABLE group_me_message');
    }
}
