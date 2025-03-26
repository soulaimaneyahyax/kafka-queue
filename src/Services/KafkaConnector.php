<?php

declare(strict_types=1);

namespace Kafka\Services;

use Kafka\Services\KafkaQueue;
use Jobcloud\Kafka\Consumer\KafkaConsumerBuilder;
use Jobcloud\Kafka\Producer\KafkaProducerBuilder;
use Illuminate\Queue\Connectors\ConnectorInterface;
use Jobcloud\Kafka\Consumer\KafkaConsumerInterface;
use Jobcloud\Kafka\Producer\KafkaProducerInterface;

class KafkaConnector implements ConnectorInterface
{
    public function connect(array $config): KafkaQueue
    {
        return new KafkaQueue(
            $this->createProducer($config),
            $this->createConsumer($config)
        );
    }

    protected function createProducer(array $config): KafkaProducerInterface
    {
        return KafkaProducerBuilder::create()
            ->withAdditionalConfig($this->getCommonConfig($config))
            ->build();
    }

    protected function createConsumer(array $config): KafkaConsumerInterface
    {
        return KafkaConsumerBuilder::create()
            ->withAdditionalConfig(array_merge(
                $this->getCommonConfig($config),
                ['group.id' => $config['group_id'] ?? 'default_group',
                 'auto.offset.reset' => 'earliest']
            ))
            ->withConsumerGroup($config['group_id'] ?? 'default_group')
            ->build();
    }

    protected function getCommonConfig(array $config): array
    {
        return [
            'bootstrap.servers' => $config['bootstrap_servers'] ?? 'localhost:9092',
            'security.protocol' => $config['security_protocol'] ?? 'SASL_SSL',
            'sasl.mechanisms' => $config['sasl_mechanisms'] ?? 'PLAIN',
            'sasl.username' => $config['sasl_username'] ?? '',
            'sasl.password' => $config['sasl_password'] ?? '',
        ];
    }
}
