<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230420085452 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE depot_garantit (id INT NOT NULL, id_locataire INT NOT NULL, id_appartement INT NOT NULL, somme DOUBLE PRECISION NOT NULL, etat INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE etat_lieux (id INT NOT NULL, date DATE DEFAULT NULL, remarque VARCHAR(500) DEFAULT NULL, quand VARCHAR(255) DEFAULT NULL, id_locataire INT DEFAULT NULL, id_appartement INT DEFAULT NULL, etat INT DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE depot_garantit');
        $this->addSql('DROP TABLE etat_lieux');
    }
}
