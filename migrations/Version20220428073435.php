<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220428073435 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo CHANGE is_completed is_completed TINYINT(1) NOT NULL');
        $this->addSql('INSERT INTO priority (title, power) VALUES ("Niski", 1), ("Średni", 2), ("Wysoki", 3)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE todo CHANGE is_completed is_completed TINYINT(1) DEFAULT NULL');
    }
}
