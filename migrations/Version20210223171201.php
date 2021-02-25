<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210223171201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot ADD user_id INT DEFAULT NULL, ADD compte_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE depot ADD CONSTRAINT FK_47948BBCF2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_47948BBCA76ED395 ON depot (user_id)');
        $this->addSql('CREATE INDEX IDX_47948BBCF2C56620 ON depot (compte_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498510D4DE');
        $this->addSql('DROP INDEX IDX_8D93D6498510D4DE ON user');
        $this->addSql('ALTER TABLE user DROP depot_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCA76ED395');
        $this->addSql('ALTER TABLE depot DROP FOREIGN KEY FK_47948BBCF2C56620');
        $this->addSql('DROP INDEX IDX_47948BBCA76ED395 ON depot');
        $this->addSql('DROP INDEX IDX_47948BBCF2C56620 ON depot');
        $this->addSql('ALTER TABLE depot DROP user_id, DROP compte_id');
        $this->addSql('ALTER TABLE user ADD depot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498510D4DE FOREIGN KEY (depot_id) REFERENCES depot (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498510D4DE ON user (depot_id)');
    }
}
