<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223215741 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_64C19AA9F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, type_transaction_id INT DEFAULT NULL, nom_complet VARCHAR(255) NOT NULL, phone INT NOT NULL, cni INT NOT NULL, INDEX IDX_C74404557903E29B (type_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, type_transaction_id INT DEFAULT NULL, montant INT NOT NULL, date_depot DATE NOT NULL, date_retrait DATE NOT NULL, date_annulation DATE DEFAULT NULL, ttc INT NOT NULL, frais_etat INT NOT NULL, frais_system INT NOT NULL, frais_evoie INT NOT NULL, frais_retrait INT NOT NULL, code_transaction INT NOT NULL, INDEX IDX_723705D17903E29B (type_transaction_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_transaction (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404557903E29B FOREIGN KEY (type_transaction_id) REFERENCES type_transaction (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17903E29B FOREIGN KEY (type_transaction_id) REFERENCES type_transaction (id)');
        $this->addSql('ALTER TABLE user ADD agence_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D725330D ON user (agence_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404557903E29B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D17903E29B');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE type_transaction');
        $this->addSql('DROP INDEX IDX_8D93D649D725330D ON user');
        $this->addSql('ALTER TABLE user DROP agence_id');
    }
}
