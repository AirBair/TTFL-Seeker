<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220212134305 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Full name in TTFL property to NbaPlayer.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_player ADD full_name_in_ttfl VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_player DROP full_name_in_ttfl');
    }
}
