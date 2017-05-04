<?php

namespace Anax\Configure;

/**
 * Testing.
 *
 */
class ConfigureTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test
     */
    public function testConfigReturnesSelf()
    {
        $config = new MockConfig();

        $subset = ["key" => "value"];
        $self = $config->configure($subset);
        $this->assertEquals($config, $self);

        $aConfig = $config->getConfig();
        $this->assertArraySubset($subset, $aConfig);
    }



    /**
     * Test
     */
    public function testConfigValueByArray()
    {
        $config = new MockConfig();

        $subset = ["key", "value"];
        $config->configure($subset);
        $aConfig = $config->getConfig();
        $this->assertArraySubset($subset, $aConfig);
    }



    /**
     * Test
     */
    public function testConfigValueByFile()
    {
        $config = new MockConfig();

        $expexted = ["key1" => "value1"];
        $config->configure("../test/config/test1.php");
        $aConfig = $config->getConfig();
        $this->assertArraySubset($expexted, $aConfig);
    }



    /**
     * Test
     *
     * @expectedException Anax\Configure\Exception
     */
    public function testMissingConfigFile()
    {
        $config = new MockConfig();
        $config->configure("MISSING-FILE");
    }
}
