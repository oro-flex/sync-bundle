<?php

namespace Oro\Bundle\SyncBundle\Twig;

use Oro\Bundle\SyncBundle\Content\TagGeneratorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OroSyncExtension extends \Twig_Extension
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return TagGeneratorInterface
     */
    protected function getTagGenerator()
    {
        return $this->container->get('oro_sync.content.tag_generator');
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('check_ws', [$this, 'checkWsConnected']),
            new \Twig_SimpleFunction('oro_sync_get_content_tags', [$this, 'generate'])
        ];
    }

    /**
     * Check WebSocket server connection
     *
     * @return bool True on success, false otherwise
     */
    public function checkWsConnected()
    {
        // TODO: MUST BE FIXED IN BAP-17006
        return false;
    }

    /**
     * @param mixed $data
     * @param bool  $includeCollectionTag
     * @param bool  $processNestedData
     *
     * @return array
     */
    public function generate($data, $includeCollectionTag = false, $processNestedData = true)
    {
        // enforce plain array should returns
        return array_values($this->getTagGenerator()->generate($data, $includeCollectionTag, $processNestedData));
    }

    /**
     * Returns the name of the extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'sync_extension';
    }
}
