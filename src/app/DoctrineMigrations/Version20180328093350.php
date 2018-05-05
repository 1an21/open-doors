<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180328093350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("CREATE TABLE `ext_log_entries` (
                       `id` int(11) NOT NULL,
                      `action` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
                      `logged_at` datetime NOT NULL,
                      `object_id` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
                      `object_class` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
                      `version` int(11) NOT NULL,
                      `data` longtext COLLATE utf8_unicode_ci COMMENT '(DC2Type:array)',
                      `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;");
        $this->addSql("ALTER TABLE `ext_log_entries`
                      ADD PRIMARY KEY (`id`),
                      ADD KEY `log_class_lookup_idx` (`object_class`),
                      ADD KEY `log_date_lookup_idx` (`logged_at`),
                      ADD KEY `log_user_lookup_idx` (`username`),
                      ADD KEY `log_version_lookup_idx` (`object_id`,`object_class`,`version`);");
        $this->addSql("ALTER TABLE `ext_log_entries` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("DROP TABLE `ext_log_entries`");

    }
}
