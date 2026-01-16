<?php

namespace PHPMaker2024\mandrake;

use Spatie\Color\Hex;
use Spatie\Color\Rgb;
use Spatie\Color\Hsl;

/**
 * CAPTCHA class
 */
class PhpCaptcha extends CaptchaBase
{
    public static $BackgroundColor = "FFFFFF"; // Hex string
    public static $TextColor = "003359"; // Hex string
    public static $NoiseColor = "64A0C8"; // Hex string
    public static $DarkTextColor = ""; // Hex string
    public static $DarkBackgroundColor = "212529"; // Hex string
    public static $DarkNoiseColor = ""; // Hex string
    public static $DefaultAdjust = 30;
    public static $Width = 250;
    public static $Height = 50;
    public static $Characters = 6;
    public static $FontSize = 0;
    public static $Font = "monofont";
    public $Response = "";
    public $ResponseField = "captcha";
    public $Image;
    public $DarkImage;

    /**
     * Constructor
     *
     * @param string $Font Font file name
     */
    public function __construct()
    {
        if (self::$FontSize <= 0) {
            self::$FontSize = $this->getHeight() * 0.55;
        }
    }

    /**
     * Generate code
     *
     * @param int $Characters Number of characters
     * @return string
     */
    protected function generateCode($Characters)
    {
        $possible = "23456789BCDFGHJKMNPQRSTVWXYZ"; // Possible characters
        $code = "";
        $i = 0;
        while ($i < $Characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible) - 1), 1);
            $i++;
        }
        return $code;
    }

    /**
     * Convert hex to RGB
     *
     * @param string $hexstr Hex string
     * @return Rgba Color
     */
    protected function hexToRgb($hexstr)
    {
        if (!str_starts_with($hexstr, "#")) {
            $hexstr = "#" . $hexstr;
        }
        return Hex::fromString($hexstr)->toRgb();
    }

    /**
     * Adjust lightness (Darken/Lighten) a HSL value
     *
     * @param Hsl $hsl Hsl value
     * @param ?int $amount
     * @return Hsl
     */
    private function adjustLightness(Hsl $hsl, ?int $amount = null): Hsl
    {
        $amount ??= self::$DefaultAdjust;
        $lightness = $hsl->lightness();
        $lightness = $lightness + $amount;
        $lightness = ($lightness < 0) ? 0 : $lightness;
        $lightness = ($lightness > 100) ? 100 : $lightness;
        return new Hsl($hsl->hue(), $hsl->saturation(), $lightness);
    }

    /**
     * Output image
     *
     * @return string Code
     */
    public function show()
    {
        $code = $this->generateCode(self::$Characters);
        $oriCode = $code;
        $code = "";
        $len = strlen($oriCode);
        for ($i = 0; $i < $len; $i++) {
            $code .= $oriCode[$i];
            if ($i < $len - 1) {
                $code .= " ";
            }
        }
        $code = trim($code);
        $width = $this->getWidth();
        $height = $this->getHeight();
        try {
            $image = imagecreatetruecolor($width, $height * 2);
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Cannot initialize new GD image stream - " . $e->getMessage());
        }
        $rgb = $this->hexToRgb(self::$BackgroundColor);
        $backgroundColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        imagefill($image, 0, 0, $backgroundColor);
        $rgb = $this->hexToRgb(self::$DarkBackgroundColor);
        $backgroundColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        imagefilledrectangle($image, 0, $height, $width, $height * 2, $backgroundColor);
        $rgb = $this->hexToRgb(self::$TextColor);
        $textColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        $rgb = self::$DarkTextColor
            ? $this->hexToRgb(self::$DarkTextColor)
            : $this->adjustLightness($rgb->toHsl(), self::$DefaultAdjust)->toRgb(); // Increase lightness for dark mode
        $darkTextColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        $rgb = $this->hexToRgb(self::$NoiseColor);
        $noiseColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        $rgb = self::$DarkNoiseColor
            ? $this->hexToRgb(self::$DarkNoiseColor)
            : $this->adjustLightness($rgb->toHsl(), self::$DefaultAdjust * -1)->toRgb(); // Decrease lightness for dark mode
        $darkNoiseColor = imagecolorallocate($image, $rgb->red(), $rgb->green(), $rgb->blue());
        // Generate random dots in background
        for ($i = 0; $i < ($width * $height) / 3; $i++) {
            $centerX = mt_rand(0, $width);
            $centerY = mt_rand(0, $height);
            imagefilledellipse($image, $centerX, $centerY, 1, 1, $noiseColor);
            imagefilledellipse($image, $centerX, $centerY + $height, 1, 1, $darkNoiseColor);
        }
        // Generate random lines in background
        for ($i = 0; $i < ($width * $height) / 150; $i++) {
            $x1 = mt_rand(0, $width);
            $y1 = mt_rand(0, $height);
            $x2 = mt_rand(0, $width);
            $y2 = mt_rand(0, $height);
            imageline($image, $x1, $y1, $x2, $y2, $noiseColor);
            imageline($image, $x1, $y1 + $height, $x2, $y2 + $height, $darkNoiseColor);
        }
        $fontFile = self::$Font;
        // Always use full path
        if (!ContainsString($fontFile, ".")) {
            $fontFile .= ".ttf";
        }
        $fontFile = IncludeTrailingDelimiter(Config("FONT_PATH"), true) . $fontFile;
        // Create textbox and add text
        try {
            $textBox = imagettfbbox(self::$FontSize, 0, $fontFile, $code);
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Error in imagettfbbox function - " . $e->getMessage());
        }
        $x = ($width - $textBox[4]) / 2;
        $y = ($height - ($textBox[5] - $textBox[3])) / 2;
        try {
            imagettftext($image, self::$FontSize, 0, intval($x), intval($y), $textColor, $fontFile, $code);
            imagettftext($image, self::$FontSize, 0, intval($x), intval($y + $height), $darkTextColor, $fontFile, $code);
        } catch (\Exception $e) {
            throw new \Exception("PhpCaptcha: Error in imagettfbbox function - " . $e->getMessage());
        }
        // Output captcha image to browser
        if (ob_get_length()) { // Clean buffer
            ob_end_clean();
        }
        ob_start();
        AddHeader("Content-Type", "image/png");
        imagepng($image);
        $data = ob_get_contents();
        ob_end_clean();
        Write($data);
        imagedestroy($image);
        return $oriCode;
    }

    // Width
    public function getWidth()
    {
        return self::$Width;
    }

    // Height
    public function getHeight()
    {
        return self::$Height;
    }

    // HTML tag
    public function getHtml()
    {
        global $Language, $Page;
        $classAttr = $Page->OffsetColumnClass ? ' class="' . $Page->OffsetColumnClass . '"' : "";
        $class = $this->getFailureMessage() != "" ? " is-invalid" : "";
        $url = GetUrl("captcha/" . $Page->PageID);
        $width = $this->getWidth();
        $height = $this->getHeight() - 1; // Make sure the clipped area does not contain the other part
        return <<<EOT
            <div class="row ew-captcha">
                <div{$classAttr}>
                    <p><img src="{$url}" alt="" class="ew-captcha-image"></p>
                    <input type="text" name="{$this->getElementName()}" id="{$this->getElementId()}" class="form-control ew-form-control{$class}" size="30" placeholder="{$Language->phrase("EnterValidateCode")}">
                    <div class="invalid-feedback">{$this->getFailureMessage()}</div>
                </div>
            </div>
            <style>
            .ew-captcha-image { width: {$width}px; height: {$height}px; object-fit: cover; object-position: top; }
            [data-bs-theme="dark"] .ew-captcha-image { object-position: bottom; }
            </style>
            EOT;
    }

    // HTML tag for confirm page
    public function getConfirmHtml()
    {
        return '<input type="hidden" name="' . $this->getElementName() . '" id="' . $this->getElementId() . '" value="' . HtmlEncode($this->Response) . '">';
    }

    // Validate
    public function validate()
    {
        $sessionName = $this->getSessionName();
        return ($this->Response == Session($sessionName));
    }

    // Client side validation script
    public function getScript()
    {
        return '.addField("' . $this->getElementName() . '", ew.Validators.captcha, ' . ($this->getFailureMessage() != '' ? 'true' : 'false') . ')';
    }
}
