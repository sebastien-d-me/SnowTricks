<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516144138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avatar (id INT AUTO_INCREMENT NOT NULL, id_member_id INT NOT NULL, path VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1677722FF5A26FD9 (id_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, id_member_id INT NOT NULL, id_trick_id INT NOT NULL, creation_date DATE NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_9474526CF5A26FD9 (id_member_id), INDEX IDX_9474526CE25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hash (id INT AUTO_INCREMENT NOT NULL, id_member_id INT NOT NULL, hash VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_D1B862B8F5A26FD9 (id_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE illustration (id INT AUTO_INCREMENT NOT NULL, id_trick_id INT NOT NULL, type VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_D67B9A42E25A52BB (id_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE login_credentials (id INT AUTO_INCREMENT NOT NULL, id_member_id INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_487594BEE7927C74 (email), UNIQUE INDEX UNIQ_487594BEF5A26FD9 (id_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, id_trick_group_id INT NOT NULL, id_member_id INT NOT NULL, name VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_D8F0A91EA6E1E0FE (id_trick_group_id), INDEX IDX_D8F0A91EF5A26FD9 (id_member_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE hash ADD CONSTRAINT FK_D1B862B8F5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE illustration ADD CONSTRAINT FK_D67B9A42E25A52BB FOREIGN KEY (id_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE login_credentials ADD CONSTRAINT FK_487594BEF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA6E1E0FE FOREIGN KEY (id_trick_group_id) REFERENCES trick_group (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FF5A26FD9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF5A26FD9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE25A52BB');
        $this->addSql('ALTER TABLE hash DROP FOREIGN KEY FK_D1B862B8F5A26FD9');
        $this->addSql('ALTER TABLE illustration DROP FOREIGN KEY FK_D67B9A42E25A52BB');
        $this->addSql('ALTER TABLE login_credentials DROP FOREIGN KEY FK_487594BEF5A26FD9');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA6E1E0FE');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EF5A26FD9');
        $this->addSql('DROP TABLE avatar');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE hash');
        $this->addSql('DROP TABLE illustration');
        $this->addSql('DROP TABLE login_credentials');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE trick_group');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
