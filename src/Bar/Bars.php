<?php

namespace Maantje\Charts\Bar;

use Maantje\Charts\Chart;
use Maantje\Charts\Serie;

class Bars extends Serie
{
    /**
     * @param  BarContract[]  $bars
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

        return max(array_map(fn (BarContract $data) => $data->maxValue(), $this->bars));
    }

    public function minValue(): float
    {
        if (count($this->bars) === 0) {
            return 0;
        }

        return min(array_map(fn (BarContract $data) => $data->minValue(), $this->bars));
    }

    public function maxValueForAxis(string $axis): ?float
    {
        $values = array_values(array_filter(
            array_map(fn (BarContract $bar) => $bar->maxValueForAxis($axis, $this->yAxis), $this->bars),
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
            array_map(fn (BarContract $bar) => $bar->minValueForAxis($axis, $this->yAxis), $this->bars),
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

        $maxBarWidth = 0;

        if ($numBars > 0) {
            $maxBarWidth = $chart->availableWidth() / $numBars;
        }

        $x = $chart->left();

        $svg = '';

        foreach ($this->bars as $bar) {
            $svg .= $bar->render($chart, $x, $maxBarWidth, $this->yAxis);

            $x += $maxBarWidth;
        }

        return $svg;
    }
}
