<?php

namespace Maantje\Charts\HorizontalBar;

use Maantje\Charts\Chart;

interface HorizontalBarContract
{
    public function value(): float;

    public function label(): ?string;

    public function maxValue(): float;

    public function minValue(): float;

    public function maxValueForAxis(string $axis, ?string $fallbackAxis = null): ?float;

    public function minValueForAxis(string $axis, ?string $fallbackAxis = null): ?float;

    public function render(Chart $chart, float $y, float $maxBarHeight, ?string $fallbackAxis = null): string;
}
