<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423135113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE audio (duration TIME DEFAULT NULL, format INT DEFAULT 0 NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE author_document (author_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_37F9A0C3F675F31B (author_id), INDEX IDX_37F9A0C3C33F7837 (document_id), PRIMARY KEY(author_id, document_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, state INT DEFAULT 1 NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_2246507BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE book (isbn VARCHAR(20) NOT NULL, pages INT DEFAULT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE copy (id INT AUTO_INCREMENT NOT NULL, state INT DEFAULT 1 NOT NULL, physical_condition INT DEFAULT 1 NOT NULL, document_id INT DEFAULT NULL, loan_id INT DEFAULT NULL, INDEX IDX_4DBABB82C33F7837 (document_id), INDEX IDX_4DBABB82CE73868F (loan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE copy_basket (copy_id INT NOT NULL, basket_id INT NOT NULL, INDEX IDX_87F83FC0A8752772 (copy_id), INDEX IDX_87F83FC01BE1FB52 (basket_id), PRIMARY KEY(copy_id, basket_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, langage VARCHAR(255) NOT NULL, thumbnail_url LONGTEXT NOT NULL, publication_date DATETIME DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE document_user (document_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2A275ADAC33F7837 (document_id), INDEX IDX_2A275ADAA76ED395 (user_id), PRIMARY KEY(document_id, user_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE loan (id INT AUTO_INCREMENT NOT NULL, start_at DATETIME NOT NULL, withdrawal_at DATETIME DEFAULT NULL, back_at DATETIME DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_C5D30D03A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE membership (id INT AUTO_INCREMENT NOT NULL, document LONGTEXT NOT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_86FFD285A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, object VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, sent_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE notification_user (notification_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_35AF9D73EF1A9D84 (notification_id), INDEX IDX_35AF9D73A76ED395 (user_id), PRIMARY KEY(notification_id, user_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE periodical (frequency INT DEFAULT 0 NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE publisher (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) DEFAULT NULL, lastname VARCHAR(255) DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE publisher_document (publisher_id INT NOT NULL, document_id INT NOT NULL, INDEX IDX_4F37F3AB40C86FCE (publisher_id), INDEX IDX_4F37F3ABC33F7837 (document_id), PRIMARY KEY(publisher_id, document_id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, periodicity INT DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, amount NUMERIC(5, 2) NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, subscription_id INT DEFAULT NULL, INDEX IDX_723705D1A76ED395 (user_id), INDEX IDX_723705D19A1887DC (subscription_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE video (duration TIME DEFAULT NULL, format INT DEFAULT 0 NOT NULL, id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE audio ADD CONSTRAINT FK_187D3695BF396750 FOREIGN KEY (id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE basket ADD CONSTRAINT FK_2246507BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book ADD CONSTRAINT FK_CBE5A331BF396750 FOREIGN KEY (id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy ADD CONSTRAINT FK_4DBABB82C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy ADD CONSTRAINT FK_4DBABB82CE73868F FOREIGN KEY (loan_id) REFERENCES loan (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy_basket ADD CONSTRAINT FK_87F83FC0A8752772 FOREIGN KEY (copy_id) REFERENCES copy (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy_basket ADD CONSTRAINT FK_87F83FC01BE1FB52 FOREIGN KEY (basket_id) REFERENCES basket (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document_user ADD CONSTRAINT FK_2A275ADAC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document_user ADD CONSTRAINT FK_2A275ADAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan ADD CONSTRAINT FK_C5D30D03A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membership ADD CONSTRAINT FK_86FFD285A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE periodical ADD CONSTRAINT FK_522160B9BF396750 FOREIGN KEY (id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publisher_document ADD CONSTRAINT FK_4F37F3AB40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publisher_document ADD CONSTRAINT FK_4F37F3ABC33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction ADD CONSTRAINT FK_723705D19A1887DC FOREIGN KEY (subscription_id) REFERENCES subscription (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CBF396750 FOREIGN KEY (id) REFERENCES document (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE audio DROP FOREIGN KEY FK_187D3695BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3C33F7837
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE basket DROP FOREIGN KEY FK_2246507BA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy DROP FOREIGN KEY FK_4DBABB82C33F7837
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy DROP FOREIGN KEY FK_4DBABB82CE73868F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy_basket DROP FOREIGN KEY FK_87F83FC0A8752772
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE copy_basket DROP FOREIGN KEY FK_87F83FC01BE1FB52
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document_user DROP FOREIGN KEY FK_2A275ADAC33F7837
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE document_user DROP FOREIGN KEY FK_2A275ADAA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE loan DROP FOREIGN KEY FK_C5D30D03A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE membership DROP FOREIGN KEY FK_86FFD285A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE periodical DROP FOREIGN KEY FK_522160B9BF396750
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publisher_document DROP FOREIGN KEY FK_4F37F3AB40C86FCE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE publisher_document DROP FOREIGN KEY FK_4F37F3ABC33F7837
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE transaction DROP FOREIGN KEY FK_723705D19A1887DC
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CBF396750
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE audio
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE author
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE author_document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE basket
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE book
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE copy
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE copy_basket
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE document_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE loan
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE membership
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE notification_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE periodical
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE publisher
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE publisher_document
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE subscription
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE transaction
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE video
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
