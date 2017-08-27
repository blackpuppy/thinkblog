#!/bin/sh

vendor/bin/phinx migrate -c _phinx/phinx.php
vendor/bin/phinx seed:run -c _phinx/phinx.php
yarn dev
