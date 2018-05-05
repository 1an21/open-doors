<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180410114619 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE `lockkey` DROP FOREIGN KEY `FK_6FBC4B4495A3C5B0`; ALTER TABLE `lockkey` ADD CONSTRAINT `FK_6FBC4B4495A3C5B0` FOREIGN KEY (`rkey`) REFERENCES `rkey`(`id`) ON DELETE CASCADE ON UPDATE RESTRICT;");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("ALTER TABLE `lockkey` DROP FOREIGN KEY `FK_6FBC4B4495A3C5B0`; ALTER TABLE `lockkey` ADD CONSTRAINT `FK_6FBC4B4495A3C5B0` FOREIGN KEY (`rkey`) REFERENCES `rkey`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;");

    }
}
