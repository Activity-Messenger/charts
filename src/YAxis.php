<?php

namespace Maantje\Charts;

use Closure;
use Maantje\Charts\Annotations\YAxisAnnotation;
use Maantje\Charts\SVG\Text;

class YAxis implements Renderable
{
    public Closure $formatter;

    /**
     * @param  array<int, YAxisAnnotation&Renderable>  $annotations
     */
    public function __construct(
        public string $name = 'default',
        public string $title = '',
        public ?float $minValue = null,
        public ?float $maxValue = null,
        public ?string $position = null,
        public ?string $color = null,
        public array $annotations = [],
        public int $fontSize = 14,
        public ?string $fontFamily = null,
        public int $labelMargin = 0,
        public int $characterSize = 5,
        ?Closure $formatter = null
    ) {
        $this->formatter = $formatter ?? fn (mixed $label) => number_format($label);
        $this->annotations = array_map(function (YAxisAnnotation $annotation) {
            $annotation->setYAxis($this->name);

            return $annotation;
        }, $this->annotations);
    }

    public function render(Chart $chart): string
    {
        $numLines = $chart->grid->lines;
        $lineSpacing = $chart->availableHeight() / $numLines;
        $svg = '';

        $titleMargin = 35;
        $labelWidth = strlen($this->formatter->call($this, $chart->maxValue($this->name))) * $this->characterSize + $this->labelMargin;
        $isRight = $this->side() === 'right';
        $axisWidth = $labelWidth + $titleMargin;

        if ($isRight) {
            $chart->incrementRightMargin($axisWidth);
        } else {
            $chart->incrementLeftMargin($axisWidth);
        }

        $minValue = $chart->minValue($this->name);
        $maxValue = $chart->maxValue($this->name);

        $valueRange = $maxValue - $minValue;
        $valueStep = $valueRange / $numLines;

        for ($i = 0; $i <= $numLines; $i++) {
            $value = $minValue + ($valueStep * $i);

            $labelText = $this->formatter->call($this, $value);
            $labelX = $isRight ? $chart->right() + 10 : $chart->left() - 10;
            $labelY = $chart->top() + $chart->availableHeight() - ($i * $lineSpacing) + 5;

            $svg .= new Text(
                content: $labelText,
                x: $labelX,
                y: $labelY,
                fontFamily: $this->fontFamily ?? $chart->fontFamily,
                fontSize: $this->fontSize ?? $chart->fontSize,
                fill: $this->color ?? $chart->color,
                textAnchor: $isRight ? 'start' : 'end'
            );
        }

        $titleY = ($chart->availableHeight()) / 2;
        $titleX = $isRight ? $chart->right() + $labelWidth + 25 : $chart->left() - $labelWidth - 25;

        $svg .= new Text(
            content: $this->title,
            x: $titleX,
            y: $titleY,
            fontFamily: $this->fontFamily ?? $chart->fontFamily,
            fontSize: $this->fontSize ?? $chart->fontSize,
            fill: $this->color ?? $chart->color,
            textAnchor: 'middle',
            alignmentBaseline: 'middle',
            transform: $isRight ? "rotate(90, $titleX, $titleY)" : "rotate(270, $titleX, $titleY)"
        );

        return $svg;
    }

    protected function side(): string
    {
        return $this->position === 'right' ? 'right' : 'left';
    }
}
