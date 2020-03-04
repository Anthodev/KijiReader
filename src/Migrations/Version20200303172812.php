<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200303172812 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed ADD created_at DATETIME NOT NULL, ADD updated_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE feed_user RENAME INDEX idx_181781bc85926e TO IDX_D043D1E51A5BC03');
        $this->addSql('ALTER TABLE feed_user RENAME INDEX idx_181781ba76ed395 TO IDX_D043D1EA76ED395');
        $this->addSql('ALTER TABLE folder_feed RENAME INDEX idx_6bcd172f162cb942 TO IDX_3ADF62BE162CB942');
        $this->addSql('ALTER TABLE folder_feed RENAME INDEX idx_6bcd172fc85926e TO IDX_3ADF62BE51A5BC03');
        $this->addSql('ALTER TABLE story RENAME INDEX idx_eb560438c85926e TO IDX_EB56043851A5BC03');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed DROP created_at, DROP updated_at');
        $this->addSql('ALTER TABLE feed_user RENAME INDEX idx_d043d1e51a5bc03 TO IDX_181781BC85926E');
        $this->addSql('ALTER TABLE feed_user RENAME INDEX idx_d043d1ea76ed395 TO IDX_181781BA76ED395');
        $this->addSql('ALTER TABLE folder_feed RENAME INDEX idx_3adf62be162cb942 TO IDX_6BCD172F162CB942');
        $this->addSql('ALTER TABLE folder_feed RENAME INDEX idx_3adf62be51a5bc03 TO IDX_6BCD172FC85926E');
        $this->addSql('ALTER TABLE story RENAME INDEX idx_eb56043851a5bc03 TO IDX_EB560438C85926E');
    }
}
