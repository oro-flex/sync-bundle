<?php

namespace Oro\Bundle\SyncBundle\Wamp;

# TODO: should be uncommented in scope of BAP-16769, see https://github.com/laboro/dev/pull/16599/files for details
//use JDare\ClankBundle\Periodic\PeriodicInterface;
//use Oro\Bundle\SyncBundle\Authentication\AuthenticationTopicHandler;
//use Psr\Log\LoggerInterface;
//use Ratchet\Wamp\Topic;
//
///**
// * This periodic service is used to broadcast messages to clients to prevent connection loose
// * by default, the connection could be closed if no data were transmitted between client and server
// * @link http://nginx.org/en/docs/http/websocket.html
// */
//class ClientPing implements PeriodicInterface
//{
//    /** @var AuthenticationTopicHandler */
//    private $topicHandler;
//
//    /** @var LoggerInterface */
//    private $logger;
//
//    /**
//     * @param AuthenticationTopicHandler $topicHandler
//     * @param LoggerInterface $logger
//     */
//    public function __construct(AuthenticationTopicHandler $topicHandler, LoggerInterface $logger)
//    {
//        $this->topicHandler = $topicHandler;
//        $this->logger = $logger;
//    }
//
//    /**
//     * {@inheritdoc}
//     */
//    public function tick()
//    {
//        try {
//            // search oro/ping topic and broadcast a ping message to it.
//            /** @var Topic[] $topics */
//            $topics = $this->topicHandler->getSubscribedTopics();
//            if (array_key_exists('oro/ping', $topics)) {
//                $topics['oro/ping']->broadcast('Connection keep alive');
//            }
//        } catch (\Exception $e) {
//            $this->logger->error(
//                'Failed to broadcast a message to the topic "oro/ping"',
//                ['exception' => $e]
//            );
//        }
//    }
//}
