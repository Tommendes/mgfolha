<?php
$time - time();
$exec = shell_exec("git add .");
echo $exec;
$exec = shell_exec("git commit -m 'web$time files before pull'");
echo $exec;
$exec = shell_exec("git push");
echo $exec;
$exec = shell_exec("git pull origin master 2>&1");
echo $exec;
 