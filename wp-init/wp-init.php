<?php
function render_var_dump($input)
{
    ob_start();
    var_dump($input);

    return ob_flush();
}

function execute($command, $ignore_exception = false)
{
    echo "Executing command '$command'.\n";
    $result_code = 0;
    $output = null;
    $result = exec($command, $output, $result_code);
    if (!$result && $result_code !== 0) {
        $message = "Command '" . $command . "' failed with exit code " . $result_code . "Output: " . render_var_dump($output);
        if ($ignore_exception) {
            echo $message . "\n";
        } else {
            throw new Exception($message);
        }
    }

    return $result;
}

$metadata_path = "/etc/clidata";
$sql_dump_file = "/etc/wp-dev-kit/restore/dump.sql";
$should_import_sql_dump = file_exists($sql_dump_file);
$sql_dump_imported_file = "$metadata_path/sql_dump_imported";
if ($should_import_sql_dump && !file_exists($sql_dump_imported_file)) {
    throw new Exception("File $sql_dump_file exists but $sql_dump_imported_file does not. SQL import should be executed.");
}

function wp_init_common()
{
    // TODO enable dev plugin
}

$site_title = getenv("SITE_TITLE") ?? "WP Dev Kit";
$site_root_url = getenv("SITE_ROOT_URL");
if (!$site_root_url) {
    throw new Exception("Environment variable SITE_ROOT_URL not set.");
}

$admin_user = getenv("ADMIN_USERNAME") ?? "admin-user";
$admin_pass = getenv("ADMIN_PASSWORD") ?? "pass";
$admin_mail = getenv("ADMIN_EMAIL") ?? "info@ducode.org";

if ($should_import_sql_dump) {
    $sql_dump_updated_file = $metadata_path . "/sql_dump_updated";
    if (file_exists($sql_dump_updated_file)) {
        echo "File " . $sql_dump_updated_file . " exists, so the dump is already successfully written and updated in the database.";
    } else {
        // This code is executed if an existing MySQL WordPress dump is found.
        wp_init_common();

        echo "Handle SQL dump commands.\n";

        // Retrieve the current site URL and do a full search & replace on the database.
        $site_url = execute("wp db query 'SELECT option_value FROM wp_options WHERE option_name=\"siteurl\"' --skip-column-names --allow-root");
        echo "Replacing all instances of $site_url with $site_root_url.\n";
        echo exec("wp search-replace '" . $site_url . "' '" . $site_root_url . "' --allow-root");

        file_put_contents($sql_dump_updated_file, "ok");
    }
}

// Setting up site with settings.
$settings_path = "/etc/wp-dev-kit/settings.json";
if (file_exists($settings_path)) {
    echo "Reading file $settings_path.\n";
    $settings = json_decode(file_get_contents($settings_path));
    foreach ($settings->disable_plugins ?? [] as $disable) {
        echo execute("wp plugin deactivate " . $disable . " --allow-root", true);
    }

    foreach ($settings->activate_plugins ?? [] as $activate) {
        echo execute("wp plugin activate " . $activate . " --allow-root", true);
    }

    foreach ($settings->install_plugins as $install) {
        echo execute("wp plugin install " . $install . " --activate --allow-root", true);
    }

    if (isset($settings->install_theme) && $settings->install_theme) {
        echo execute("wp theme install " . $settings->install_theme . " --activate --allow-root", true);
    }

    if (isset($settings->activate_theme) && $settings->activate_theme) {
        echo execute("wp theme activate " . $settings->activate_theme . " --allow-root", true);
    }

    if (isset($settings->options) && $settings->options) {
        foreach ($settings->options as $key => $value) {
            try {
                echo execute("wp option add ".$key." ".$value." --allow-root");
            } catch(Exception) {
                echo execute("wp option update ".$key." ".$value." --allow-root", true);
            }
        }
    }
}

// (Re)set the admin user here.
try {
    $current_user = execute("wp user get " . $admin_user . " --allow-root");
    // User exists.
    echo execute("wp user update " . $admin_user . " --user_pass=" . $admin_pass . " --allow-root");
} catch (Exception $ex) {
    // User doesn't exist.
    echo execute("wp user create " . $admin_user . " " . $admin_mail . " --user_pass=" . $admin_pass . " --role=administrator --allow-root");
}