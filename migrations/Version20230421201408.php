<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230421201408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement ADD district_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CB08FA272 FOREIGN KEY (district_id) REFERENCES district (id)');
        $this->addSql('CREATE INDEX IDX_20FD592CB08FA272 ON etablissement (district_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CB08FA272');
        $this->addSql('DROP INDEX IDX_20FD592CB08FA272 ON etablissement');
        $this->addSql('ALTER TABLE etablissement DROP district_id');
    }
}
