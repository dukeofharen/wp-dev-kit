#!/bin/bash
set -eu

function restore() {
  WP_CONTENT_PATH="/var/www/html/wp-content"
  FOLDER="$WP_CONTENT_PATH/$1"
  ORIGIN_FOLDER="/etc/wp-dev-kit/restore/$1"
  ZIP_FILE="/etc/wp-dev-kit/restore/$1.zip"
  CHECK_PATH="/etc/clidata/$1_restored"
  if [ -f "$CHECK_PATH" ]; then
    echo "File $CHECK_PATH found so $1 is already restored."
    return
  fi

  if [ -f "$ZIP_FILE" ]; then
    echo "File $ZIP_FILE found; restoring it to $FOLDER."
    if [ ! -d "$FOLDER" ]; then
      echo "Creating folder $FOLDER"
      mkdir "$FOLDER"
    fi

    unzip -od "$FOLDER" "$ZIP_FILE"
    touch "$CHECK_PATH"
    chown -R www-data:www-data "$FOLDER"
  elif [ -d "$ORIGIN_FOLDER" ]; then
    echo "Copying folder $ORIGIN_FOLDER to $WP_CONTENT_PATH."
    cp -r "$ORIGIN_FOLDER" "$WP_CONTENT_PATH"
    touch "$CHECK_PATH"
    chown -R www-data:www-data "$FOLDER"
  fi
}

restore "uploads"
restore "plugins"
restore "themes"

sleep 10
php /wp-init/wp-init.php