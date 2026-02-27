<?php

namespace Maantje\Charts\HorizontalBar;

use Maantje\Charts\Chart;
use Maantje\Charts\Serie;

class HorizontalBars extends Serie
{
    /**
     * @param  HorizontalBarContract[]  $bars
     */
    public function __construct(
        protected array $bars = [],
        public ?string $yAxis = null,
    ) {
        parent::__construct($yAxis);
    }

    public function maxValue(): float
    {
        if (count($this->bars) === 0) {
            return 0;
        }

        return max(array_map(fn (HorizontalBarContract $data) => $data->maxValue(), $this->bars));
    }

    public function minValue(): float
    {
        if (count($this->bars) === 0) {
            return 0;
        }

        return min(array_map(fn (HorizontalBarContract $data) => $data->minValue(), $this->bars));
    }

    /**
     * @return string[]
     */
    public function categories(): array
    {
        return array_map(fn (HorizontalBarContract $bar) => $bar->label() ?? '', $this->bars);
    }

    public function maxValueForAxis(string $axis): ?float
    {
        $values = array_values(array_filter(
            array_map(fn (HorizontalBarContract $bar) => $bar->maxValueForAxis($axis, $this->yAxis), $this->bars),
            fn (?float $value) => $value !== null
        ));

        if (count($values) === 0) {
            return null;
        }

        return max($values);
    }

    public function minValueForAxis(string $axis): ?float
    {
        $values = array_values(array_filter(
            array_map(fn (HorizontalBarContract $bar) => $bar->minValueForAxis($axis, $this->yAxis), $this->bars),
            fn (?float $value) => $value !== null
        ));

        if (count($values) === 0) {
            return null;
        }

        return min($values);
    }

    public function render(Chart $chart): string
    {
        $numBars = count($this->bars);

        if ($numBars === 0) {
            return '';
        }

        $maxBarHeight = $chart->availableHeight() / $numBars;
        $y = $chart->top();
        $svg = '';

        foreach ($this->bars as $bar) {
            $svg .= $bar->render($chart, $y, $maxBarHeight, $this->yAxis);
            $y += $maxBarHeight;
        }

        return $svg;
    }
}
