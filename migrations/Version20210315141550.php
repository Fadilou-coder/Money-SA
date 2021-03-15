<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210315141550 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_transaction_agence ADD user_id INT DEFAULT NULL, ADD transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_transaction_agence ADD CONSTRAINT FK_75DD9339A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE type_transaction_agence ADD CONSTRAINT FK_75DD93392FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_75DD9339A76ED395 ON type_transaction_agence (user_id)');
        $this->addSql('CREATE INDEX IDX_75DD93392FC0CB0F ON type_transaction_agence (transaction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_transaction_agence DROP FOREIGN KEY FK_75DD9339A76ED395');
        $this->addSql('ALTER TABLE type_transaction_agence DROP FOREIGN KEY FK_75DD93392FC0CB0F');
        $this->addSql('DROP INDEX IDX_75DD9339A76ED395 ON type_transaction_agence');
        $this->addSql('DROP INDEX IDX_75DD93392FC0CB0F ON type_transaction_agence');
        $this->addSql('ALTER TABLE type_transaction_agence DROP user_id, DROP transaction_id');
    }
}
