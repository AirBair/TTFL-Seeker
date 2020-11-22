<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201122170948 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Distinction between regular season and the playoffs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick ADD is_playoffs TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fantasy_team_ranking ADD is_playoffs TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fantasy_user_ranking ADD is_playoffs TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE nba_game ADD is_playoffs TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick DROP is_playoffs');
        $this->addSql('ALTER TABLE fantasy_team_ranking DROP is_playoffs');
        $this->addSql('ALTER TABLE fantasy_user_ranking DROP is_playoffs');
        $this->addSql('ALTER TABLE nba_game DROP is_playoffs');
    }
}
