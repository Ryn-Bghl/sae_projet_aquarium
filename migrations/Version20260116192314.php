<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260116192314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE aquarium (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, capacity DOUBLE PRECISION NOT NULL, type VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_2BBA6EB2A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE data (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, temp DOUBLE PRECISION NOT NULL, ph DOUBLE PRECISION NOT NULL, kh DOUBLE PRECISION NOT NULL, gh DOUBLE PRECISION NOT NULL, cl2 DOUBLE PRECISION NOT NULL, no2 DOUBLE PRECISION NOT NULL, no3 DOUBLE PRECISION NOT NULL, observation VARCHAR(255) NOT NULL, aquarium_id INT NOT NULL, INDEX IDX_ADF3F3637051F3DE (aquarium_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fish (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, aquarium_id INT NOT NULL, INDEX IDX_3F7444337051F3DE (aquarium_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, user_id INT NOT NULL, INDEX IDX_9F74B898A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE aquarium ADD CONSTRAINT FK_2BBA6EB2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F3637051F3DE FOREIGN KEY (aquarium_id) REFERENCES aquarium (id)');
        $this->addSql('ALTER TABLE fish ADD CONSTRAINT FK_3F7444337051F3DE FOREIGN KEY (aquarium_id) REFERENCES aquarium (id)');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aquarium DROP FOREIGN KEY FK_2BBA6EB2A76ED395');
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F3637051F3DE');
        $this->addSql('ALTER TABLE fish DROP FOREIGN KEY FK_3F7444337051F3DE');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898A76ED395');
        $this->addSql('DROP TABLE aquarium');
        $this->addSql('DROP TABLE data');
        $this->addSql('DROP TABLE fish');
        $this->addSql('DROP TABLE setting');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
