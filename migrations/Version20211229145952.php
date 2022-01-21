<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229145952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant ADD ecole_id INT NOT NULL');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA277EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecole (id)');
        $this->addSql('CREATE INDEX IDX_34B70CA277EF1B1E ON enfant (ecole_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA277EF1B1E');
        $this->addSql('DROP INDEX IDX_34B70CA277EF1B1E ON enfant');
        $this->addSql('ALTER TABLE enfant DROP ecole_id');
    }
}
