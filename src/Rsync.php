<?php

namespace SociosEnInternet\PhpciPlugins;

use PHPCI\Plugin;
use PHPCI\Builder;
use PHPCI\Model\Build;
use PHPCI\Helper\Lang;

/**
* Drush Plugin - Provides access to Rsync functionality.
* @author       Ivan Bustos <contacto@ivanbustos.com>
* @package      PHPCI
* @subpackage   Plugins
*/
class Rsync implements Plugin
{
    protected $phpci;
    protected $build;
    protected $log;
    protected $options;
    protected $arguments;
    protected $source;
    protected $flags;

    /**
     * Standard Constructor
     *
     * $options['directory'] Output Directory. Default: %BUILDPATH%
     * $options['filename']  Phar Filename. Default: build.phar
     * $options['regexp']    Regular Expression Filename Capture. Default: /\.php$/
     * $options['stub']      Stub Content. No Default Value
     *
     * @param Builder $phpci
     * @param Build $build
     * @param array $options
     */
    public function __construct(Builder $phpci, Build $build, array $options = array())
    {
        $path             = $phpci->buildPath;
        $this->phpci      = $phpci;
        $this->build      = $build;
        $this->directory  = $path;
        
        if (array_key_exists('source', $options)) {
            $this->source = $options['source'];
        }
        
        if (array_key_exists('flags', $options)) {
            $this->flags = $options['flags'];
        }
    }

    /**
     * Executes Drush and runs a specified command
     */
    public function execute()
    {
        $buildPath = $this->phpci->buildPath;
        if (empty($this->source)) {
            $this->phpci->logFailure('Source missing.');
            return;
        }
        $rsyncLocation = $this->phpci->findBinary(array('rsync'));
        if ($this->flags) {
            $cmd = $rsyncLocation . ' -%s %s %s';
            return $this->phpci->executeCommand($cmd, $this->flags, $this->source, $buildPath);
        }
        $cmd = $rsyncLocation . ' %s %s';
        return $this->phpci->executeCommand($cmd, $this->source, $buildPath);
    }
}
