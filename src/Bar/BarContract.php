<?php

namespace Maantje\Charts\Bar;

use Maantje\Charts\Chart;

interface BarContract
{
    public function value(): float;

    public function maxValue(): float;

    public function minValue(): float;

    public function maxValueForAxis(string $axis, ?string $fallbackAxis = null): ?float;

    public function minValueForAxis(string $axis, ?string $fallbackAxis = null): ?float;

    public function render(Chart $chart, float $x, float $maxBarWidth, ?string $fallbackAxis = null): string;
}
