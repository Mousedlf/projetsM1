<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527104911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE team_member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE team_member (id INT NOT NULL, project_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6FFBDA1166D1F9C ON team_member (project_id)');
        $this->addSql('ALTER TABLE team_member ADD CONSTRAINT FK_6FFBDA1166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE project DROP team_members');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE team_member_id_seq CASCADE');
        $this->addSql('ALTER TABLE team_member DROP CONSTRAINT FK_6FFBDA1166D1F9C');
        $this->addSql('DROP TABLE team_member');
        $this->addSql('ALTER TABLE project ADD team_members VARCHAR(255) NOT NULL');
    }
}
