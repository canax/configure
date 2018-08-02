<?php

namespace Anax\Configure;

/**
 * Load configuration for a specified item, look in several places for the
 * configuration files or directories. Return the configuration as the value
 * received from the configuration file.
 */
class Configuration
{
    /**
     * @var [] $dirs where to look for configuration items.
     */
    protected $dirs = [];



    /**
     * Set the directories where to look for configuration
     * items (files, directories) to load.
     *
     * @throws Exception when the path to any of the directories are incorrect.
     *
     * @param array $dirs with the path to the config directories to search in.
     *
     * @return self to allow chaining.
     */
    public function setBaseDirectories(array $dirs): object
    {
        if (empty($dirs)) {
            throw new Exception(t("The array for configuration directories can not be empty."));
        }

        foreach ($dirs as $dir) {
            if (!(is_readable($dir) && is_dir($dir))) {
                throw new Exception(t(
                    "The configuration dir '@dir' is not a valid path.",
                    ["@dir" => $dir]
                ));
            }
        }

        $this->dirs = $dirs;
        return $this;
    }



    /**
     * Read configuration from file or directory, if a file, look though all
     * base dirs and use the first configuration that is found. A configuration
     * item can be combined from a file and a directory, when available in the
     * same base directory.
     *
     * The resulting configuration is always an array, its structure contains
     * values from each individual configuration file, like this.
     *
     * $config = [
     *      "base" => configuration returned from file.php,
     *      "items" => [
     *          "file1.php" => configuration returned from dir/file1.php,
     *          "file2.php" => configuration returned from dir/file2.php,
     *      ]
     * ]
     *
     * The configuration files in the directory are loaded per alphabetical
     * order.
     *
     * @param string $item is a name representing the module and is used to
     *                     combine the path to search for.
     *
     * @return mixed with returned value from the loaded configuration.
     *
     * @throws Exception when configuration item can not be found.
     * @throws Exception when $dirs are empty.
     */
    public function load(string $item): array
    {
        if (empty($this->dirs)) {
            throw new Exception(t("The array for configuration directories can not be empty."));
        }

        $found = false;
        $config = [];
        foreach ($this->dirs as $dir) {
            $path = "$dir/$item";
            $file = "$path.php";

            // The configuration is found in a file
            if (is_readable($file) && is_file($file)) {
                $found = true;
                $config["base"] = require $file;
            }

            // The configuration is found in a directory
            if (is_readable($path) && is_dir($path)) {
                $found = true;
                $config["items"] = $this->loadFromDir($path);
            }

            if ($found) {
                break;
            }
        }

        if (!$found) {
            throw new Exception(t(
                "Configure item '@item' can not be found.",
                ["@item" => $item]
            ));
        }

        return $config;
    }



    /**
     * Read configuration a directory, loop through all files and add
     * them into the $config array as [
     *      "file1.php" => loaded configuration from dir/file1.php,
     *      "file2.php" => loaded configuration from dir/file2.php,
     * ].
     *
     * @param string $path is the path to the directory containing config files.
     *
     * @return array with configuration for each file.
     */
    public function loadFromDir(string $path): array
    {
        $config = [];
        foreach (glob("$path/*.php") as $file) {
            $config[basename($file)] = require $file;
        }

        return $config;
    }
}
