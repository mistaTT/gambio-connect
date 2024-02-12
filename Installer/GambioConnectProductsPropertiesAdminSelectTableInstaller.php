<?php

namespace GXModules\Makaira\GambioConnect\Installer;

use CI_DB_query_builder;
use GXModules\Makaira\GambioConnect\Installer\GambioConnectTableInstallerInterface;

class GambioConnectProductsPropertiesAdminSelectTableInstaller implements GambioConnectTableInstallerInterface
{

    public static function install(CI_DB_query_builder $db): void
    {
        $db->query("
            CREATE TRIGGER makaira_connect_products_properties_admin_select_create_trigger AFTER INSERT on products_properties_admin_select
            FOR EACH ROW
            CALL makairaChange(NEW.products_id, 'product')
        ");

        $db->query("
            CREATE TRIGGER makaira_connect_products_properties_admin_select_update_trigger AFTER UPDATE on products_properties_admin_select
            FOR EACH ROW
            CALL makairaChange(NEW.products_id, 'product')
        ");

        $db->query("
            CREATE TRIGGER makaira_connect_products_properties_admin_select_delete_trigger AFTER DELETE on products_properties_admin_select
            FOR EACH ROW
            CALL makairaChange(OLD.products_id, 'product')
        ");
    }

    public static function uninstall(CI_DB_query_builder $db): void
    {
        $db->query("DROP TRIGGER IF EXISTS makaira_connect_products_properties_admin_select_create_trigger");

        $db->query("DROP TRIGGER IF EXISTS makaira_connect_products_properties_admin_select_update_trigger");

        $db->query("DROP TRIGGER IF EXISTS makaira_connect_products_properties_admin_select_delete_trigger");
    }
}
