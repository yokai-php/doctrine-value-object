<?php

declare(strict_types=1);

use Doctrine\Deprecations\Deprecation;
use Psr\Log\AbstractLogger;

require __DIR__ . '/../vendor/autoload.php';

// A deprecation of doctrine/dbal fails the test when this repository causes it,
// so that the library keeps working for projects that do not tolerate deprecations.
// The ones caused by another dependency (e.g. doctrine/orm) are not ours to fix.
Deprecation::withoutDeduplication();
Deprecation::enableWithPsrLogger(new class extends AbstractLogger {
    /**
     * @param mixed                $level
     * @param string|\Stringable   $message
     * @param array<string, mixed> $context
     */
    public function log($level, $message, array $context = []): void
    {
        if (($context['package'] ?? null) !== 'doctrine/dbal') {
            return;
        }

        $vendor = \dirname(__DIR__) . '/vendor/';
        $internals = [$vendor . 'doctrine/dbal/', $vendor . 'doctrine/deprecations/', $vendor . 'psr/log/', __FILE__];
        foreach (\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
            $file = $frame['file'] ?? '';
            if ($file === '') {
                continue;
            }
            foreach ($internals as $internal) {
                if (\str_starts_with($file, $internal)) {
                    continue 2;
                }
            }

            // first caller outside doctrine/dbal
            if (\str_starts_with($file, $vendor)) {
                return;
            }

            break;
        }

        throw new \LogicException(\sprintf('Deprecation triggered by doctrine/dbal: %s', $message));
    }
});
