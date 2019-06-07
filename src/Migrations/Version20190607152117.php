<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190607152117 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE trade_side (id INT AUTO_INCREMENT NOT NULL, trade_id INT DEFAULT NULL, franchise_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_417A6C4EC2D9760 (trade_id), INDEX IDX_417A6C4E523CAB89 (franchise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trade_side_player (trade_side_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_9AE29BD565DDD932 (trade_side_id), INDEX IDX_9AE29BD599E6F5DF (player_id), PRIMARY KEY(trade_side_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trade_side_draft_pick (trade_side_id INT NOT NULL, draft_pick_id INT NOT NULL, INDEX IDX_2F9994C865DDD932 (trade_side_id), INDEX IDX_2F9994C8D834859 (draft_pick_id), PRIMARY KEY(trade_side_id, draft_pick_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trade (id INT AUTO_INCREMENT NOT NULL, winning_side_id INT DEFAULT NULL, date DATETIME NOT NULL, mfl_api_hash LONGTEXT NOT NULL, INDEX IDX_7E1A43666F10003 (winning_side_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE trade_side ADD CONSTRAINT FK_417A6C4EC2D9760 FOREIGN KEY (trade_id) REFERENCES trade (id)');
        $this->addSql('ALTER TABLE trade_side ADD CONSTRAINT FK_417A6C4E523CAB89 FOREIGN KEY (franchise_id) REFERENCES franchise (id)');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD565DDD932 FOREIGN KEY (trade_side_id) REFERENCES trade_side (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C865DDD932 FOREIGN KEY (trade_side_id) REFERENCES trade_side (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C8D834859 FOREIGN KEY (draft_pick_id) REFERENCES draft_pick (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade ADD CONSTRAINT FK_7E1A43666F10003 FOREIGN KEY (winning_side_id) REFERENCES trade_side (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade_side_player DROP FOREIGN KEY FK_9AE29BD565DDD932');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP FOREIGN KEY FK_2F9994C865DDD932');
        $this->addSql('ALTER TABLE trade DROP FOREIGN KEY FK_7E1A43666F10003');
        $this->addSql('ALTER TABLE trade_side DROP FOREIGN KEY FK_417A6C4EC2D9760');
        $this->addSql('DROP TABLE trade_side');
        $this->addSql('DROP TABLE trade_side_player');
        $this->addSql('DROP TABLE trade_side_draft_pick');
        $this->addSql('DROP TABLE trade');
    }
}
