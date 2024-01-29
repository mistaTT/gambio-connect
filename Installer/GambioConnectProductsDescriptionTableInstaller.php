<?php

namespace GXModules\Makaira\GambioConnect\Installer;

use Doctrine\DBAL\Connection;
use GXModules\Makaira\GambioConnect\Installer\GambioConnectTableInstallerInterface;

class GambioConnectProductsDescriptionTableInstaller implements GambioConnectTableInstallerInterface
{

    public static function install(Connection $connection): void
    {
        $connection->executeStatement("
            CREATE TRIGGER makaira_connect_product_description_create_trigger AFTER INSERT on products_description
            FOR EACH ROW
            CALL makairaChange(NEW.products_id, 'product')
        ");
        
        $connection->executeStatement("
            CREATE TRIGGER makaira_connect_product_description_update_trigger AFTER UPDATE on products_description
            FOR EACH ROW
            CALL makairaChange(NEW.products_id, 'product')
        ");
        
        $connection->executeStatement("
            CREATE TRIGGER makaira_connect_product_description_delete_trigger AFTER DELETE on products_description
            FOR EACH ROW
            CALL makairaChange(OLD.products_id, 'product')
        ");
    }

    public static function uninstall(Connection $connection): void
    {
        $connection->executeStatement("DROP TRIGGER IF EXISTS makaira_connect_product_description_create_trigger");
        
        $connection->executeStatement("DROP TRIGGER IF EXISTS makaira_connect_product_description_update_trigger");
        
        $connection->executeStatement("DROP TRIGGER IF EXISTS makaira_connect_product_description_delete_trigger");
    }
}