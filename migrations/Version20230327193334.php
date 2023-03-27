<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327193334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `delete` DROP FOREIGN KEY FK_3A127C87FF631228');
        $this->addSql('ALTER TABLE `delete` CHANGE etablissement_id etablissement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `delete` ADD CONSTRAINT FK_3A127C87FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `delete` DROP FOREIGN KEY FK_3A127C87FF631228');
        $this->addSql('ALTER TABLE `delete` CHANGE etablissement_id etablissement_id INT NOT NULL');
        $this->addSql('ALTER TABLE `delete` ADD CONSTRAINT FK_3A127C87FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id)');
    }
}
