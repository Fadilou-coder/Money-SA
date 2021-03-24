<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210324135647 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commission');
        $this->addSql('DROP TABLE transaction_agence');
        $this->addSql('ALTER TABLE transaction ADD agence_retrait_id INT DEFAULT NULL, ADD agence_envoi_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1BA1790A5 FOREIGN KEY (agence_retrait_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11E1FEC78 FOREIGN KEY (agence_envoi_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_723705D1BA1790A5 ON transaction (agence_retrait_id)');
        $this->addSql('CREATE INDEX IDX_723705D11E1FEC78 ON transaction (agence_envoi_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commission (id INT AUTO_INCREMENT NOT NULL, agence_id INT DEFAULT NULL, date DATE NOT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, montant VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_1C650158D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE transaction_agence (transaction_id INT NOT NULL, agence_id INT NOT NULL, INDEX IDX_12BCE8E92FC0CB0F (transaction_id), INDEX IDX_12BCE8E9D725330D (agence_id), PRIMARY KEY(transaction_id, agence_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commission ADD CONSTRAINT FK_1C650158D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('ALTER TABLE transaction_agence ADD CONSTRAINT FK_12BCE8E92FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction_agence ADD CONSTRAINT FK_12BCE8E9D725330D FOREIGN KEY (agence_id) REFERENCES agence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1BA1790A5');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11E1FEC78');
        $this->addSql('DROP INDEX IDX_723705D1BA1790A5 ON transaction');
        $this->addSql('DROP INDEX IDX_723705D11E1FEC78 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP agence_retrait_id, DROP agence_envoi_id');
    }
}
