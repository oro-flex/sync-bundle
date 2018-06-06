<?php

namespace Oro\Bundle\SyncBundle\Authentication;

use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Gos\Bundle\WebSocketBundle\Server\App\Dispatcher\TopicDispatcherInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

/**
 * Decorator that adds Sync authentication tickets functionality.
 */
class TicketAuthenticationAwareTopicDispatcherDecorator implements TopicDispatcherInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * @var TopicDispatcherInterface
     */
    private $decoratedTopicDispatcher;

    /**
     * @param TopicDispatcherInterface $decoratedTopicDispatcher
     */
    public function __construct(TopicDispatcherInterface $decoratedTopicDispatcher)
    {
        $this->decoratedTopicDispatcher = $decoratedTopicDispatcher;
        $this->logger = new NullLogger();
    }

    /**
     * {@inheritdoc}
     */
    public function onSubscribe(ConnectionInterface $conn, Topic $topic, WampRequest $request)
    {
        if (!isset($conn->Authenticated) || !$conn->Authenticated) {
            // Remove not authorized client from the topic.
            $topic->remove($conn);

            $this->logger->warning(
                'Skip subscribing to the topic "{topic}", because the client is not authenticated',
                [
                    'remoteAddress' => $conn->remoteAddress,
                    'connectionId' => $conn->resourceId,
                    'topic' => $topic,
                ]
            );

            return;
        }

        $this->decoratedTopicDispatcher->onSubscribe($conn, $topic, $request);
    }

    /**
     * {@inheritdoc}
     */
    public function onUnSubscribe(ConnectionInterface $conn, Topic $topic, WampRequest $request)
    {
        $this->decoratedTopicDispatcher->onUnSubscribe($conn, $topic, $request);
        $this->logger->debug(
            'Unsubscribe client from the topic "{topic}"',
            [
                'remoteAddress' => $conn->remoteAddress,
                'connectionId' => $conn->resourceId,
                'topic' => $topic,
            ]
        );
    }

    /**
     * {@inheritDoc}
     */
    public function onPush(WampRequest $request, $data, $provider)
    {
        $this->decoratedTopicDispatcher->onPush($request, $data, $provider);
    }

    /**
     * {@inheritDoc}
     */
    public function onPublish(
        ConnectionInterface $conn,
        Topic $topic,
        WampRequest $request,
        $event,
        array $exclude,
        array $eligible
    ) {
        $logContent = [
            'remoteAddress' => $conn->remoteAddress,
            'connectionId' => $conn->resourceId,
            'topic' => $topic,
        ];

        if (isset($conn->Authenticated) && $conn->Authenticated) {
            $this->decoratedTopicDispatcher->onPublish($conn, $topic, $request, $event, $exclude, $eligible);

            $this->logger->debug('Send a message to the topic "{topic}"', $logContent);
        } else {
            $this->logger->warning(
                'Skip the sending of a message to the topic "{topic}", because the client is not authenticated',
                $logContent
            );
        }
    }

    /**
     * {@inheritDoc}
     */
    public function dispatch(
        $calledMethod,
        ConnectionInterface $conn,
        Topic $topic,
        WampRequest $request,
        $payload = null,
        $exclude = null,
        $eligible = null
    ) {
        return $this->decoratedTopicDispatcher
            ->dispatch($calledMethod, $conn, $topic, $request, $payload, $exclude, $eligible);
    }
}
