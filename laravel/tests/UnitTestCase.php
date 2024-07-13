<?php

declare(strict_types=1);

namespace Tests;

use Closure;
use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Throwable;

abstract class UnitTestCase extends TestCase
{
    /**
     * Assert that the given callback throws an exception with the given message when invoked.
     *
     * @param Closure $test
     * @param class-string<Throwable> $expectedClass
     * @param string $expectedMessage
     * @return $this
     */
    protected function assertThrows(
        Closure $test,
        string  $expectedClass = Throwable::class,
        string  $expectedMessage = ''
    ): self
    {
        try {
            $this->convertUserErrorToException();
            $test();
            $thrown = false;
        } catch (Throwable $exception) {
            $thrown = $exception instanceof $expectedClass;
            $actualMessage = $exception->getMessage();
        }

        restore_error_handler();

        Assert::assertTrue(
            $thrown,
            sprintf('Failed asserting that exception of type "%s" was thrown.', $expectedClass)
        );

        if (!empty($expectedMessage)) {
            if (!isset($actualMessage)) {
                Assert::fail(
                    sprintf(
                        'Failed asserting that exception of type "%s" with message "%s" was thrown.',
                        $expectedClass,
                        $expectedMessage
                    )
                );
            } else {
                Assert::assertStringContainsString($expectedMessage, $actualMessage);
            }
        }

        return $this;
    }

    protected function convertUserErrorToException(): void
    {
        set_error_handler(
            static function ($errno, $errstr) {
                throw new Exception($errstr, $errno);
            },
            E_USER_NOTICE | E_USER_WARNING
        );
    }
}
