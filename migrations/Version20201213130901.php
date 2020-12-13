<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20201213130901 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adding logo & primaryColor on NbaTeam';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_team ADD logo_file_name VARCHAR(255) DEFAULT NULL, ADD primary_color VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE nba_team DROP logo_file_name, DROP primary_color');
    }
}
