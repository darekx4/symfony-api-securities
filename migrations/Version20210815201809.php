<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210815201809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facts (id INT AUTO_INCREMENT NOT NULL, security_id INT NOT NULL, attribute_id INT DEFAULT NULL, value INT NOT NULL, INDEX IDX_9B040C9B6DBE4214 (security_id), INDEX IDX_9B040C9BB6E62EFA (attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE security (id INT AUTO_INCREMENT NOT NULL, symbol VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facts ADD CONSTRAINT FK_9B040C9B6DBE4214 FOREIGN KEY (security_id) REFERENCES security (id)');
        $this->addSql('ALTER TABLE facts ADD CONSTRAINT FK_9B040C9BB6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facts DROP FOREIGN KEY FK_9B040C9BB6E62EFA');
        $this->addSql('ALTER TABLE facts DROP FOREIGN KEY FK_9B040C9B6DBE4214');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE facts');
        $this->addSql('DROP TABLE security');
    }
}
