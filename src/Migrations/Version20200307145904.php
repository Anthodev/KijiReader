<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307145904 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE feed (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, website VARCHAR(255) NOT NULL, rss_link VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feed_user (feed_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_D043D1E51A5BC03 (feed_id), INDEX IDX_D043D1EA76ED395 (user_id), PRIMARY KEY(feed_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_ECA209CDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE folder_feed (folder_id INT NOT NULL, feed_id INT NOT NULL, INDEX IDX_3ADF62BE162CB942 (folder_id), INDEX IDX_3ADF62BE51A5BC03 (feed_id), PRIMARY KEY(folder_id, feed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(16) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, view_feed INT NOT NULL, view_article INT NOT NULL, order_article INT NOT NULL, display_unread TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_E545A0C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE story (id INT AUTO_INCREMENT NOT NULL, feed_id INT NOT NULL, title VARCHAR(255) NOT NULL, url VARCHAR(512) NOT NULL, content LONGTEXT NOT NULL, date DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_EB56043851A5BC03 (feed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, role_id INT NOT NULL, username VARCHAR(64) NOT NULL, password VARCHAR(255) NOT NULL, plain_password VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_8D93D649D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_story (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, feed_id INT DEFAULT NULL, story_id INT DEFAULT NULL, starred TINYINT(1) NOT NULL, read_status TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_994FF60A76ED395 (user_id), INDEX IDX_994FF6051A5BC03 (feed_id), INDEX IDX_994FF60AA5D4036 (story_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE feed_user ADD CONSTRAINT FK_D043D1E51A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE feed_user ADD CONSTRAINT FK_D043D1EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE folder ADD CONSTRAINT FK_ECA209CDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE folder_feed ADD CONSTRAINT FK_3ADF62BE162CB942 FOREIGN KEY (folder_id) REFERENCES folder (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE folder_feed ADD CONSTRAINT FK_3ADF62BE51A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE story ADD CONSTRAINT FK_EB56043851A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D60322AC FOREIGN KEY (role_id) REFERENCES role (id)');
        $this->addSql('ALTER TABLE user_story ADD CONSTRAINT FK_994FF60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_story ADD CONSTRAINT FK_994FF6051A5BC03 FOREIGN KEY (feed_id) REFERENCES feed (id)');
        $this->addSql('ALTER TABLE user_story ADD CONSTRAINT FK_994FF60AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE feed_user DROP FOREIGN KEY FK_D043D1E51A5BC03');
        $this->addSql('ALTER TABLE folder_feed DROP FOREIGN KEY FK_3ADF62BE51A5BC03');
        $this->addSql('ALTER TABLE story DROP FOREIGN KEY FK_EB56043851A5BC03');
        $this->addSql('ALTER TABLE user_story DROP FOREIGN KEY FK_994FF6051A5BC03');
        $this->addSql('ALTER TABLE folder_feed DROP FOREIGN KEY FK_3ADF62BE162CB942');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D60322AC');
        $this->addSql('ALTER TABLE user_story DROP FOREIGN KEY FK_994FF60AA5D4036');
        $this->addSql('ALTER TABLE feed_user DROP FOREIGN KEY FK_D043D1EA76ED395');
        $this->addSql('ALTER TABLE folder DROP FOREIGN KEY FK_ECA209CDA76ED395');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5A76ED395');
        $this->addSql('ALTER TABLE user_story DROP FOREIGN KEY FK_994FF60A76ED395');
        $this->addSql('DROP TABLE feed');
        $this->addSql('DROP TABLE feed_user');
        $this->addSql('DROP TABLE folder');
        $this->addSql('DROP TABLE folder_feed');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE story');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_story');
    }
}
