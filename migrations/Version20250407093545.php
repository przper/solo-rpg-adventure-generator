<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250407093545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create `monster_shadowdark` table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE monster_shadowdark (
            id UUID NOT NULL,
            name VARCHAR(255) NOT NULL,
            challenge_rating NUMERIC(10, 3) NOT NULL,
            experience_points INT NOT NULL,
            hit_dice VARCHAR(255) DEFAULT NULL,
            total_hit_points INT DEFAULT NULL,
            armor_class INT NOT NULL DEFAULT 10,
            attributes JSON NOT NULL DEFAULT '[]'::json,
            attacks JSON NOT NULL DEFAULT '[]'::json,
            specials JSON NOT NULL DEFAULT '[]'::json,
            PRIMARY KEY(id)
        )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.hit_dice IS '(DC2Type:dice_stack)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE monster_shadowdark
        SQL);
    }
}
