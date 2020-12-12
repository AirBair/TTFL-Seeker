<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201212101316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding isAllowedInExoticLeague attribute in NbaPlayer';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_player ADD is_allowed_in_exotic_league TINYINT(1) NOT NULL');
        $this->addSql('UPDATE nba_player SET is_allowed_in_exotic_league=1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_player DROP is_allowed_in_exotic_league');
    }
}
