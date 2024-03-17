<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201223112212 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Improve Fantasy Team & Player';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team ADD is_exotic_team TINYINT(1) NOT NULL, ADD fantasy_points INT NOT NULL, ADD fantasy_rank INT NOT NULL');
        $this->addSql('ALTER TABLE fantasy_user ADD is_exotic_user TINYINT(1) NOT NULL, ADD fantasy_points INT NOT NULL, ADD fantasy_rank INT NOT NULL');
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team DROP is_exotic_team, DROP fantasy_points, DROP fantasy_rank');
        $this->addSql('ALTER TABLE fantasy_user DROP is_exotic_user, DROP fantasy_points, DROP fantasy_rank');
    }
}
