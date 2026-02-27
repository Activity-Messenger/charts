<?php

namespace Maantje\Charts\HorizontalBar;

use Maantje\Charts\Chart;
use Maantje\Charts\SVG\Fragment;

class HorizontalBarGroup implements HorizontalBarContract
{
    /**
     * @param  HorizontalBar[]  $bars
     */
    public function __construct(
        protected string $name,
        protected int $margin = 5,
        protected ?float $height = null,
        protected array $bars = [],
        public ?int $radius = null,
    ) {
        if (is_null($this->radius)) {
            return;
        }

        foreach ($this->bars as $bar) {
            if (is_null($bar->radius)) {
                $bar->radius = $this->radius;
            }
        }
    }

    public function label(): ?string
    {
        return $this->name;
    }

    public function maxValue(): float
    {
        if (count($this->bars) === 0) {
            return 0;
        }

        return max(array_map(fn (HorizontalBarContract $bar) => $bar->value(), $this->bars));
    }

    public function minValue(): float
    {
        if (count($this->bars) === 0) {
            return 0;
        }

        return min(array_map(fn (HorizontalBarContract $bar) => $bar->value(), $this->bars));
    }

    public function maxValueForAxis(string $axis, ?string $fallbackAxis = null): ?float
    {
        $values = array_values(array_filter(
            array_map(fn (HorizontalBarContract $bar) => $bar->maxValueForAxis($axis, $fallbackAxis), $this->bars),
            fn (?float $value) => $value !== null
        ));

        if (count($values) === 0) {
            return null;
        }

        return max($values);
    }

    public function minValueForAxis(string $axis, ?string $fallbackAxis = null): ?float
    {
        $values = array_values(array_filter(
            array_map(fn (HorizontalBarContract $bar) => $bar->minValueForAxis($axis, $fallbackAxis), $this->bars),
            fn (?float $value) => $value !== null
        ));

        if (count($values) === 0) {
            return null;
        }

        return min($values);
    }

    public function render(Chart $chart, float $y, float $maxBarHeight, ?string $fallbackAxis = null): string
    {
        $numBars = count($this->bars);

        if ($numBars === 0) {
            return '';
        }

        $groupHeight = min($this->height ?? $maxBarHeight, $maxBarHeight);
        $innerGapTotal = $this->margin * max(0, $numBars - 1);
        $barHeight = max(1, ($groupHeight - $innerGapTotal) / $numBars);

        $currentY = $y + ($maxBarHeight - $groupHeight) / 2;

        return new Fragment(array_map(function (HorizontalBarContract $bar) use (&$currentY, $barHeight, $chart, $fallbackAxis) {
            $svg = $bar->render($chart, $currentY, $barHeight, $fallbackAxis);
            $currentY += $barHeight + $this->margin;

            return $svg;
        }, $this->bars));
    }

    public function value(): float
    {
        return $this->maxValue();
    }
}
