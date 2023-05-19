<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519004515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, id_login_credentials_id INT NOT NULL, path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1677722F90332D6B (id_login_credentials_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, id_login_credentials_id INT NOT NULL, id_trick_id INT NOT NULL, creation_date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526C90332D6B (id_login_credentials_id), INDEX IDX_9474526CE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hash (id INT AUTO_INCREMENT NOT NULL, id_login_credentials_id INT NOT NULL, hash VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_D1B862B890332D6B (id_login_credentials_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_credentials (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_487594BEE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, id_trick_id INT NOT NULL, type VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_6A2CA10CE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, id_trick_group_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_D8F0A91EA6E1E0FE (id_trick_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722F90332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C90332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE hash ADD CONSTRAINT FK_D1B862B890332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10CE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA6E1E0FE FOREIGN KEY (id_trick_group_id) REFERENCES trick_group (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722F90332D6B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C90332D6B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE25A52BB');
        $this->addSql('ALTER TABLE hash DROP FOREIGN KEY FK_D1B862B890332D6B');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10CE25A52BB');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA6E1E0FE');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE hash');
        $this->addSql('DROP TABLE login_credentials');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE trick_group');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
