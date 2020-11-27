<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201127221737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add full name attribute on NBA player & team';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team_ranking CHANGE ranking_at ranking_at DATE NOT NULL');
        $this->addSql('ALTER TABLE nba_player ADD full_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE nba_team ADD full_name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team_ranking CHANGE ranking_at ranking_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE nba_player DROP full_name');
        $this->addSql('ALTER TABLE nba_team DROP full_name');
    }
}
