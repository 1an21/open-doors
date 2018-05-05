<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180408202631 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("CREATE TRIGGER `delete_lk` AFTER DELETE ON `lockkey`
 FOR EACH ROW INSERT INTO log_lk ( msg, lock_name, lock_pass, tag ) 
        SELECT 'delete', l.lock_name, l.lock_pass, k.tag
        FROM lockkey lk 
        LEFT JOIN locks as l on OLD.locks=l.id
        LEFT JOIN rkey as k on OLD.rkey=k.id;
        
        CREATE TRIGGER `delete_mk` AFTER DELETE ON `masterkey`
        FOR EACH ROW INSERT INTO log_mk Set msg='delete', tag_mk = OLD.tag;
        
        CREATE TRIGGER `insert_lk` AFTER INSERT ON `lockkey`
        FOR EACH ROW INSERT INTO log_lk ( msg, lock_name, lock_pass, tag ) 
        SELECT 'insert', l.lock_name, l.lock_pass, k.tag 
        FROM lockkey lk 
        LEFT JOIN locks as l on lk.locks=l.id
        LEFT JOIN rkey as k on lk.rkey=k.id
        WHERE lk.locks=NEW.locks AND lk.rkey=NEW.rkey;
        
        CREATE TRIGGER `insert_mk` AFTER INSERT ON `masterkey`
        FOR EACH ROW INSERT INTO log_mk Set msg = 'insert', tag_mk = NEW.tag;
        
        CREATE TRIGGER `update_mk` AFTER UPDATE ON `masterkey`
        FOR EACH ROW INSERT INTO log_mk Set msg='update', tag_mk=NEW.tag, old_tag=OLD.tag;
        
        CREATE TABLE `log_lk` (
          `id` int(11) UNSIGNED NOT NULL,
          `msg` varchar(255) NOT NULL,
          `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `lock_name` varchar(255) NOT NULL,
          `lock_pass` varchar(255) NOT NULL,
          `tag` varchar(11) NOT NULL,
          `old_name` text,
          `old_pass` text,
          `old_tag` text
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
        
        ALTER TABLE `log_lk`
          ADD PRIMARY KEY (`id`);
        
        ALTER TABLE `log_lk`
          MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
          
         CREATE TABLE `log_mk` (
          `id` int(11) UNSIGNED NOT NULL,
          `msg` varchar(255) NOT NULL,
          `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
          `tag_mk` text NOT NULL,
          `old_tag` text
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;
        
        ALTER TABLE `log_mk`
          ADD PRIMARY KEY (`id`);
        
        ALTER TABLE `log_mk`
          MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
        ");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("DROP TRIGGER IF EXISTS `delete_lk`;
DROP TRIGGER IF EXISTS `delete_mk`;
DROP TRIGGER IF EXISTS `insert_lk`;
DROP TRIGGER IF EXISTS `insert_mk`;
DROP TRIGGER IF EXISTS `update_lk`;
DROP TRIGGER IF EXISTS `update_mk`;
DROP TABLE log_lk;
DROP TABLE log_mk;
");

    }
}
