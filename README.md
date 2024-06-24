# WP Dev Kit

WP Dev Kit is a simple frame for WordPress to make local development for themes and plugins easier. All necessary
components run inside Docker containers, using a single `docker-compose.yml` file.

## Running the dev environment

To run the dev environment, go to the root folder of this repository in the terminal and execute `docker compose up` (
given you have [Docker](https://www.docker.com/) installed). All the necessary containers will be downloaded and
started. Using these URLs, you can reach the applications:

- <http://localhost:8000>: the actual WordPress site.
- <http://localhost:8025>: MailPit (a simple application for testing email functionality locally)
- <http://localhost:8001>: phpMyAdmin (DB admin tool, by default log in with `root` and `root`)

You can also connect with the MySQL DB with your favourite DB tooling, by connecting with host `localhost` and
port `3306`.

When starting the site, admin user `admin-user` will be added with password `pass`. You can log in
here: <http://localhost:8000/wp-admin>.

### Setting up the local dev environment

To install, activate and disable plugins or themes, place them in the `settings.json` file. When starting the
application, this file will be
read to install or disable specific plugins. If you want to install plugins, pick the ID from the URL of the plugin
page (e.g. https://wordpress.org/plugins/wp-mail-smtp/ will be `wp-mail-smtp` and same story with themes where
URL https://wordpress.org/themes/twentysixteen/ will be `twentysixteen`).

You can also set options which need to be added / updated in the `wp_options` table. You can provide them as a key /
value object. Everytime the website starts, the options will be checked and added / updated.

### Restoring a "live" environment

Part of WordPress development is working with actual data. To make this possible, import functionality has been built.
To begin, make a complete dump of the MySQL database for the environment you want to import locally. Place it in
the `restore` folder and call it `dump.sql`. Make sure nothing important is in your local dev DB, BECAUSE ALL TABLES
WILL BE DELETED!

You can also restore the `plugins`, `uploads` and `themes` folders (all in the `wp-content` folder). Make sure to
archive these folders (name the zip files `plugins.zip`, `uploads.zip` and `themes.zip` and put the contents of these
folders in the root of the zip files) and place any of these zip files in the `restore` directory. When running the
application, a check will be made if any of these zip files exist and the contents will be extracted to the right
directory on your dev environment. Again, make sure to backup any important files because ALL FILES WILL BE OVERWRITTEN!