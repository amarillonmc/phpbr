#!/bin/bash
tar -cvzf dts.tgz --exclude=config.inc.php --exclude=JudgeOnline --exclude=dts.tgz --exclude=./.* --exclude=gamedata/bak/* --exclude=gamedata/cache/mixitem_1.php .
