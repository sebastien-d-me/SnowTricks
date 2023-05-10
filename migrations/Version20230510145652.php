<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230510145652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE illustration ADD path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE login_credentials ADD username VARCHAR(255) NOT NULL, ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE member DROP is_active');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE illustration DROP path');
        $this->addSql('ALTER TABLE login_credentials DROP username, DROP is_active');
        $this->addSql('ALTER TABLE member ADD is_active TINYINT(1) NOT NULL');
    }
}
