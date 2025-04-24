<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424131622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD approved_at DATETIME DEFAULT NULL, ADD rejected_at DATETIME DEFAULT NULL, ADD approved_by_id INT DEFAULT NULL, ADD rejected_by_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D6492D234F6A FOREIGN KEY (approved_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD CONSTRAINT FK_8D93D649CBF05FC9 FOREIGN KEY (rejected_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D6492D234F6A ON user (approved_by_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649CBF05FC9 ON user (rejected_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D6492D234F6A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CBF05FC9
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D6492D234F6A ON user
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649CBF05FC9 ON user
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP approved_at, DROP rejected_at, DROP approved_by_id, DROP rejected_by_id
        SQL);
    }
}
