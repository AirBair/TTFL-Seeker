<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201226105937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update Fantasy pick to retrieve a no pick from a fantasy user';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick ADD is_no_pick TINYINT(1) NOT NULL, CHANGE nba_player_id nba_player_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick DROP is_no_pick, CHANGE nba_player_id nba_player_id VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
