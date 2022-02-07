<?php

namespace App\Service\Generator;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

//use App\Repository\ConfigurationVarRepository;

class QrcodeService
{

    protected $builder;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function qrcode($url)
    {
        //$url = 'https://www.google.com/search?q=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');

        $path = dirname(__DIR__, 1).'\public\uploads';

        // set qrcode
        $result = $this->builder
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->labelAlignment(new LabelAlignmentCenter())
            ->labelMargin(new Margin(15, 5, 5, 5))
            ->logoPath($path.'\logo.png')
            ->logoResizeToWidth('100')
            ->logoResizeToHeight('100')
            ->backgroundColor(new Color(221, 158, 3))
            ->build()
        ;

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile($path."/qrCode".$namePng);

        return $result->getDataUri();
    }
}