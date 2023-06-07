<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607171915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick ADD featured_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E306FF23 FOREIGN KEY (featured_id) REFERENCES media (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91E306FF23 ON trick (featured_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E306FF23');
        $this->addSql('DROP INDEX IDX_D8F0A91E306FF23 ON trick');
        $this->addSql('ALTER TABLE trick DROP featured_id');
    }
}
