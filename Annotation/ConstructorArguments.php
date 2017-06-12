<?php
namespace Webonaute\DoctrineFixturesGeneratorBundle\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
final class ConstructorArguments extends Annotation
{
    /**
     * @param bool $asString
     * @return array|string
     * @throws \Exception
     */
    public function getArguments($asString = false)
    {
        $constructorArguments = [];

        foreach ($this->value as $k => $ca) {
            if (isset($ca['value'])) {
                $caValue = $ca['value'];
            } elseif (isset($ca['php'])) {
                if ($asString) {
                    $caValue = $ca['php'];
                } else {
                    eval('$caValue = ' . $ca['php'] . ';');
                }
            } else {
                throw new \Exception(sprintf('Cannot determine constructor argument. [$s]', json_encode($ca)));
            }

            $constructorArguments[$k] = $caValue;
        }

        if ($asString) {
            $constructorArguments = $this->toString($constructorArguments);
        }

        return $constructorArguments;
    }

    /**
     * @param array $args
     * @return string
     */
    protected function toString(array $args)
    {
        $string = implode(', ', $args);

        return $string;
    }
}