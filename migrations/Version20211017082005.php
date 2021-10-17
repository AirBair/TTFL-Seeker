<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20211017082005 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add boolean to enable/disabled synchronization on users/teams';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team ADD is_synchronization_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fantasy_user ADD is_synchronization_active TINYINT(1) NOT NULL');
        $this->addSql('UPDATE fantasy_team SET is_synchronization_active=1 WHERE 1');
        $this->addSql('UPDATE fantasy_user SET is_synchronization_active=1 WHERE 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fantasy_team DROP is_synchronization_active');
        $this->addSql('ALTER TABLE fantasy_user DROP is_synchronization_active');
    }
}
