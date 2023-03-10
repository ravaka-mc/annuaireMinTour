<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230310184659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C98260155');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C12469DE2');
        $this->addSql('ALTER TABLE etablissement ADD slug VARCHAR(255) DEFAULT NULL, ADD date_validation DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C98260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C12469DE2');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592C98260155');
        $this->addSql('ALTER TABLE etablissement DROP slug, DROP date_validation');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592C98260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
