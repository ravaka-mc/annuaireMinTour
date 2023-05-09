<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508193155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category_activite_licence_a (category_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_99203FA112469DE2 (category_id), INDEX IDX_99203FA19B0F88B1 (activite_id), PRIMARY KEY(category_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_activite_licence_a ADD CONSTRAINT FK_99203FA112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_activite_licence_a ADD CONSTRAINT FK_99203FA19B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category_activite_licence_a DROP FOREIGN KEY FK_99203FA112469DE2');
        $this->addSql('ALTER TABLE category_activite_licence_a DROP FOREIGN KEY FK_99203FA19B0F88B1');
        $this->addSql('DROP TABLE category_activite_licence_a');
    }
}
