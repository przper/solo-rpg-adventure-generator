<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250407101551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add vector support for similarity search to `monster_shadowdark` table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE EXTENSION IF NOT EXISTS vector');

        $this->addSql(<<<'SQL'
            ALTER TABLE monster_shadowdark ADD description TEXT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monster_shadowdark ADD vector_embedding vector(1536) DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN monster_shadowdark.vector_embedding IS '(DC2Type:vector)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX vector_embedding_idx ON monster_shadowdark USING hnsw (vector_embedding vector_l2_ops)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX vector_embedding_idx
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monster_shadowdark DROP description
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE monster_shadowdark DROP vector_embedding
        SQL);
        $this->addSql('DROP EXTENSION IF EXISTS vector');
    }
}
