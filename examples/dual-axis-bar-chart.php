<?php

require '../vendor/autoload.php';

use Maantje\Charts\Bar\Bar;
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
            yAxis: 'revenue',
            bars: [
                new Bar(name: 'Jan', value: 120000, color: '#1f77b4'),
                new Bar(name: 'Feb', value: 95000, color: '#1f77b4'),
                new Bar(name: 'Mar', value: 142000, color: '#1f77b4'),
            ],
        ),
        new Bars(
            bars: [
                new Bar(name: 'Jan', value: 320, yAxis: 'orders', color: '#d62728', width: 40),
                new Bar(name: 'Feb', value: 280, yAxis: 'orders', color: '#d62728', width: 40),
                new Bar(name: 'Mar', value: 410, yAxis: 'orders', color: '#d62728', width: 40),
            ],
        ),
    ],
);

echo $chart->render();
