<?php

//putenv("ANSICON=on"); // colored console
//putenv("PHPUNIT_CMD_DEBUG=1"); // Show debug output for jbzoo/phpunit cmd() function

    // Symfony
    function dump($var, $isDie = true, $label = null)
    {
        if (null !== $label) {
            $label = PHP_EOL . '"' . $label . '" : '. PHP_EOL;
            if (defined('STDOUT')) {
                fwrite(STDOUT, $label);
            } else {
                echo '<div class="labeldump">'.$label.'</div>';
            }
        }

        $varDump = 0;
        if ($varDump && !is_array($var) && !is_object($var) && !is_callable($var)) {
            var_dump($var);
        } else {
            Symfony\Component\VarDumper\VarDumper::dump($var);
        }

        $trace     = debug_backtrace(false);
        $dirname   = pathinfo(dirname($trace[0]['file']), PATHINFO_BASENAME);
        $filename  = pathinfo($trace[0]['file'], PATHINFO_BASENAME);
        $line      = $trace[0]['line'];
        $callplace = "\"{$dirname}/{$filename}:{$line}\"";

        $message = '-------------' . PHP_EOL . 'Die ==> ' . $callplace . PHP_EOL;

        $isDie && die($message);
    }

    // composer
    if ($autoloadPath = realpath('D:\OSPanel\domains\sysmdump\cli-autoload\vendor\autoload.php')) {
        require_once $autoloadPath;
    }

