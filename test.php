<?php

use Gzhegow\Calendar\Calendar;
use function Gzhegow\Calendar\_calendar;
use function Gzhegow\Calendar\_php_dump;
use function Gzhegow\Calendar\_assert_true;
use function Gzhegow\Calendar\_calendar_now;
use function Gzhegow\Calendar\_calendar_diff;
use function Gzhegow\Calendar\_calendar_date;
use function Gzhegow\Calendar\_calendar_interval;
use function Gzhegow\Calendar\_calendar_timezone;
use function Gzhegow\Calendar\_calendar_now_fixed;
use function Gzhegow\Calendar\_calendar_now_immutable;
use function Gzhegow\Calendar\_calendar_date_immutable;
use function Gzhegow\Calendar\_calendar_now_fixed_clear;


require_once __DIR__ . '/vendor/autoload.php';


error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    if (error_reporting() & $errno) {
        throw new \ErrorException($errstr, -1, $errno, $errfile, $errline);
    }
});
set_exception_handler(function (\Throwable $throwable) {
    var_dump($throwable);

    die();
});


function main()
{
    _calendar($calendar = new Calendar());

    $tests[ '_calendar_date' ] = _calendar_date($datetime = 'now', $formats = null, $timezoneIfParsed = null);
    _assert_true('is_a', [ $tests[ '_calendar_date' ], DateTime::class ]);

    $tests[ '_calendar_date_immutable' ] = _calendar_date_immutable($datetime = 'now', $formats = null, $timezoneIfParsed = null);
    _assert_true('is_a', [ $tests[ '_calendar_date_immutable' ], DateTimeImmutable::class ]);
    $tests[ '_calendar_date_immutable_add' ] = $tests[ '_calendar_date_immutable' ]->add(new \DateInterval('P1D'));
    _assert_true('is_a', [ $tests[ '_calendar_date_immutable_add' ], DateTimeImmutable::class ]);
    $tests[ '_calendar_date_immutable_sub' ] = $tests[ '_calendar_date_immutable' ]->sub(new \DateInterval('P1D'));
    _assert_true('is_a', [ $tests[ '_calendar_date_immutable_sub' ], DateTimeImmutable::class ]);
    $tests[ '_calendar_date_immutable_modify' ] = $tests[ '_calendar_date_immutable' ]->modify('+ 10 hours');
    _assert_true('is_a', [ $tests[ '_calendar_date_immutable_modify' ], DateTimeImmutable::class ]);

    $tests[ '_calendar_now' ] = _calendar_now($timezone = null);
    _assert_true('is_a', [ $tests[ '_calendar_now' ], DateTime::class ]);

    $tests[ '_calendar_now_immutable' ] = _calendar_now_immutable($timezone = null);
    _assert_true('is_a', [ $tests[ '_calendar_now_immutable' ], DateTimeImmutable::class ]);

    $tests[ '_calendar_now_fixed0' ] = _calendar_now_fixed($datetime = null, $timezone = null);
    _assert_true('is_a', [ $tests[ '_calendar_now_fixed0' ], DateTimeImmutable::class ]);
    _calendar_now_fixed_clear();
    $tests[ '_calendar_now_fixed1' ] = _calendar_now_fixed($datetime = null, $timezone = null);
    $tests[ '_calendar_now_fixed2' ] = _calendar_now_fixed($datetime = null, $timezone = null);
    _assert_true('is_a', [ $tests[ '_calendar_now_fixed1' ], DateTimeImmutable::class ]);
    _assert_true('is_a', [ $tests[ '_calendar_now_fixed2' ], DateTimeImmutable::class ]);
    _assert_true(static function () use ($tests) {
        return $tests[ '_calendar_now_fixed1' ] === $tests[ '_calendar_now_fixed2' ];
    });

    $tests[ '_calendar_timezone' ] = _calendar_timezone($timezone = 'UTC');
    _assert_true('is_a', [ $tests[ '_calendar_timezone' ], DateTimeZone::class ]);
    _assert_true(function () use ($tests) {
        return 'UTC' === $tests[ '_calendar_timezone' ]->getName();
    });

    $tests[ '_calendar_interval' ] = _calendar_interval($interval = 'P0D', $formats = null);
    _assert_true('is_a', [ $tests[ '_calendar_interval' ], DateInterval::class ]);
    _assert_true(function () use ($tests) {
        return 'P0D' === $tests[ '_calendar_interval' ]->jsonSerialize();
    });

    $now = _calendar_now_immutable();
    $past = $now->modify('- 10 hours');
    $tests[ '_calendar_diff' ] = _calendar_diff($now, $past, $absolute = false); // : ?DateInterval;
    _assert_true('is_a', [ $tests[ '_calendar_diff' ], DateInterval::class ]);
    _assert_true(function () use ($tests) {
        return 'PT10H' === $tests[ '_calendar_diff' ]->jsonSerialize();
    });

    var_dump(json_encode($tests, JSON_PRETTY_PRINT));

    // string(730) "{
    //     "_calendar_date": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_date_immutable": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_date_immutable_add": "2024-05-10T19:47:39.074+03:00",
    //     "_calendar_date_immutable_sub": "2024-05-08T19:47:39.074+03:00",
    //     "_calendar_date_immutable_modify": "2024-05-10T05:47:39.074+03:00",
    //     "_calendar_now": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_now_immutable": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_now_fixed0": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_now_fixed1": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_now_fixed2": "2024-05-09T19:47:39.074+03:00",
    //     "_calendar_timezone": "UTC",
    //     "_calendar_interval": "P0D",
    //     "_calendar_diff": "PT10H"
    // }"

    $dump = [];
    foreach ( $tests as $i => $test ) {
        $dump[ $i ] = _php_dump($test);
    }
    var_dump($dump);

    // array(13) {
    //   ["_calendar_date"]=>
    //   string(48) "{ object(Gzhegow\Calendar\Struct\DateTime # 8) }"
    //   ["_calendar_date_immutable"]=>
    //   string(57) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 9) }"
    //   ["_calendar_date_immutable_add"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 10) }"
    //   ["_calendar_date_immutable_sub"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 11) }"
    //   ["_calendar_date_immutable_modify"]=>
    //   string(57) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 7) }"
    //   ["_calendar_now"]=>
    //   string(49) "{ object(Gzhegow\Calendar\Struct\DateTime # 12) }"
    //   ["_calendar_now_immutable"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 13) }"
    //   ["_calendar_now_fixed0"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 14) }"
    //   ["_calendar_now_fixed1"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 15) }"
    //   ["_calendar_now_fixed2"]=>
    //   string(58) "{ object(Gzhegow\Calendar\Struct\DateTimeImmutable # 15) }"
    //   ["_calendar_timezone"]=>
    //   string(53) "{ object(Gzhegow\Calendar\Struct\DateTimeZone # 16) }"
    //   ["_calendar_interval"]=>
    //   string(53) "{ object(Gzhegow\Calendar\Struct\DateInterval # 17) }"
    //   ["_calendar_diff"]=>
    //   string(53) "{ object(Gzhegow\Calendar\Struct\DateInterval # 20) }"
    // }

}

main();
