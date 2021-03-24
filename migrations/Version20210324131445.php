<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324131445 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE type_transaction_agence');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_transaction_agence (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, transaction_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, part VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, archiver TINYINT(1) NOT NULL, INDEX IDX_75DD9339A76ED395 (user_id), INDEX IDX_75DD93392FC0CB0F (transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE type_transaction_agence ADD CONSTRAINT FK_75DD93392FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('ALTER TABLE type_transaction_agence ADD CONSTRAINT FK_75DD9339A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }
}
