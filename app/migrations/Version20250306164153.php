<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250306164153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE User ADD COLUMN username VARCHAR(255) NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todolist AS SELECT id, name, created_at, updated_at FROM todolist');
        $this->addSql('DROP TABLE todolist');
        $this->addSql('CREATE TABLE todolist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_DD4DF6DBA76ED395 FOREIGN KEY (user_id) REFERENCES User (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO todolist (id, name, created_at, updated_at) SELECT id, name, created_at, updated_at FROM __temp__todolist');
        $this->addSql('DROP TABLE __temp__todolist');
        $this->addSql('CREATE INDEX IDX_DD4DF6DBA76ED395 ON todolist (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__User AS SELECT id, email, roles, password, isVerified FROM User');
        $this->addSql('DROP TABLE User');
        $this->addSql('CREATE TABLE User (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, isVerified BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO User (id, email, roles, password, isVerified) SELECT id, email, roles, password, isVerified FROM __temp__User');
        $this->addSql('DROP TABLE __temp__User');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON User (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__todolist AS SELECT id, name, created_at, updated_at FROM todolist');
        $this->addSql('DROP TABLE todolist');
        $this->addSql('CREATE TABLE todolist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO todolist (id, name, created_at, updated_at) SELECT id, name, created_at, updated_at FROM __temp__todolist');
        $this->addSql('DROP TABLE __temp__todolist');
    }
}
