<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190307232831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_trick (tag_id INT NOT NULL, trick_id INT NOT NULL, INDEX IDX_FA1CB018BAD26311 (tag_id), INDEX IDX_FA1CB018B281BE2E (trick_id), PRIMARY KEY(tag_id, trick_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tag_trick ADD CONSTRAINT FK_FA1CB018BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag_trick ADD CONSTRAINT FK_FA1CB018B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(64) DEFAULT NULL, CHANGE data data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE username username VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tag_trick DROP FOREIGN KEY FK_FA1CB018BAD26311');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_trick');
        $this->addSql('ALTER TABLE ext_log_entries CHANGE object_id object_id VARCHAR(64) DEFAULT \'NULL\' COLLATE utf8_unicode_ci, CHANGE data data LONGTEXT DEFAULT \'NULL\' COLLATE utf8_unicode_ci COMMENT \'(DC2Type:array)\', CHANGE username username VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
