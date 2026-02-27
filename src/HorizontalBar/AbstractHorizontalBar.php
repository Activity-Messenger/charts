<?php

namespace Maantje\Charts\HorizontalBar;

abstract class AbstractHorizontalBar implements HorizontalBarContract
{
    public function __construct(
        public ?string $name = null,
        public ?string $yAxis = null,
        public string $color = '#3498db',
        public ?float $height = null,
    ) {}

    public function label(): ?string
    {
        return $this->name;
    }

    protected function calculateHeight(float $maxBarHeight): float
    {
        return min($this->height ?? $maxBarHeight, $maxBarHeight);
    }

    protected function calculateY(float $y, float $height, float $maxBarHeight): float
    {
        if (! is_null($this->height)) {
            return $y + ($maxBarHeight - $height) / 2;
        }

        return $y;
    }

    protected function axis(?string $fallbackAxis = null): string
    {
        return $this->yAxis ?? $fallbackAxis ?? 'default';
    }

    public function maxValueForAxis(string $axis, ?string $fallbackAxis = null): ?float
    {
        if ($this->axis($fallbackAxis) !== $axis) {
            return null;
        }

        return $this->maxValue();
    }

    public function minValueForAxis(string $axis, ?string $fallbackAxis = null): ?float
    {
        if ($this->axis($fallbackAxis) !== $axis) {
            return null;
        }

        return $this->minValue();
    }
}
