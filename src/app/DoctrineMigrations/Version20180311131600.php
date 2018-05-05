<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180311131600 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
         // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `locks` ADD UNIQUE( `lock_name`);');
        $this->addSql('ALTER TABLE `rkey` CHANGE `description` `description` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');


    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
       // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `locks` DROP INDEX `lock_name`;');
        $this->addSql('ALTER TABLE `rkey` CHANGE `description` `description` VARCHAR(25) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
        

    }
}
