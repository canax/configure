<?php

namespace Anax\Configure;

use \PHPUnit\Framework\TestCase;

/**
 * A test class.
 */
class ConfigurationTest extends TestCase
{
    /**
     * Base directories for configuration.
     */
    protected $dirs = [
        __DIR__ . "/../config1",
        __DIR__ . "/../config2",
    ];



    /**
     * Load configuration from file alone.
     */
    public function testConfigFromSingleFile()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("view");

        $this->assertInternalType("array", $config);
        $this->assertArrayHasKey("base", $config);
        $this->assertArrayNotHasKey("items", $config);
        $this->assertContains("a view", $config["base"]);
    }



    /**
     * Load configuration from directory alone.
     */
    public function testConfigFromDirectory()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("response");

        $this->assertInternalType("array", $config);
        $this->assertArrayNotHasKey("base", $config);
        $this->assertArrayHasKey("items", $config);
        $this->assertContains("part1", $config["items"]["part1.php"]);
        $this->assertContains("part2", $config["items"]["part2.php"]);
    }



    /**
     * Load configuration from file and directory.
     */
    public function testConfigFromFileAndDirectory()
    {
        $cfg = new Configuration();
        $cfg->setBaseDirectories($this->dirs);
        $config = $cfg->load("route");

        $this->assertInternalType("array", $config);
        $this->assertArrayHasKey("base", $config);
        $this->assertContains("a route", $config["base"]);
        $this->assertArrayHasKey("items", $config);
        $this->assertContains("a 404 route", $config["items"]["404.php"]);
        $this->assertContains("an internal route", $config["items"]["internal.php"]);
    }
}
