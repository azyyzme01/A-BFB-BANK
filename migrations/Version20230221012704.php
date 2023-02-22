<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230221012704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bon_achat ADD convention_id INT NOT NULL');
        $this->addSql('ALTER TABLE bon_achat ADD CONSTRAINT FK_5B8DF14AA2ACEBCC FOREIGN KEY (convention_id) REFERENCES convention (id)');
        $this->addSql('CREATE INDEX IDX_5B8DF14AA2ACEBCC ON bon_achat (convention_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bon_achat DROP FOREIGN KEY FK_5B8DF14AA2ACEBCC');
        $this->addSql('DROP INDEX IDX_5B8DF14AA2ACEBCC ON bon_achat');
        $this->addSql('ALTER TABLE bon_achat DROP convention_id');
    }
}
