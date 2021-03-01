<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210301111933 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agence (id INT AUTO_INCREMENT NOT NULL, compte_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, blocage TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_64C19AA9F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom_complet VARCHAR(255) NOT NULL, phone INT NOT NULL, cni VARCHAR(255) DEFAULT NULL, blocage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE compte (id INT AUTO_INCREMENT NOT NULL, num_compte VARCHAR(255) NOT NULL, solde INT NOT NULL, blocage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE depot (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, compte_id INT DEFAULT NULL, date_depot DATE NOT NULL, montant INT NOT NULL, INDEX IDX_47948BBCA76ED395 (user_id), INDEX IDX_47948BBCF2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, blocage TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, user_depot_id INT DEFAULT NULL, user_retrait_id INT DEFAULT NULL, client_retrait_id INT DEFAULT NULL, client_envoie_id INT DEFAULT NULL, montant INT NOT NULL, date_depot DATE DEFAULT NULL, date_retrait DATE DEFAULT NULL, date_annulation DATE DEFAULT NULL, ttc INT NOT NULL, frais_etat INT NOT NULL, frais_system INT NOT NULL, frais_evoie INT DEFAULT NULL, frais_retrait INT DEFAULT NULL, code_transaction VARCHAR(255) NOT NULL, INDEX IDX_723705D1659D30DE (user_depot_id), INDEX IDX_723705D1D99F8396 (user_retrait_id), INDEX IDX_723705D1EEAC783B (client_retrait_id), INDEX IDX_723705D1A97CE472 (client_envoie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profil_id INT DEFAULT NULL, agence_id INT DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, cni VARCHAR(255) NOT NULL, avatar LONGBLOB DEFAULT NULL, adresse VARCHAR(255) NOT NULL, blocage TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), INDEX IDX_8D93D649275ED078 (profil_id), INDEX IDX_8D93D649D725330D (agence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agence ADD CONSTRAINT FK_64C19AA9F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1659D30DE FOREIGN KEY (user_depot_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1D99F8396 FOREIGN KEY (user_retrait_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1EEAC783B FOREIGN KEY (client_retrait_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A97CE472 FOREIGN KEY (client_envoie_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649275ED078 FOREIGN KEY (profil_id) REFERENCES profil (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D725330D FOREIGN KEY (agence_id) REFERENCES agence (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D725330D');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1EEAC783B');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1A97CE472');
        $this->addSql('ALTER TABLE agence DROP FOREIGN KEY FK_64C19AA9F2C56620');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCF2C56620');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649275ED078');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCA76ED395');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1659D30DE');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D1D99F8396');
        $this->addSql('DROP TABLE agence');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE compte');
        $this->addSql('DROP TABLE depot');
        $this->addSql('DROP TABLE profil');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE user');
    }
}
