<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211021151224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_meeting_email DROP FOREIGN KEY FK_3050A983A76ED395');
        $this->addSql('DROP INDEX UNIQ_3050A983A76ED395 ON movie_meeting_email');
        $this->addSql('ALTER TABLE movie_meeting_email DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie_meeting_email ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie_meeting_email ADD CONSTRAINT FK_3050A983A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3050A983A76ED395 ON movie_meeting_email (user_id)');
    }
}
