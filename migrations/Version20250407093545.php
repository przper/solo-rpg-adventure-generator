<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250407093545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE monster_shadowdark (
            id UUID NOT NULL,
            name VARCHAR(255) NOT NULL,
            challenge_rating NUMERIC(10, 0) NOT NULL,
            experience_points INT NOT NULL,
            hit_dice VARCHAR(255) NOT NULL,
            armor_class INT NOT NULL,
            attacks TEXT NOT NULL,
            PRIMARY KEY(id)
        )
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.id IS '(DC2Type:uuid)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.hit_dice IS '(DC2Type:dice_stack)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.attacks IS '(DC2Type:simple_array)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE monster_shadowdark
        SQL);
    }
}
