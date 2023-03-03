<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230303195021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement_groupement (etablissement_id INT NOT NULL, groupement_id INT NOT NULL, INDEX IDX_FCCAF723FF631228 (etablissement_id), INDEX IDX_FCCAF723E66695CE (groupement_id), PRIMARY KEY(etablissement_id, groupement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement_groupement ADD CONSTRAINT FK_FCCAF723FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_groupement ADD CONSTRAINT FK_FCCAF723E66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement_groupement DROP FOREIGN KEY FK_FCCAF723FF631228');
        $this->addSql('ALTER TABLE etablissement_groupement DROP FOREIGN KEY FK_FCCAF723E66695CE');
        $this->addSql('DROP TABLE etablissement_groupement');
    }
}
