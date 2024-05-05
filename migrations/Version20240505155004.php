<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505155004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entity (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, preview VARCHAR(255) NOT NULL, INDEX IDX_E2844689777D11E (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, entity_id INT NOT NULL, title VARCHAR(70) NOT NULL, description VARCHAR(1000) NOT NULL, file_path VARCHAR(250) NOT NULL, is_movie TINYINT(1) NOT NULL, upload_date DATETIME NOT NULL, release_date DATE NOT NULL, views INT NOT NULL, duration VARCHAR(10) NOT NULL, season INT NOT NULL, episode INT NOT NULL, INDEX IDX_7CC7DA2C81257D5D (entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entity ADD CONSTRAINT FK_E2844689777D11E FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C81257D5D FOREIGN KEY (entity_id) REFERENCES entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE entity DROP FOREIGN KEY FK_E2844689777D11E');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C81257D5D');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE entity');
        $this->addSql('DROP TABLE video');
    }
}
