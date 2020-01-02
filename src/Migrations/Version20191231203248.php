<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191231203248 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE flux (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, rss_link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE flux_user (flux_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_181781BC85926E (flux_id), INDEX IDX_181781BA76ED395 (user_id), PRIMARY KEY(flux_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_ECA209CDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder_flux (folder_id INT NOT NULL, flux_id INT NOT NULL, INDEX IDX_6BCD172F162CB942 (folder_id), INDEX IDX_6BCD172FC85926E (flux_id), PRIMARY KEY(folder_id, flux_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(16) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, view_flux INT NOT NULL, view_article INT NOT NULL, order_article INT NOT NULL, display_unread TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E545A0C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, flux_id INT NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(512) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, starred TINYINT(1) NOT NULL, seen TINYINT(1) NOT NULL, INDEX IDX_EB560438C85926E (flux_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, username VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flux_user ADD CONSTRAINT FK_181781BC85926E FOREIGN KEY (flux_id) REFERENCES flux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flux_user ADD CONSTRAINT FK_181781BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE folder_flux ADD CONSTRAINT FK_6BCD172F162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE folder_flux ADD CONSTRAINT FK_6BCD172FC85926E FOREIGN KEY (flux_id) REFERENCES flux (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB560438C85926E FOREIGN KEY (flux_id) REFERENCES flux (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE flux_user DROP FOREIGN KEY FK_181781BC85926E');
        $this->addSql('ALTER TABLE folder_flux DROP FOREIGN KEY FK_6BCD172FC85926E');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB560438C85926E');
        $this->addSql('ALTER TABLE folder_flux DROP FOREIGN KEY FK_6BCD172F162CB942');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE flux_user DROP FOREIGN KEY FK_181781BA76ED395');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CDA76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('DROP TABLE flux');
        $this->addSql('DROP TABLE flux_user');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE folder_flux');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE user');
    }
}
