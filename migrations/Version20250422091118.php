<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250422091118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D32FC0CB0F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_A3C664D32FC0CB0F ON subscription
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription DROP transaction_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD subscription_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD CONSTRAINT FK_723705D19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_723705D19A1887DC ON transaction (subscription_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD transaction_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D32FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_A3C664D32FC0CB0F ON subscription (transaction_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19A1887DC
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_723705D19A1887DC ON transaction
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP subscription_id
        SQL);
    }
}
