<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201128160129 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Initial Migration.';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE fantasy_pick (id INT AUTO_INCREMENT NOT NULL, fantasy_user_id INT NOT NULL, nba_player_id VARCHAR(255) NOT NULL, season INT NOT NULL, is_playoffs TINYINT(1) NOT NULL, picked_at DATE NOT NULL, fantasy_points INT NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_A0A75E6213BC7FA0 (fantasy_user_id), INDEX IDX_A0A75E6299A217B7 (nba_player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fantasy_team (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FD8AF7E65E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fantasy_team_ranking (id INT AUTO_INCREMENT NOT NULL, fantasy_team_id INT NOT NULL, season INT NOT NULL, is_playoffs TINYINT(1) NOT NULL, fantasy_points INT NOT NULL, fantasy_rank INT NOT NULL, ranking_at DATE NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_1F19534E9DBE749B (fantasy_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fantasy_user (id INT AUTO_INCREMENT NOT NULL, fantasy_team_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, ttfl_id INT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) DEFAULT NULL, registered_at DATETIME NOT NULL, last_login_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B4F987B0F85E0677 (username), INDEX IDX_B4F987B09DBE749B (fantasy_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fantasy_user_ranking (id INT AUTO_INCREMENT NOT NULL, fantasy_user_id INT NOT NULL, season INT NOT NULL, is_playoffs TINYINT(1) NOT NULL, fantasy_points INT NOT NULL, fantasy_rank INT NOT NULL, ranking_at DATE NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_B86D3C7313BC7FA0 (fantasy_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nba_game (id VARCHAR(255) NOT NULL, local_nba_team_id VARCHAR(255) NOT NULL, visitor_nba_team_id VARCHAR(255) NOT NULL, season INT NOT NULL, is_playoffs TINYINT(1) NOT NULL, game_day DATE NOT NULL, scheduled_at DATETIME DEFAULT NULL, local_score INT DEFAULT NULL, visitor_score INT DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_C7EE5A59A420D96C (local_nba_team_id), INDEX IDX_C7EE5A59954637D5 (visitor_nba_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nba_player (id VARCHAR(255) NOT NULL, nba_team_id VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, jersey VARCHAR(255) NOT NULL, is_injured TINYINT(1) NOT NULL, average_fantasy_points DOUBLE PRECISION DEFAULT NULL, past_year_fantasy_points DOUBLE PRECISION DEFAULT NULL, updated_at DATETIME NOT NULL, INDEX IDX_BE36172ACF29ABC2 (nba_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nba_stats_log (id INT AUTO_INCREMENT NOT NULL, nba_game_id VARCHAR(255) NOT NULL, nba_player_id VARCHAR(255) NOT NULL, nba_team_id VARCHAR(255) NOT NULL, points INT NOT NULL, assists INT NOT NULL, rebounds INT NOT NULL, steals INT NOT NULL, blocks INT NOT NULL, turnovers INT NOT NULL, field_goals INT NOT NULL, field_goals_attempts INT NOT NULL, three_points_field_goals INT NOT NULL, three_points_field_goals_attempts INT NOT NULL, free_throws INT NOT NULL, free_throws_attempts INT NOT NULL, minutes_played INT NOT NULL, has_won TINYINT(1) NOT NULL, fantasy_points INT NOT NULL, is_best_pick TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_E769AA062CAAA69 (nba_game_id), INDEX IDX_E769AA0699A217B7 (nba_player_id), INDEX IDX_E769AA06CF29ABC2 (nba_team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nba_team (id VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, nickname VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, tricode VARCHAR(255) NOT NULL, conference VARCHAR(255) NOT NULL, division VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fantasy_pick ADD CONSTRAINT FK_A0A75E6213BC7FA0 FOREIGN KEY (fantasy_user_id) REFERENCES fantasy_user (id)');
        $this->addSql('ALTER TABLE fantasy_pick ADD CONSTRAINT FK_A0A75E6299A217B7 FOREIGN KEY (nba_player_id) REFERENCES nba_player (id)');
        $this->addSql('ALTER TABLE fantasy_team_ranking ADD CONSTRAINT FK_1F19534E9DBE749B FOREIGN KEY (fantasy_team_id) REFERENCES fantasy_team (id)');
        $this->addSql('ALTER TABLE fantasy_user ADD CONSTRAINT FK_B4F987B09DBE749B FOREIGN KEY (fantasy_team_id) REFERENCES fantasy_team (id)');
        $this->addSql('ALTER TABLE fantasy_user_ranking ADD CONSTRAINT FK_B86D3C7313BC7FA0 FOREIGN KEY (fantasy_user_id) REFERENCES fantasy_user (id)');
        $this->addSql('ALTER TABLE nba_game ADD CONSTRAINT FK_C7EE5A59A420D96C FOREIGN KEY (local_nba_team_id) REFERENCES nba_team (id)');
        $this->addSql('ALTER TABLE nba_game ADD CONSTRAINT FK_C7EE5A59954637D5 FOREIGN KEY (visitor_nba_team_id) REFERENCES nba_team (id)');
        $this->addSql('ALTER TABLE nba_player ADD CONSTRAINT FK_BE36172ACF29ABC2 FOREIGN KEY (nba_team_id) REFERENCES nba_team (id)');
        $this->addSql('ALTER TABLE nba_stats_log ADD CONSTRAINT FK_E769AA062CAAA69 FOREIGN KEY (nba_game_id) REFERENCES nba_game (id)');
        $this->addSql('ALTER TABLE nba_stats_log ADD CONSTRAINT FK_E769AA0699A217B7 FOREIGN KEY (nba_player_id) REFERENCES nba_player (id)');
        $this->addSql('ALTER TABLE nba_stats_log ADD CONSTRAINT FK_E769AA06CF29ABC2 FOREIGN KEY (nba_team_id) REFERENCES nba_team (id)');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team_ranking DROP FOREIGN KEY FK_1F19534E9DBE749B');
        $this->addSql('ALTER TABLE fantasy_user DROP FOREIGN KEY FK_B4F987B09DBE749B');
        $this->addSql('ALTER TABLE fantasy_pick DROP FOREIGN KEY FK_A0A75E6213BC7FA0');
        $this->addSql('ALTER TABLE fantasy_user_ranking DROP FOREIGN KEY FK_B86D3C7313BC7FA0');
        $this->addSql('ALTER TABLE nba_stats_log DROP FOREIGN KEY FK_E769AA062CAAA69');
        $this->addSql('ALTER TABLE fantasy_pick DROP FOREIGN KEY FK_A0A75E6299A217B7');
        $this->addSql('ALTER TABLE nba_stats_log DROP FOREIGN KEY FK_E769AA0699A217B7');
        $this->addSql('ALTER TABLE nba_game DROP FOREIGN KEY FK_C7EE5A59A420D96C');
        $this->addSql('ALTER TABLE nba_game DROP FOREIGN KEY FK_C7EE5A59954637D5');
        $this->addSql('ALTER TABLE nba_player DROP FOREIGN KEY FK_BE36172ACF29ABC2');
        $this->addSql('ALTER TABLE nba_stats_log DROP FOREIGN KEY FK_E769AA06CF29ABC2');
        $this->addSql('DROP TABLE fantasy_pick');
        $this->addSql('DROP TABLE fantasy_team');
        $this->addSql('DROP TABLE fantasy_team_ranking');
        $this->addSql('DROP TABLE fantasy_user');
        $this->addSql('DROP TABLE fantasy_user_ranking');
        $this->addSql('DROP TABLE nba_game');
        $this->addSql('DROP TABLE nba_player');
        $this->addSql('DROP TABLE nba_stats_log');
        $this->addSql('DROP TABLE nba_team');
    }
}
