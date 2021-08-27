<?php

use x\console;

require "../vendor/autoload.php";



console::init();
console::log("HELLO FROM CONSOLE");

echo console::readLogs();