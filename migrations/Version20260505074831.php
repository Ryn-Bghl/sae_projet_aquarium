<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260505074831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Add columns as nullable first
        $this->addSql('ALTER TABLE data ADD updated_at DATETIME DEFAULT NULL, ADD created_by_id INT DEFAULT NULL, ADD updated_by_id INT DEFAULT NULL');
        
        // Add foreign key constraints (deferrable for now, then alter)
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F363B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE data ADD CONSTRAINT FK_ADF3F363896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        
        // Set a default value for existing rows for created_by_id
        // IMPORTANT: Replace '(SELECT id FROM user LIMIT 1)' with the ID of an existing user in your 'user' table if needed
        $this->addSql('UPDATE data SET created_by_id = (SELECT id FROM user LIMIT 1) WHERE created_by_id IS NULL');

        // Now, alter the column to be NOT NULL
        $this->addSql('ALTER TABLE data CHANGE created_by_id created_by_id INT NOT NULL');

        $this->addSql('CREATE INDEX IDX_ADF3F363B03A8386 ON data (created_by_id)');
        $this->addSql('CREATE INDEX IDX_ADF3F363896DBBDE ON data (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F363B03A8386');
        $this->addSql('ALTER TABLE data DROP FOREIGN KEY FK_ADF3F363896DBBDE');
        $this->addSql('DROP INDEX IDX_ADF3F363B03A8386 ON data');
        $this->addSql('DROP INDEX IDX_ADF3F363896DBBDE ON data');
        $this->addSql('ALTER TABLE data DROP updated_at, DROP created_by_id, DROP updated_by_id');
    }
}
