<?php

require '../vendor/autoload.php';

use Maantje\Charts\Bar\Bar;
use Maantje\Charts\Bar\BarGroup;
use Maantje\Charts\Bar\Bars;
use Maantje\Charts\Chart;
use Maantje\Charts\Formatter;
use Maantje\Charts\YAxis;

$chart = new Chart(
    yAxis: [
        new YAxis(
            name: 'revenue',
            title: 'Revenue',
            color: '#1f77b4',
            formatter: Formatter::template('$:value'),
        ),
        new YAxis(
            name: 'orders',
            title: 'Orders',
            position: 'right',
            color: '#d62728',
            formatter: Formatter::template(':value orders'),
        ),
    ],
    series: [
        new Bars(
            bars: [
                new BarGroup(
                    name: 'Jan',
                    bars: [
                        new Bar(value: 120000, yAxis: 'revenue', color: '#1f77b4', width: 40),
                        new Bar(value: 320, yAxis: 'orders', color: '#d62728', width: 40),
                    ],
                ),
                new BarGroup(
                    name: 'Feb',
                    bars: [
                        new Bar(value: 95000, yAxis: 'revenue', color: '#1f77b4', width: 40),
                        new Bar(value: 280, yAxis: 'orders', color: '#d62728', width: 40),
                    ],
                ),
                new BarGroup(
                    name: 'Mar',
                    bars: [
                        new Bar(value: 142000, yAxis: 'revenue', color: '#1f77b4', width: 40),
                        new Bar(value: 410, yAxis: 'orders', color: '#d62728', width: 40),
                    ],
                ),
            ],
        ),
    ],
);

echo $chart->render();
