<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021113815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie_meeting_email (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status SMALLINT NOT NULL, email_time DATETIME DEFAULT NULL, invitation_time DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_3050A983A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie_meeting_email ADD CONSTRAINT FK_3050A983A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE movie_meetings ADD movie_meeting_email_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_meetings ADD CONSTRAINT FK_9808C96F919C368D FOREIGN KEY (movie_meeting_email_id) REFERENCES movie_meeting_email (id)');
        $this->addSql('CREATE INDEX IDX_9808C96F919C368D ON movie_meetings (movie_meeting_email_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_meetings DROP FOREIGN KEY FK_9808C96F919C368D');
        $this->addSql('DROP TABLE movie_meeting_email');
        $this->addSql('DROP INDEX IDX_9808C96F919C368D ON movie_meetings');
        $this->addSql('ALTER TABLE movie_meetings DROP movie_meeting_email_id');
    }
}
