<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919195731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meeting (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, first_team_id INT DEFAULT NULL, second_team_id INT DEFAULT NULL, meeting_date DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_F515E13933D1A3E7 (tournament_id), INDEX IDX_F515E1393AE0B452 (first_team_id), INDEX IDX_F515E1393E2E58C3 (second_team_id), UNIQUE INDEX tournament_team_meetings (tournament_id, first_team_id, second_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teams (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX teams (name, deleted_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournament (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME(6) NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', deleted_at DATETIME(6) DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_BD5FB8D92B36786B (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E13933D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournament (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E1393AE0B452 FOREIGN KEY (first_team_id) REFERENCES teams (id)');
        $this->addSql('ALTER TABLE meeting ADD CONSTRAINT FK_F515E1393E2E58C3 FOREIGN KEY (second_team_id) REFERENCES teams (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E13933D1A3E7');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E1393AE0B452');
        $this->addSql('ALTER TABLE meeting DROP FOREIGN KEY FK_F515E1393E2E58C3');
        $this->addSql('DROP TABLE meeting');
        $this->addSql('DROP TABLE teams');
        $this->addSql('DROP TABLE tournament');
    }
}
