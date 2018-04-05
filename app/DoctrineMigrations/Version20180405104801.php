<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180405104801 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("
CREATE TABLE `entrance` (
  `id` int(11) NOT NULL,
  `locks` int(11) DEFAULT NULL,
  `rkey` int(11) DEFAULT NULL,
  `entime` datetime NOT NULL,
  `result` enum('open','deny') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `entrance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rkey` (`rkey`),
  ADD KEY `locks` (`locks`);

ALTER TABLE `entrance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `entrance`
  ADD CONSTRAINT `FK_C8954FE695A3C5B0` FOREIGN KEY (`rkey`) REFERENCES `rkey` (`id`),
  ADD CONSTRAINT `FK_C8954FE6FC316D97` FOREIGN KEY (`locks`) REFERENCES `locks` (`id`);");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("DROP TABLE `entrance`");

    }
}
