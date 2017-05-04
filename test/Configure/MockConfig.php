<?php

namespace Anax\Configure;

/**
 * A class mocking the trait to be tested.
 */
class MockConfig implements ConfigureInterface
{
    use ConfigureTrait;

    /**
     * Provide access to private variable.
     */
    public function getConfig()
    {
        return $this->config;
    }
}
