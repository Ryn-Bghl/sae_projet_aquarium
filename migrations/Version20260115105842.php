<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260115105842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aquarium ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE aquarium ADD CONSTRAINT FK_2BBA6EB2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2BBA6EB2A76ED395 ON aquarium (user_id)');
        $this->addSql('ALTER TABLE setting ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE setting ADD CONSTRAINT FK_9F74B898A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_9F74B898A76ED395 ON setting (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE aquarium DROP FOREIGN KEY FK_2BBA6EB2A76ED395');
        $this->addSql('DROP INDEX IDX_2BBA6EB2A76ED395 ON aquarium');
        $this->addSql('ALTER TABLE aquarium DROP user_id');
        $this->addSql('ALTER TABLE setting DROP FOREIGN KEY FK_9F74B898A76ED395');
        $this->addSql('DROP INDEX IDX_9F74B898A76ED395 ON setting');
        $this->addSql('ALTER TABLE setting DROP user_id');
    }
}
