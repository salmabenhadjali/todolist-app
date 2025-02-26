<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250225205118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , is_completed BOOLEAN NOT NULL, todoList_id INTEGER DEFAULT NULL, parentItem_id INTEGER DEFAULT NULL, CONSTRAINT FK_1F1B251E62AB5DB6 FOREIGN KEY (todoList_id) REFERENCES todolist (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1F1B251E6BF24F87 FOREIGN KEY (parentItem_id) REFERENCES item (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1F1B251E62AB5DB6 ON item (todoList_id)');
        $this->addSql('CREATE INDEX IDX_1F1B251E6BF24F87 ON item (parentItem_id)');
        $this->addSql('CREATE TABLE todolist (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE todolist');
    }
}
