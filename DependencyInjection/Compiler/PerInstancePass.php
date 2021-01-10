<?php

namespace JMS\SerializerBundle\DependencyInjection\Compiler;

use JMS\SerializerBundle\DependencyInjection\DIUtils;
use JMS\SerializerBundle\DependencyInjection\ScopedContainer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @internal
 */
abstract class PerInstancePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
         foreach (self::getSerializers($container) as $scopedContainer) {
             $this->processInstance($scopedContainer);
         }
    }

    /**
     * @param ContainerBuilder $container
     * @return ScopedContainer[]
     */
    private static function getSerializers(ContainerBuilder $container): array
    {
        $serializers = [];

        foreach ($container->findTaggedServiceIds('jms_serializer.serializer') as $serializerId => $serializerAttributes) {
            foreach ($serializerAttributes as $serializerAttribute) {
                $serializers[$serializerId] = new ScopedContainer($container, $serializerAttribute['name']);
            }
        }
        return $serializers;
    }

    protected abstract function processInstance(ScopedContainer $container): void;
}
