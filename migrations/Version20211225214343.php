<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211225214343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudo VARCHAR(255) NOT NULL, tel BIGINT NOT NULL, UNIQUE INDEX UNIQ_880E0D76E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ecole (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', update_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expire_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', logo VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9786AACE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gardienne (id INT AUTO_INCREMENT NOT NULL, ecole_id INT NOT NULL, salles_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, tel BIGINT DEFAULT NULL, photo VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_36EDF371E7927C74 (email), INDEX IDX_36EDF37177EF1B1E (ecole_id), INDEX IDX_36EDF371B11E4946 (salles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, ecole_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_4E977E5C77EF1B1E (ecole_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gardienne ADD CONSTRAINT FK_36EDF37177EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecole (id)');
        $this->addSql('ALTER TABLE gardienne ADD CONSTRAINT FK_36EDF371B11E4946 FOREIGN KEY (salles_id) REFERENCES salle (id)');
        $this->addSql('ALTER TABLE salle ADD CONSTRAINT FK_4E977E5C77EF1B1E FOREIGN KEY (ecole_id) REFERENCES ecole (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gardienne DROP FOREIGN KEY FK_36EDF37177EF1B1E');
        $this->addSql('ALTER TABLE salle DROP FOREIGN KEY FK_4E977E5C77EF1B1E');
        $this->addSql('ALTER TABLE gardienne DROP FOREIGN KEY FK_36EDF371B11E4946');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE ecole');
        $this->addSql('DROP TABLE gardienne');
        $this->addSql('DROP TABLE salle');
    }
}
