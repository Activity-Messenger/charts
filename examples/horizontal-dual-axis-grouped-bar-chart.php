<?php

require '../vendor/autoload.php';

use Maantje\Charts\Formatter;
use Maantje\Charts\HorizontalBar\HorizontalBar;
use Maantje\Charts\HorizontalBar\HorizontalBarGroup;
use Maantje\Charts\HorizontalBar\HorizontalBars;
use Maantje\Charts\HorizontalBarChart;
use Maantje\Charts\YAxis;

$blue = '#1f77b4';
$red = '#d62728';

$chart = new HorizontalBarChart(
    width: 900,
    height: 450,
    yAxis: [
        new YAxis(
            name: 'revenue',
            title: 'Revenue',
            color: $blue,
            formatter: Formatter::template('$:value'),
        ),
        new YAxis(
            name: 'orders',
            title: 'Orders',
            color: $red,
            formatter: Formatter::template(':value orders'),
        ),
    ],
    series: [
        new HorizontalBars(
            bars: [
                new HorizontalBarGroup(
                    name: 'January',
                    bars: [
                        new HorizontalBar(value: 120000, yAxis: 'revenue', color: $blue, height: 24),
                        new HorizontalBar(value: 320, yAxis: 'orders', color: $red, height: 24),
                    ],
                ),
                new HorizontalBarGroup(
                    name: 'February',
                    bars: [
                        new HorizontalBar(value: 95000, yAxis: 'revenue', color: $blue, height: 24),
                        new HorizontalBar(value: 280, yAxis: 'orders', color: $red, height: 24),
                    ],
                ),
                new HorizontalBarGroup(
                    name: 'March',
                    bars: [
                        new HorizontalBar(value: 142000, yAxis: 'revenue', color: $blue, height: 24),
                        new HorizontalBar(value: 410, yAxis: 'orders', color: $red, height: 24),
                    ],
                ),
            ],
        ),
    ],
);

echo $chart->render();
