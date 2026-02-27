<?php

use Maantje\Charts\HorizontalBar\HorizontalBar;
use Maantje\Charts\HorizontalBar\HorizontalBarGroup;
use Maantje\Charts\HorizontalBar\HorizontalBars;
use Maantje\Charts\HorizontalBarChart;
use Maantje\Charts\YAxis;

it('calculates x coordinates and zero line for mixed values', function () {
    $chart = new HorizontalBarChart(
        series: [
            new HorizontalBars(
                bars: [
                    new HorizontalBar(name: 'A', value: 100),
                    new HorizontalBar(name: 'B', value: -100),
                ],
            ),
        ],
    );

    $zeroX = $chart->zeroLineX();
    $positiveX = $chart->xForAxis(100);
    $negativeX = $chart->xForAxis(-100);

    expect($positiveX)->toBeGreaterThan($zeroX)
        ->and($negativeX)->toBeLessThan($zeroX)
        ->and($zeroX)->toBeGreaterThan($chart->left())
        ->and($zeroX)->toBeLessThan($chart->right());
});

it('renders grouped horizontal bars with distinct vertical positions', function () {
    $chart = new HorizontalBarChart(
        yAxis: [
            new YAxis(name: 'revenue', title: 'Revenue'),
            new YAxis(name: 'orders', title: 'Orders'),
        ],
        series: [
            new HorizontalBars(
                bars: [
                    new HorizontalBarGroup(
                        name: 'Jan',
                        bars: [
                            new HorizontalBar(value: 1000, yAxis: 'revenue'),
                            new HorizontalBar(value: 250, yAxis: 'orders'),
                        ],
                    ),
                    new HorizontalBarGroup(
                        name: 'Feb',
                        bars: [
                            new HorizontalBar(value: 1500, yAxis: 'revenue'),
                            new HorizontalBar(value: 400, yAxis: 'orders'),
                        ],
                    ),
                ],
            ),
        ],
    );

    $svg = $chart->render();

    $dom = new DOMDocument;
    $dom->loadXML($svg);
    $xpath = new DOMXPath($dom);
    $xpath->registerNamespace('svg', 'http://www.w3.org/2000/svg');
    $barRects = $xpath->query('//svg:rect[svg:title]');

    expect($barRects)->not->toBeFalse()
        ->and($barRects->length)->toBe(4)
        ->and((float) $barRects->item(0)->getAttribute('y'))->not->toBe((float) $barRects->item(1)->getAttribute('y'))
        ->and($svg)->toContain('>Revenue</text>')
        ->and($svg)->toContain('>Orders</text>')
        ->and($chart->yAxis['revenue']->position)->toBe('bottom')
        ->and($chart->yAxis['orders']->position)->toBe('top');
});

it('renders category labels from input order', function () {
    $chart = new HorizontalBarChart(
        series: [
            new HorizontalBars(
                bars: [
                    new HorizontalBar(name: 'One', value: 10),
                    new HorizontalBar(name: 'Two', value: 20),
                    new HorizontalBar(name: 'Three', value: 30),
                ],
            ),
        ],
    );

    $svg = $chart->render();

    expect($svg)->toContain('>One</text>')
        ->and($svg)->toContain('>Two</text>')
        ->and($svg)->toContain('>Three</text>');
});
