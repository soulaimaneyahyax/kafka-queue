<?php

declare(strict_types=1);

namespace Kafka\Services;

use Illuminate\Queue\Queue;
use Jobcloud\Kafka\Producer\KafkaProducer;
use Jobcloud\Kafka\Consumer\KafkaConsumerInterface;
use Illuminate\Contracts\Queue\Queue as QueueContract;

class KafkaQueue extends Queue implements QueueContract
{
}
