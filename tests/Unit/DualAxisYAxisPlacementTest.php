<?php

use Maantje\Charts\Bar\Bar;
use Maantje\Charts\Bar\Bars;
use Maantje\Charts\Chart;
use Maantje\Charts\YAxis;

it('renders the second y-axis on the right side by default', function () {
    $chart = new Chart(
        yAxis: [
            new YAxis(name: 'left', title: 'Left Axis'),
            new YAxis(name: 'right', title: 'Right Axis'),
        ],
        series: [
            new Bars(bars: [new Bar(value: 10)], yAxis: 'left'),
            new Bars(bars: [new Bar(value: 1000)], yAxis: 'right'),
        ],
    );

    $svg = $chart->render();

    expect($svg)->toContain('>Left Axis</text>')
        ->and($svg)->toContain('rotate(270')
        ->and($svg)->toContain('>Right Axis</text>')
        ->and($svg)->toContain('rotate(90');
});
