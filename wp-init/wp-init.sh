#!/bin/bash
set -eu
if [ -d "/var/www/html/wp-content/uploads" ]; then chown -R www-data:www-data /var/www/html/wp-content/uploads; fi

sleep 10
php /wp-init/wp-init.php

#RESTORE_SITE_PATH="/etc/wp-starter/restore/site"
#SITE_RESTORED_CHECK_PATH="/etc/clidata/site_restored"
#SITE_ROOT_PATH="/var/www/html"
#if [ -d "$RESTORE_SITE_PATH" ]; then
#  if [ -f "$SITE_RESTORED_CHECK_PATH" ]; then
#    echo "Site already restored."
#  else
#    echo "Restoring path $RESTORE_SITE_PATH"
#    HTACCESS_PATH="$RESTORE_SITE_PATH/.htaccess"
#    if [ -f "$HTACCESS_PATH" ]; then
#      echo "Restoring $HTACCESS_PATH"
#      cp $HTACCESS_PATH $SITE_ROOT_PATH
#    fi
#
#    RESTORE_WP_CONTENT_PATH="$RESTORE_SITE_PATH/wp-content"
#    if [ -d "$RESTORE_WP_CONTENT_PATH" ]; then
#      echo "Restoring $RESTORE_WP_CONTENT_PATH"
#      WP_CONTENT_PATH="$SITE_ROOT_PATH/wp-content"
#      cp -r $RESTORE_WP_CONTENT_PATH/plugins $WP_CONTENT_PATH/plugins
#      cp -r $RESTORE_WP_CONTENT_PATH/uploads $WP_CONTENT_PATH/uploads
#    fi
#
#    touch $SITE_RESTORED_CHECK_PATH
#  fi
#fi
