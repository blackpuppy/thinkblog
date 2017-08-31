#!/bin/bash

# ----------------------
# KUDU Deployment Script
# Version: 1.0.15
# ----------------------

# Helpers
# -------

exitWithMessageOnError () {
  if [ ! $? -eq 0 ]; then
    echo "An error has occurred during web site deployment."
    echo $1
    exit 1
  fi
}

# Prerequisites
# -------------

# Verify node.js installed
hash node 2>/dev/null
exitWithMessageOnError "Missing node.js executable, please install node.js, if already installed make sure it can be reached from current environment."

# Setup
# -----

SCRIPT_DIR="${BASH_SOURCE[0]%\\*}"
SCRIPT_DIR="${SCRIPT_DIR%/*}"
ARTIFACTS=$SCRIPT_DIR/../artifacts
KUDU_SYNC_CMD=${KUDU_SYNC_CMD//\"}

if [[ ! -n "$DEPLOYMENT_SOURCE" ]]; then
  DEPLOYMENT_SOURCE=$SCRIPT_DIR
fi

if [[ ! -n "$NEXT_MANIFEST_PATH" ]]; then
  NEXT_MANIFEST_PATH=$ARTIFACTS/manifest

  if [[ ! -n "$PREVIOUS_MANIFEST_PATH" ]]; then
    PREVIOUS_MANIFEST_PATH=$NEXT_MANIFEST_PATH
  fi
fi

if [[ ! -n "$DEPLOYMENT_TARGET" ]]; then
  DEPLOYMENT_TARGET=$ARTIFACTS/wwwroot
else
  KUDU_SERVICE=true
fi

if [[ ! -n "$KUDU_SYNC_CMD" ]]; then
  # Install kudu sync
  echo Installing Kudu Sync
  npm install kudusync -g --silent
  exitWithMessageOnError "npm failed"

  if [[ ! -n "$KUDU_SERVICE" ]]; then
    # In case we are running locally this is the correct location of kuduSync
    KUDU_SYNC_CMD=kuduSync
  else
    # In case we are running on kudu service this is the correct location of kuduSync
    KUDU_SYNC_CMD=$APPDATA/npm/node_modules/kuduSync/bin/kuduSync
  fi
fi

# PHP Helpers
# -----------

initializeDeploymentConfig() {
	if [ ! -e "$COMPOSER_ARGS" ]; then
    COMPOSER_ARGS="--no-interaction --prefer-dist --optimize-autoloader --no-progress --no-dev --verbose"
    echo "No COMPOSER_ARGS variable declared in App Settings, using the default settings"
  else
    echo "Using COMPOSER_ARGS variable declared in App Setting"
  fi
  echo "Composer settings: $COMPOSER_ARGS"
}

# Node Helpers
# ------------

selectNodeVersion () {
  if [[ -n "$KUDU_SELECT_NODE_VERSION_CMD" ]]; then
    SELECT_NODE_VERSION="$KUDU_SELECT_NODE_VERSION_CMD \"$DEPLOYMENT_SOURCE\" \"$DEPLOYMENT_TARGET\" \"$DEPLOYMENT_TEMP\""
    eval $SELECT_NODE_VERSION
    exitWithMessageOnError "select node version failed"

    if [[ -e "$DEPLOYMENT_TEMP/__nodeVersion.tmp" ]]; then
      NODE_EXE=`cat "$DEPLOYMENT_TEMP/__nodeVersion.tmp"`
      exitWithMessageOnError "getting node version failed"
    fi

    if [[ -e "$DEPLOYMENT_TEMP/__npmVersion.tmp" ]]; then
      NPM_JS_PATH=`cat "$DEPLOYMENT_TEMP/__npmVersion.tmp"`
      exitWithMessageOnError "getting npm version failed"
    fi

    if [[ ! -n "$NODE_EXE" ]]; then
      NODE_EXE=node
    fi

    NPM_CMD="\"$NODE_EXE\" \"$NPM_JS_PATH\""
  else
    NPM_CMD=npm
    NODE_EXE=node
  fi
}

##################################################################################################################################
# Deployment
# ----------

echo PHP deployment
echo "**** This is custom deployment ****"

# 1. KuduSync
if [[ "$IN_PLACE_DEPLOYMENT" -ne "1" ]]; then
  "$KUDU_SYNC_CMD" -v 50 -f "$DEPLOYMENT_SOURCE" -t "$DEPLOYMENT_TARGET" -n "$NEXT_MANIFEST_PATH" -p "$PREVIOUS_MANIFEST_PATH" -i ".git;.hg;.deployment;deploy.sh"
  exitWithMessageOnError "Kudu Sync failed"
fi

# 2.1 Install composer
if [ ! -e "D:\home\site\deployments\tools/composer.phar" ]; then
  echo "**** Installing composer ****"
  pushd "D:\home\site\deployments\tools"

  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
  php composer-setup.php --filename=composer
  php -r "unlink('composer-setup.php');"
  # mv composer.phar composer
  echo @php "%~dp0composer" %*>composer.bat

  popd
fi

# 2.2 Verify composer installed
# pushd "D:\home\site\deployments\tools"
hash /d/home/site/deployments/tools/composer 2>/dev/null
exitWithMessageOnError "Missing composer executable"
# popd

# 2.3 Initialize Composer Config
initializeDeploymentConfig

# 2.4 Use composer
echo "**** Installing composer packages ****"
if [ -e "$DEPLOYMENT_TARGET/composer.json" ]; then
  echo "Found composer.json"
  # cd "$DEPLOYMENT_TARGET"
  pushd "$DEPLOYMENT_TARGET"
  /d/home/site/deployments/tools/composer install $COMPOSER_ARGS
  exitWithMessageOnError "Composer install failed"
  popd
  # cd - > /dev/null
fi

# 2.5 Run Database Migrations/Seeding
# echo "**** Running Database Migrations/Seeding ****"
# vendor/bin/phinx migrate -c _phinx/phinx.php
# vendor/bin/phinx seed:run -c _phinx/phinx.php

# 3.1 Select node version
selectNodeVersion

# 3.2 Install npm packages
# echo "**** Installing npm packages ****"
# if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
#   cd "$DEPLOYMENT_TARGET"
#   eval $NPM_CMD install
#   exitWithMessageOnError "npm failed"
#   cd - > /dev/null
# fi

# 3.3 Install Yarn
echo "**** Verifying Yarn Install ****"
eval $NPM_CMD install install yarn -g

# 3.4 Install Yarn packages
echo "**** Installing Yarn Packages ****"
if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
  cd "$DEPLOYMENT_TARGET"
  yarn install
  exitWithMessageOnError "Yarn failed"
  cd - > /dev/null
fi

# 3.5 Build Assets
echo "**** Building Frontend Assets ****"
if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
  cd "$DEPLOYMENT_TARGET"
  # yarn dev
  eval $NPM_CMD run dev
  exitWithMessageOnError "Yarn failed"
  cd - > /dev/null
fi

##################################################################################################################################echo "Finished successfully."
echo "Finished successfully."
