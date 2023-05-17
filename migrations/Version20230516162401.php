<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516162401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FF5A26FD9');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF5A26FD9');
        $this->addSql('ALTER TABLE hash DROP FOREIGN KEY FK_D1B862B8F5A26FD9');
        $this->addSql('ALTER TABLE login_credentials DROP FOREIGN KEY FK_487594BEF5A26FD9');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EF5A26FD9');
        $this->addSql('DROP TABLE member');
        $this->addSql('DROP INDEX UNIQ_1677722FF5A26FD9 ON avatar');
        $this->addSql('ALTER TABLE avatar CHANGE id_member_id id_login_credentials_id INT NOT NULL');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722F90332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722F90332D6B ON avatar (id_login_credentials_id)');
        $this->addSql('DROP INDEX IDX_9474526CF5A26FD9 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE id_member_id id_login_credentials_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C90332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('CREATE INDEX IDX_9474526C90332D6B ON comment (id_login_credentials_id)');
        $this->addSql('DROP INDEX IDX_D1B862B8F5A26FD9 ON hash');
        $this->addSql('ALTER TABLE hash CHANGE id_member_id id_login_credentials_id INT NOT NULL');
        $this->addSql('ALTER TABLE hash ADD CONSTRAINT FK_D1B862B890332D6B FOREIGN KEY (id_login_credentials_id) REFERENCES login_credentials (id)');
        $this->addSql('CREATE INDEX IDX_D1B862B890332D6B ON hash (id_login_credentials_id)');
        $this->addSql('DROP INDEX UNIQ_487594BEF5A26FD9 ON login_credentials');
        $this->addSql('ALTER TABLE login_credentials ADD first_name VARCHAR(50) DEFAULT NULL, ADD last_name VARCHAR(50) DEFAULT NULL, DROP id_member_id');
        $this->addSql('DROP INDEX IDX_D8F0A91EF5A26FD9 ON trick');
        $this->addSql('ALTER TABLE trick DROP id_member_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722F90332D6B');
        $this->addSql('DROP INDEX UNIQ_1677722F90332D6B ON avatar');
        $this->addSql('ALTER TABLE avatar CHANGE id_login_credentials_id id_member_id INT NOT NULL');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722FF5A26FD9 ON avatar (id_member_id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C90332D6B');
        $this->addSql('DROP INDEX IDX_9474526C90332D6B ON comment');
        $this->addSql('ALTER TABLE comment CHANGE id_login_credentials_id id_member_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('CREATE INDEX IDX_9474526CF5A26FD9 ON comment (id_member_id)');
        $this->addSql('ALTER TABLE hash DROP FOREIGN KEY FK_D1B862B890332D6B');
        $this->addSql('DROP INDEX IDX_D1B862B890332D6B ON hash');
        $this->addSql('ALTER TABLE hash CHANGE id_login_credentials_id id_member_id INT NOT NULL');
        $this->addSql('ALTER TABLE hash ADD CONSTRAINT FK_D1B862B8F5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('CREATE INDEX IDX_D1B862B8F5A26FD9 ON hash (id_member_id)');
        $this->addSql('ALTER TABLE login_credentials ADD id_member_id INT NOT NULL, DROP first_name, DROP last_name');
        $this->addSql('ALTER TABLE login_credentials ADD CONSTRAINT FK_487594BEF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_487594BEF5A26FD9 ON login_credentials (id_member_id)');
        $this->addSql('ALTER TABLE trick ADD id_member_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF5A26FD9 FOREIGN KEY (id_member_id) REFERENCES member (id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EF5A26FD9 ON trick (id_member_id)');
    }
}
