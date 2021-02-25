<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223220341 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404557903E29B');
        $this->addSql('DROP INDEX IDX_C74404557903E29B ON client');
        $this->addSql('ALTER TABLE client DROP type_transaction_id');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D17903E29B');
        $this->addSql('DROP INDEX IDX_723705D17903E29B ON transaction');
        $this->addSql('ALTER TABLE transaction DROP type_transaction_id');
        $this->addSql('ALTER TABLE type_transaction ADD client_id INT DEFAULT NULL, ADD transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_transaction ADD CONSTRAINT FK_392ED24019EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE type_transaction ADD CONSTRAINT FK_392ED2402FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transaction (id)');
        $this->addSql('CREATE INDEX IDX_392ED24019EB6921 ON type_transaction (client_id)');
        $this->addSql('CREATE INDEX IDX_392ED2402FC0CB0F ON type_transaction (transaction_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD type_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404557903E29B FOREIGN KEY (type_transaction_id) REFERENCES type_transaction (id)');
        $this->addSql('CREATE INDEX IDX_C74404557903E29B ON client (type_transaction_id)');
        $this->addSql('ALTER TABLE transaction ADD type_transaction_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D17903E29B FOREIGN KEY (type_transaction_id) REFERENCES type_transaction (id)');
        $this->addSql('CREATE INDEX IDX_723705D17903E29B ON transaction (type_transaction_id)');
        $this->addSql('ALTER TABLE type_transaction DROP FOREIGN KEY FK_392ED24019EB6921');
        $this->addSql('ALTER TABLE type_transaction DROP FOREIGN KEY FK_392ED2402FC0CB0F');
        $this->addSql('DROP INDEX IDX_392ED24019EB6921 ON type_transaction');
        $this->addSql('DROP INDEX IDX_392ED2402FC0CB0F ON type_transaction');
        $this->addSql('ALTER TABLE type_transaction DROP client_id, DROP transaction_id');
    }
}
