<?php

namespace core;

class Environment
{
  public static function load(string $dir)
  {
    if (!file_exists($dir . '/.env')) {
      return false;
    }

    $lines = file($dir . '/.env');
    foreach ($lines as $line) {
      $line = trim($line);
      $line ? putenv($line) : '';
    }
  }
}
