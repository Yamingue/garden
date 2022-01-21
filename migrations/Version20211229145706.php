<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211229145706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enfant_user (enfant_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_EBBCCE7E450D2529 (enfant_id), INDEX IDX_EBBCCE7EA76ED395 (user_id), PRIMARY KEY(enfant_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enfant_user ADD CONSTRAINT FK_EBBCCE7E450D2529 FOREIGN KEY (enfant_id) REFERENCES enfant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enfant_user ADD CONSTRAINT FK_EBBCCE7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enfant ADD salle_id INT NOT NULL');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA2DC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_34B70CA2DC304035 ON enfant (salle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE enfant_user');
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA2DC304035');
        $this->addSql('DROP INDEX IDX_34B70CA2DC304035 ON enfant');
        $this->addSql('ALTER TABLE enfant DROP salle_id');
    }
}
