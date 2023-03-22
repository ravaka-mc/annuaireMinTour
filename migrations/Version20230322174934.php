<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322174934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_groupement (category_id INT NOT NULL, groupement_id INT NOT NULL, INDEX IDX_EED7A12C12469DE2 (category_id), INDEX IDX_EED7A12CE66695CE (groupement_id), PRIMARY KEY(category_id, groupement_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_groupement ADD CONSTRAINT FK_EED7A12C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_groupement ADD CONSTRAINT FK_EED7A12CE66695CE FOREIGN KEY (groupement_id) REFERENCES groupement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_groupement DROP FOREIGN KEY FK_EED7A12C12469DE2');
        $this->addSql('ALTER TABLE category_groupement DROP FOREIGN KEY FK_EED7A12CE66695CE');
        $this->addSql('DROP TABLE category_groupement');
    }
}
