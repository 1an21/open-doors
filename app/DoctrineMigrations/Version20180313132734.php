<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180313132734 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE masterkey');
        $this->addSql('ALTER TABLE rkey CHANGE description description VARCHAR(25) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95A3C5B0389B783 ON rkey (tag)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95A3C5B06DE44026 ON rkey (description)');
        $this->addSql('DROP INDEX lock_name ON locks');
        $this->addSql('ALTER TABLE locks CHANGE lock_name lock_name TEXT NOT NULL, CHANGE lock_pass lock_pass TEXT NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE masterkey (id INT AUTO_INCREMENT NOT NULL, tag TEXT NOT NULL COLLATE latin1_swedish_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE locks CHANGE lock_name lock_name VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci, CHANGE lock_pass lock_pass VARCHAR(30) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('CREATE UNIQUE INDEX lock_name ON locks (lock_name)');
        $this->addSql('DROP INDEX UNIQ_95A3C5B0389B783 ON rkey');
        $this->addSql('DROP INDEX UNIQ_95A3C5B06DE44026 ON rkey');
        $this->addSql('ALTER TABLE rkey CHANGE description description VARCHAR(25) DEFAULT NULL COLLATE utf8_unicode_ci');
    }
}
