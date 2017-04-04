<?php

namespace WonderWp\Framework\DependencyInjection;

use Pimple\Container as PimpleContainer;

class Container extends PimpleContainer implements SingletonInterface
{
    use SingletonTrait {
        SingletonTrait::buildInstance as createInstance;
    }

    protected $processed              = false;
    protected $outOfRequestScopeTypes = [];

    /** @inheritdoc */
    public static function buildInstance()
    {
        $instance = static::createInstance();

        add_action('shutdown', [$instance, 'dump']);

        return $instance;
    }

    /**
     * Dumps the container for IDE auto-completion purposes
     */
    public function dump()
    {
        $map = $this->parseContainer($this);

        $outputDir = array_key_exists('pimple_dump.output_dir', $this) ? $this['pimple_dump.output_dir'] : ROOT_DIR;
        $fileName  = $outputDir . '/pimple.json';
        $this->write($map, $fileName);

        $this->processed = true;
    }

    /**
     * Generate a mapping of the container's values
     *
     * @param Container $container
     *
     * @return array
     */
    protected function parseContainer(Container $container)
    {
        $map = [];

        foreach ($container->keys() as $name) {
            if ($item = $this->parseItem($container, $name)) {
                $map[] = $item;
            }
        }

        return $map;
    }

    /**
     * Parse the item's type and value
     *
     * @param Container $container
     * @param string    $name
     *
     * @return array|null
     */
    protected function parseItem(Container $container, $name)
    {
        try {
            $element = $container[$name];
        } catch (\Exception $e) {
            if (array_key_exists($name, $this->outOfRequestScopeTypes)) {
                return [
                    'name'  => $name,
                    'type'  => 'class',
                    'value' => $this->outOfRequestScopeTypes[$name],
                ];
            }

            return null;
        }

        if (is_object($element)) {
            if ($element instanceof \Closure) {
                $type  = 'closure';
                $value = '';
            } elseif ($element instanceof Container) {
                $type  = 'container';
                $value = $this->parseContainer($element);
            } else {
                $type  = 'class';
                $value = get_class($element);
            }
        } elseif (is_array($element)) {
            $type  = 'array';
            $value = '';
        } elseif (is_string($element)) {
            $type  = 'string';
            $value = $element;
        } elseif (is_int($element)) {
            $type  = 'int';
            $value = $element;
        } elseif (is_float($element)) {
            $type  = 'float';
            $value = $element;
        } elseif (is_bool($element)) {
            $type  = 'bool';
            $value = $element;
        } elseif ($element === null) {
            $type  = 'null';
            $value = '';
        } else {
            $type  = 'unknown';
            $value = gettype($element);
        }

        return [
            'name'  => $name,
            'type'  => $type,
            'value' => $value,
        ];
    }

    /**
     * Dump mapping to file
     *
     * @param array  $map
     * @param string $fileName
     */
    protected function write($map, $fileName)
    {
        $content = json_encode($map, JSON_PRETTY_PRINT);

        if (!file_exists($fileName)) {
            file_put_contents($fileName, $content);

            return;
        }

        $oldContent = file_get_contents($fileName);
        // prevent file lastModified time change
        if ($content !== $oldContent) {
            file_put_contents($fileName, $content);
        }
    }
}
