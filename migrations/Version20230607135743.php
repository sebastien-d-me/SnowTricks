<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607135743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722F90332D6B');
        $this->addSql('DROP INDEX UNIQ_1677722F90332D6B ON avatar');
        $this->addSql('ALTER TABLE avatar DROP id_login_credentials_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar ADD id_login_credentials_id INT NOT NULL');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722F90332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722F90332D6B ON avatar (id_login_credentials_id)');
    }
}
