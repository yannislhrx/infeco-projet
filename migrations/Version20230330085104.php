<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230330085104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE appartement (id INT AUTO_INCREMENT NOT NULL, adresse VARCHAR(500) NOT NULL, complement VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) NOT NULL, code_postal VARCHAR(50) NOT NULL, loyer DOUBLE PRECISION NOT NULL, charges DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot_garantit (id INT AUTO_INCREMENT NOT NULL, id_locataire INT NOT NULL, id_appartement INT NOT NULL, somme DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etat_lieux (id INT AUTO_INCREMENT NOT NULL, date DATE NOT NULL, remarque VARCHAR(500) DEFAULT NULL, quand VARCHAR(255) NOT NULL, id_locataire INT NOT NULL, id_appartement INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE frais (id INT AUTO_INCREMENT NOT NULL, pourcentage INT NOT NULL, id_appartement INT NOT NULL, id_agence INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE locataire (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, mail VARCHAR(255) NOT NULL, tel VARCHAR(255) NOT NULL, adresse VARCHAR(500) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, somme DOUBLE PRECISION NOT NULL, emmeteur VARCHAR(255) NOT NULL, date DATE NOT NULL, id_appartement INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solde (id INT AUTO_INCREMENT NOT NULL, id_locataire INT NOT NULL, montant DOUBLE PRECISION NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE appartement');
        $this->addSql('DROP TABLE depot_garantit');
        $this->addSql('DROP TABLE etat_lieux');
        $this->addSql('DROP TABLE frais');
        $this->addSql('DROP TABLE locataire');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE solde');
    }
}
