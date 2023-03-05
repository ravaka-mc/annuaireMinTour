<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305193616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE etablissement_activite (etablissement_id INT NOT NULL, activite_id INT NOT NULL, INDEX IDX_BA1CD700FF631228 (etablissement_id), INDEX IDX_BA1CD7009B0F88B1 (activite_id), PRIMARY KEY(etablissement_id, activite_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE etablissement_activite ADD CONSTRAINT FK_BA1CD700FF631228 FOREIGN KEY (etablissement_id) REFERENCES etablissement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement_activite ADD CONSTRAINT FK_BA1CD7009B0F88B1 FOREIGN KEY (activite_id) REFERENCES activite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE etablissement ADD classement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE etablissement ADD CONSTRAINT FK_20FD592CA513A63E FOREIGN KEY (classement_id) REFERENCES classement (id)');
        $this->addSql('CREATE INDEX IDX_20FD592CA513A63E ON etablissement (classement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE etablissement_activite DROP FOREIGN KEY FK_BA1CD700FF631228');
        $this->addSql('ALTER TABLE etablissement_activite DROP FOREIGN KEY FK_BA1CD7009B0F88B1');
        $this->addSql('DROP TABLE etablissement_activite');
        $this->addSql('ALTER TABLE etablissement DROP FOREIGN KEY FK_20FD592CA513A63E');
        $this->addSql('DROP INDEX IDX_20FD592CA513A63E ON etablissement');
        $this->addSql('ALTER TABLE etablissement DROP classement_id');
    }
}
