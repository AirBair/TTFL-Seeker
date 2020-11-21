<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201121103323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding ManyToOne link between FantasyUser & FantasyTeam & fantasyPoints attribute on FantasyPick + rename rank/ranking to fantasyRank';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick ADD fantasy_points INT NOT NULL');
        $this->addSql('ALTER TABLE fantasy_team_ranking CHANGE rank fantasy_rank INT NOT NULL');
        $this->addSql('ALTER TABLE fantasy_user ADD fantasy_team_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fantasy_user ADD CONSTRAINT FK_B4F987B09DBE749B FOREIGN KEY (fantasy_team_id) REFERENCES fantasy_team (id)');
        $this->addSql('CREATE INDEX IDX_B4F987B09DBE749B ON fantasy_user (fantasy_team_id)');
        $this->addSql('ALTER TABLE fantasy_user_ranking CHANGE ranking fantasy_rank INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_pick DROP fantasy_points');
        $this->addSql('ALTER TABLE fantasy_team_ranking CHANGE fantasy_rank rank INT NOT NULL');
        $this->addSql('ALTER TABLE fantasy_user DROP FOREIGN KEY FK_B4F987B09DBE749B');
        $this->addSql('DROP INDEX IDX_B4F987B09DBE749B ON fantasy_user');
        $this->addSql('ALTER TABLE fantasy_user DROP fantasy_team_id');
        $this->addSql('ALTER TABLE fantasy_user_ranking CHANGE fantasy_rank ranking INT NOT NULL');
    }
}
