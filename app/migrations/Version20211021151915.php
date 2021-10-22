<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021151915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_meeting_email ADD movie_meetings_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_meeting_email ADD CONSTRAINT FK_3050A9833129AD80 FOREIGN KEY (movie_meetings_id) REFERENCES movie_meetings (id)');
        $this->addSql('ALTER TABLE movie_meeting_email ADD CONSTRAINT FK_3050A983A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_3050A9833129AD80 ON movie_meeting_email (movie_meetings_id)');
        $this->addSql('CREATE INDEX IDX_3050A983A76ED395 ON movie_meeting_email (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_meeting_email DROP FOREIGN KEY FK_3050A9833129AD80');
        $this->addSql('ALTER TABLE movie_meeting_email DROP FOREIGN KEY FK_3050A983A76ED395');
        $this->addSql('DROP INDEX IDX_3050A9833129AD80 ON movie_meeting_email');
        $this->addSql('DROP INDEX IDX_3050A983A76ED395 ON movie_meeting_email');
        $this->addSql('ALTER TABLE movie_meeting_email DROP movie_meetings_id, DROP user_id');
    }
}
