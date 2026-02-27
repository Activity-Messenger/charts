<?php

namespace Maantje\Charts;

use Maantje\Charts\HorizontalBar\HorizontalBars;
use Maantje\Charts\SVG\Line;
use Maantje\Charts\SVG\Text;

class HorizontalBarChart extends Chart
{
    public function render(): string
    {
        $this->resetMargins();

        return <<<SVG
            <svg xmlns="http://www.w3.org/2000/svg" width="$this->width" height="$this->height" viewBox="$this->viewBox">
                {$this->background()}
                {$this->renderValueAxis()}
                {$this->renderCategoryAxis()}
                {$this->renderHorizontalGrid()}
                {$this->renderSeries()}
            </svg>
            SVG;
    }

    protected function defaultYAxisPosition(int $index): string
    {
        return $index === 0 ? 'bottom' : 'top';
    }

    protected function resetMargins(): void
    {
        $this->leftMargin = $this->initialLeftMargin;
        $this->rightMargin = $this->initialRightMargin;
        $this->topMargin = $this->initialTopMargin;
        $this->bottomMargin = $this->initialBottomMargin;
    }

    protected function renderValueAxis(): string
    {
        $svg = '';
        $lines = max(1, $this->grid->lines);

        foreach ($this->yAxis as $name => $axis) {
            $isTop = $axis->position === 'top';
            $axisHeight = $axis->fontSize + ($axis->title !== '' ? 34 : 20);

            if ($isTop) {
                $this->incrementTopMargin($axisHeight);
            } else {
                $this->incrementBottomMargin($axisHeight);
            }

            $y = $isTop ? $this->top() : $this->bottom();
            $svg .= new Line(
                x1: $this->left(),
                y1: $y,
                x2: $this->right(),
                y2: $y,
                stroke: $axis->color ?? $this->color,
            );

            $min = $this->minValue($name);
            $max = $this->maxValue($name);
            $step = ($max - $min) / $lines;

            for ($i = 0; $i <= $lines; $i++) {
                $value = $min + ($step * $i);
                $x = $this->xForAxis($value, $name);
                $tickEnd = $isTop ? $y - 5 : $y + 5;
                $labelY = $isTop ? $y - 10 : $y + 20;

                $svg .= new Line(
                    x1: $x,
                    y1: $y,
                    x2: $x,
                    y2: $tickEnd,
                    stroke: $axis->color ?? $this->color,
                );

                $svg .= new Text(
                    content: $axis->formatter->call($axis, $value),
                    x: $x,
                    y: $labelY,
                    fontFamily: $axis->fontFamily ?? $this->fontFamily,
                    fontSize: $axis->fontSize ?? $this->fontSize,
                    fill: $axis->color ?? $this->color,
                    textAnchor: 'middle'
                );
            }

            $titleY = $isTop ? $y - 30 : $y + 40;
            $titleX = $this->left() + ($this->availableWidth() / 2);

            $svg .= new Text(
                content: $axis->title,
                x: $titleX,
                y: $titleY,
                fontFamily: $axis->fontFamily ?? $this->fontFamily,
                fontSize: $axis->fontSize ?? $this->fontSize,
                fill: $axis->color ?? $this->color,
                textAnchor: 'middle'
            );
        }

        return $svg;
    }

    protected function renderCategoryAxis(): string
    {
        $series = $this->primaryHorizontalSeries();

        if (is_null($series)) {
            return '';
        }

        $categories = $series->categories();

        if (count($categories) === 0) {
            return '';
        }

        $longestLabel = max(array_map('strlen', $categories));
        $labelWidth = $longestLabel * 5 + 15;
        $this->incrementLeftMargin($labelWidth + 10);

        $slotHeight = $this->availableHeight() / count($categories);
        $svg = '';

        for ($i = 0; $i < count($categories); $i++) {
            $y = $this->top() + ($i * $slotHeight) + ($slotHeight / 2);
            $x = $this->left();

            $svg .= new Line(
                x1: $x - 5,
                y1: $y,
                x2: $x,
                y2: $y,
                stroke: $this->color
            );

            $svg .= new Text(
                content: $categories[$i],
                x: $x - 10,
                y: $y,
                fontFamily: $this->fontFamily,
                fontSize: $this->fontSize,
                fill: $this->color,
                textAnchor: 'end',
                dominantBaseline: 'middle',
                alignmentBaseline: 'middle'
            );
        }

        return $svg;
    }

    protected function renderHorizontalGrid(): string
    {
        if (count($this->yAxis) === 0) {
            return '';
        }

        $axis = reset($this->yAxis);

        if (! $axis instanceof YAxis) {
            return '';
        }

        $lines = max(1, $this->grid->lines);
        $min = $this->minValue($axis->name);
        $max = $this->maxValue($axis->name);
        $step = ($max - $min) / $lines;
        $svg = '';

        for ($i = 0; $i <= $lines; $i++) {
            $x = $this->xForAxis($min + ($step * $i), $axis->name);
            $svg .= new Line(
                x1: $x,
                y1: $this->top(),
                x2: $x,
                y2: $this->bottom(),
                stroke: $this->grid->lineColor ?? $this->color,
                strokeWidth: $this->grid->thickness,
                strokeOpacity: $this->grid->opacity,
            );
        }

        return $svg;
    }

    protected function primaryHorizontalSeries(): ?HorizontalBars
    {
        foreach ($this->series as $series) {
            if ($series instanceof HorizontalBars) {
                return $series;
            }
        }

        return null;
    }
}
