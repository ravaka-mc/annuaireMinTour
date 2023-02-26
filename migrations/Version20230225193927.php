<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230225193927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, auteur VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, site_web VARCHAR(255) DEFAULT NULL, proprietaire VARCHAR(255) DEFAULT NULL, gerant VARCHAR(255) DEFAULT NULL, membre TINYINT(1) DEFAULT NULL, date_ouverture DATE DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, nif VARCHAR(255) DEFAULT NULL, stat VARCHAR(255) DEFAULT NULL, nombre_chambres INT DEFAULT NULL, capacite_accueil VARCHAR(255) DEFAULT NULL, nombre_couverts INT DEFAULT NULL, nombre_salaries INT DEFAULT NULL, zone_intervention VARCHAR(255) DEFAULT NULL, categorie_autorisation VARCHAR(255) DEFAULT NULL, carte_professionnelle VARCHAR(255) DEFAULT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE etablissement');
    }
}
