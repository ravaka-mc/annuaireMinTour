<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303213554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement ADD licence_a TINYINT(1) DEFAULT NULL, ADD licence_b TINYINT(1) DEFAULT NULL, ADD licence_c TINYINT(1) DEFAULT NULL, ADD date_licence_a DATE DEFAULT NULL, ADD date_licence_b DATE DEFAULT NULL, ADD date_licence_c DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP licence_a, DROP licence_b, DROP licence_c, DROP date_licence_a, DROP date_licence_b, DROP date_licence_c');
    }
}
