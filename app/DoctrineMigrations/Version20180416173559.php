<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180416173559 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("
        CREATE TRIGGER `delete_lk` AFTER DELETE ON `lockkey`
        FOR EACH ROW INSERT INTO log_lk ( msg, lock_name, lock_pass, tag ) 
        SELECT 'delete', l.lock_name, l.lock_pass, k.tag
        FROM lockkey lk 
        JOIN locks as l on OLD.locks=l.id
        JOIN rkey as k on OLD.rkey=k.id;
        
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
        FOR EACH ROW INSERT INTO log_mk Set msg='update', tag_mk=NEW.tag, old_tag=OLD.tag;");

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
        $this->addSql("
        DROP TRIGGER IF EXISTS `delete_lk`;
        DROP TRIGGER IF EXISTS `delete_mk`;
        DROP TRIGGER IF EXISTS `insert_lk`;
        DROP TRIGGER IF EXISTS `insert_mk`;
        DROP TRIGGER IF EXISTS `update_mk`;");

    }
}
