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
# Post Deployment
# ---------------

echo "This is post deployment"

# 2.4 Run Database Migrations/Seeding
# vendor/bin/phinx migrate -c _phinx/phinx.php
# vendor/bin/phinx seed:run -c _phinx/phinx.php

# 3.1 Select node version
selectNodeVersion

# 3.3 Install Yarn
echo "Verifying Yarn Install."
eval $NPM_CMD install install yarn -g

# 3.4 Install Yarn packages
echo "Installing Yarn Packages."
if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
  cd "$DEPLOYMENT_TARGET"
  yarn install
  exitWithMessageOnError "Yarn failed"
  cd - > /dev/null
fi

# 3.5 Build Assets
echo "Building Frontend Assets."
if [ -e "$DEPLOYMENT_TARGET/package.json" ]; then
  cd "$DEPLOYMENT_TARGET"
  yarn dev
  exitWithMessageOnError "Yarn failed"
  cd - > /dev/null
fi

##################################################################################################################################echo "Finished successfully."
echo "Finished successfully."
