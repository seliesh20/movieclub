<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211020052112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_meetings (id INT AUTO_INCREMENT NOT NULL, movie_list_id INT DEFAULT NULL, meeting_title VARCHAR(255) NOT NULL, meeting_date DATE NOT NULL, meeting_time TIME NOT NULL, status SMALLINT NOT NULL, UNIQUE INDEX UNIQ_9808C96F1D3854A5 (movie_list_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_meetings ADD CONSTRAINT FK_9808C96F1D3854A5 FOREIGN KEY (movie_list_id) REFERENCES movie_list (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movie_meetings');
    }
}
