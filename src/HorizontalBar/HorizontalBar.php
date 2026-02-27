<?php

namespace Maantje\Charts\HorizontalBar;

use Maantje\Charts\Chart;
use Maantje\Charts\SVG\Rect;

class HorizontalBar extends AbstractHorizontalBar
{
    public function __construct(
        ?string $name = null,
        public float $value = 0,
        ?string $yAxis = null,
        string $color = '#3498db',
        ?float $height = null,
        public ?int $radius = null,
    ) {
        parent::__construct($name, $yAxis, $color, $height);
    }

    public function render(Chart $chart, float $y, float $maxBarHeight, ?string $fallbackAxis = null): string
    {
        $height = $this->calculateHeight($maxBarHeight);
        $y = $this->calculateY($y, $height, $maxBarHeight);
        $axis = $this->axis($fallbackAxis);

        $valueX = $chart->xForAxis($this->value, $axis);
        $baseline = $chart->minValue($axis) >= 0 ? $chart->left() : $chart->zeroLineX($axis);

        $x = min($valueX, $baseline);
        $width = abs($valueX - $baseline);

        return new Rect(
            x: $x,
            y: $y,
            width: $width,
            height: $height,
            fill: $this->color,
            rx: $this->radius ?? 0,
            ry: $this->radius ?? 0,
            title: $this->value,
        );
    }

    public function value(): float
    {
        return $this->value;
    }

    public function maxValue(): float
    {
        return $this->value;
    }

    public function minValue(): float
    {
        return $this->value;
    }
}
