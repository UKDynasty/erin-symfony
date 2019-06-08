<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190608105704 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade_side_player DROP FOREIGN KEY FK_9AE29BD565DDD932');
        $this->addSql('ALTER TABLE trade_side_player DROP FOREIGN KEY FK_9AE29BD599E6F5DF');
        $this->addSql('DROP INDEX IDX_9AE29BD565DDD932 ON trade_side_player');
        $this->addSql('ALTER TABLE trade_side_player DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE trade_side_player ADD id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id), ADD value INT NOT NULL, CHANGE trade_side_id side_id INT NOT NULL');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD5965D81C4 FOREIGN KEY (side_id) REFERENCES trade_side (id)');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('CREATE INDEX IDX_9AE29BD5965D81C4 ON trade_side_player (side_id)');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP FOREIGN KEY FK_2F9994C865DDD932');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP FOREIGN KEY FK_2F9994C8D834859');
        $this->addSql('DROP INDEX IDX_2F9994C8D834859 ON trade_side_draft_pick');
        $this->addSql('DROP INDEX IDX_2F9994C865DDD932 ON trade_side_draft_pick');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (id), ADD pick_id INT DEFAULT NULL, ADD side_id INT DEFAULT NULL, ADD value INT NOT NULL, DROP trade_side_id, DROP draft_pick_id');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C8F54A307A FOREIGN KEY (pick_id) REFERENCES draft_pick (id)');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C8965D81C4 FOREIGN KEY (side_id) REFERENCES trade_side (id)');
        $this->addSql('CREATE INDEX IDX_2F9994C8F54A307A ON trade_side_draft_pick (pick_id)');
        $this->addSql('CREATE INDEX IDX_2F9994C8965D81C4 ON trade_side_draft_pick (side_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE trade_side_draft_pick MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP FOREIGN KEY FK_2F9994C8F54A307A');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP FOREIGN KEY FK_2F9994C8965D81C4');
        $this->addSql('DROP INDEX IDX_2F9994C8F54A307A ON trade_side_draft_pick');
        $this->addSql('DROP INDEX IDX_2F9994C8965D81C4 ON trade_side_draft_pick');
        $this->addSql('ALTER TABLE trade_side_draft_pick DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD draft_pick_id INT NOT NULL, DROP id, DROP pick_id, DROP side_id, CHANGE value trade_side_id INT NOT NULL');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C865DDD932 FOREIGN KEY (trade_side_id) REFERENCES trade_side (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD CONSTRAINT FK_2F9994C8D834859 FOREIGN KEY (draft_pick_id) REFERENCES draft_pick (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_2F9994C8D834859 ON trade_side_draft_pick (draft_pick_id)');
        $this->addSql('CREATE INDEX IDX_2F9994C865DDD932 ON trade_side_draft_pick (trade_side_id)');
        $this->addSql('ALTER TABLE trade_side_draft_pick ADD PRIMARY KEY (trade_side_id, draft_pick_id)');
        $this->addSql('ALTER TABLE trade_side_player MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE trade_side_player DROP FOREIGN KEY FK_9AE29BD5965D81C4');
        $this->addSql('ALTER TABLE trade_side_player DROP FOREIGN KEY FK_9AE29BD599E6F5DF');
        $this->addSql('DROP INDEX IDX_9AE29BD5965D81C4 ON trade_side_player');
        $this->addSql('ALTER TABLE trade_side_player DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE trade_side_player ADD trade_side_id INT NOT NULL, DROP id, DROP side_id, DROP value');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD565DDD932 FOREIGN KEY (trade_side_id) REFERENCES trade_side (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE trade_side_player ADD CONSTRAINT FK_9AE29BD599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_9AE29BD565DDD932 ON trade_side_player (trade_side_id)');
        $this->addSql('ALTER TABLE trade_side_player ADD PRIMARY KEY (trade_side_id, player_id)');
    }
}
