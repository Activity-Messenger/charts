<?php

use Maantje\Charts\Bar\Bar;
use Maantje\Charts\Bar\Bars;
use Maantje\Charts\Chart;
use Maantje\Charts\YAxis;

it('calculates bar min and max values per axis when bars use different axes', function () {
    $chart = new Chart(
        yAxis: [
            new YAxis(name: 'default'),
            new YAxis(name: 'secondary'),
        ],
        series: [
            new Bars(bars: [
                new Bar(value: 50),
                new Bar(value: 500, yAxis: 'secondary'),
                new Bar(value: -25, yAxis: 'secondary'),
            ]),
        ],
    );

    expect($chart->maxValue('default'))->toBe(50.0)
        ->and($chart->minValue('default'))->toBe(0.0)
        ->and($chart->maxValue('secondary'))->toBe(500.0)
        ->and($chart->minValue('secondary'))->toBe(-25.0);
});

it('uses bars series axis as fallback when individual bars do not define one', function () {
    $bar = new Bar(value: 250);
    $bars = new Bars(bars: [$bar], yAxis: 'secondary');

    $chart = new Chart(
        yAxis: [
            new YAxis(name: 'default', minValue: 0, maxValue: 100),
            new YAxis(name: 'secondary', minValue: 0, maxValue: 1000),
        ],
        series: [$bars],
    );

    $svg = $bars->render($chart);
    $expectedY = $chart->yForAxis(250, 'secondary');
    $defaultY = $chart->yForAxis(250, 'default');

    expect($svg)->toContain('y="'.$expectedY.'"')
        ->and($svg)->not->toContain('y="'.$defaultY.'"');
});
