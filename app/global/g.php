<?php

class g {

    public static function has($rule_name = null) {
        if (is_object(Auth::user()) && Auth::user()->hasRule($rule_name)) {
            return true;
        } else {
            return false;
        }
    }

    public static function back($msg = 'You do not have this permission') {
        return Redirect::back()->withError($msg);
    }

    public static function backForRule($rule_name = null) {
        $msg = 'You do not have this permission: [' . $rule_name . ']';
        return Redirect::back()->withError($msg);
    }

    public static function login($msg = 'You do not have this permission') {
        return Redirect::to('login')->withError($msg);
    }

    public static function isDeveloper() {
        if (is_object(Auth::user()) && Auth::user()->user_type == 'developer') {
            return true;
        } else {
            return false;
        }
    }

    public static function formatVoucherCode($voucher_code) {
        $str = substr($voucher_code, 0, 3) . ' - ' . substr($voucher_code, 3, 3) . ' - ' . substr($voucher_code, 6, 3);
        return $str;
    }

    public static function formatCurrency($value) {
        $str = "$" . number_format($value, 2, '.', ',');
        return $str;
    }

    public static function formatDateTime($utc_date_time, $local_time_zone = 'Pacific/Auckland') {
        //
        return g::fDT($utc_date_time, 'd/m/Y H:i:s', $local_time_zone);
    }

    public static function formatDate($utc_date_time, $local_time_zone = 'Pacific/Auckland') {
        //
        return g::fDT($utc_date_time, 'd/m/Y', $local_time_zone);
    }

    public static function formatTime($utc_date_time, $local_time_zone = 'Pacific/Auckland') {
        //
        return g::fDT($utc_date_time, 'H:i:s', $local_time_zone);
    }

    public static function utcDateTime($local_date_time, $format = 'd/m/Y', $local_time_zone = 'Pacific/Auckland') {
        //
        return Carbon::createFromFormat($format, $local_date_time, $local_time_zone)->setTimezone('UTC'); //->startOfDay();
    }

    private static function fDT($utc_date_time, $format, $local_time_zone) {
        //
        // formatDateTime
        /*
          $date_array = explode("-", $utc_date_time);
          $str = substr($date_array[2], 0, 2) . '/' . $date_array[1] . '/' . $date_array[0] . ' ' . substr($date_array[2], 3);
          echo $str . '<br />';
         */
        //
        //dd($utc_date_time);
        //

        $local = Carbon::parse($utc_date_time, 'UTC')->timezone($local_time_zone);
        $str = $local->format($format);
        //
        return $str;
        /*
         * For Debugging
         * 
          $UTC = Carbon::parse($utc_date_time, 'UTC');
          $EGYPT = Carbon::parse($utc_date_time, 'UTC')->timezone('Africa/Cairo');
          //
          echo 'DB   : <b>'.$utc_date_time.'</b>'.'<br />';
          echo 'UTC:   ' . $UTC->toDateTimeString();
          echo '<br />';
          echo 'Local: ' . $local->toDateTimeString();
          echo '<br />';
          echo 'UTC:   ' . $UTC->toDateTimeString();
          echo '<br />';
          echo 'EGYPT:   ' . $EGYPT->toDateTimeString();
          echo '<hr />';
         */
    }

    public static function writeOnImage_old($im, $text, $font, $options = '') {
        //
        $font_size = (isset($options['font_size'])) ? $options['font_size'] : 45;
        //
        $margin_left = (isset($options['margin_left'])) ? $options['margin_left'] : 0;
        $margin_right = (isset($options['margin_right'])) ? $options['margin_right'] : 0;
        $margin_top = (isset($options['margin_top'])) ? $options['margin_top'] + $font_size : $font_size;
        $margin_bottom = (isset($options['margin_bottom'])) ? $options['margin_bottom'] : 0;
        //
        $text_align = (isset($options['text_align'])) ? $options['text_align'] : 'center';
        $text_valign = (isset($options['text_valign'])) ? $options['text_valign'] : 'middle';
        //
        $text_color = imagecolorallocate($im, 0, 0, 0);
        //
        // Create bounding box for the text
        // array imagettfbbox ( float $font_size , float $angle , string $fontfile , string $text )
        $bbox = imagettfbbox($font_size, 0, $font, $text);
        // Calculate X and Y cordinates
        //
        switch ($text_align) {
            case 'left':
                $X = $margin_left;
                break;
            //
            default:
                $X = $margin_left + (imagesx($im) - $margin_left - $margin_right - ($bbox[2] - $bbox[0])) / 2 - 5;
                break;
        }
        //
        switch ($text_valign) {
            case 'top':
                $Y = $margin_top;
                break;
            //
            default:
                $Y = $margin_top + (imagesy($im) - $margin_top - $margin_bottom - ($bbox[3] - $bbox[1])) / 2;
                break;
        }
        //
        //$m_name_Y = 60;
        //
        imagettftext($im, $font_size, 0, $X, $Y, $text_color, $font, $text);
        //
        return $im;
    }

    public static function writeOnImage($img, $textData, $x, $y, $w, $h) {
        // 
        // create a new empty image resource with transparent background
        $text = Image::canvas($w, $h);
//        $text = Image::canvas($w, $h, array(255, 50, 50, 0.5)); // Display text block background - Testing only
        //
        // =====================================================================
        // Set Values
        $textData['align'] = (isset($textData['align'])) ? $textData['align'] : 'center';
        $textData['valign'] = (isset($textData['valign'])) ? $textData['valign'] : 'middle';
        // Her. align
        switch ($textData['align']) {
            case 'left':
                $text_x = 0;
                break;
            case 'right':
                $text_x = $w - 5;
                break;
            default: // 'center'
                $text_x = $w / 2;
                break;
        }
        // Ver. align
        switch ($textData['valign']) {
            case 'top':
                $text_y = 0;
                break;
            case 'bottom':
                $text_y = $h - 5;
                break;
            default: // 'middle'
                $text_y = $h / 2;
                break;
        }
        // 
        // =====================================================================
        // Add text to canvas
        $text->text($textData['text'], $text_x, $text_y, function($font) use ($textData) {
            $font->file($textData['font']);
            $font->size($textData['size']);
            $font->color('#000000');
            $font->align($textData['align']);
            $font->valign($textData['valign']);
            $font->angle(0);
        });
        // 
        // insert text image into Voucher image
        $img->insert($text, 'top-left', $x, $y);
        // 
        return $img;
    }

    public static function QRcode($code) {
        //
        include_once(public_path('voucher/phpqrcode/qrlib.php'));
        //
        $image_name = md5($code) . '.png';
        $filename = public_path($image_name);
        //
        // Generate PNG file
        QRcode::png($code, $filename, QR_ECLEVEL_H, 4);
        //
        // return PNG file name
        if (file_exists($filename)) {
            //
            // Create image resource from $filename
            $image = imagecreatefrompng($filename);
            //
            // Begin capturing the byte stream
            ob_start();
            //
            // generate the byte stream
            imagepng($image);
            //
            // and finally retrieve the byte stream
            $imagedata = ob_get_clean();
            //
            $img_tag = '<img src="data:image/png;base64,' . base64_encode($imagedata) . '" alt="' . $code . '" />';
            //
            // for Security delete image file from public folder
            unlink($filename);
            //
            return $imagedata;
            //return $img_tag;
        } else {
            return FALSE;
        }
    }

    public static function voucher($data) {
        //
        // Get variable names and values from data array
        extract($data);
        //
        // =============================================================================
        // Set fonts 
        $fontArial = 'voucher/fonts/arial.ttf';
        $fontArialB = 'voucher/fonts/arialbd.ttf';
        //
        // =============================================================================
        // Voucher image template 
        $template_filename = 'voucher/images/voucher_bg.png';
        //$template_filename = 'voucher/images/voucher_bg_filled.png'; // Testing only
        $img = Image::make($template_filename)->resize(1000, 500, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize(); // otherwise images smaller than 200x200 will be stretched.
        });
        //
        // =============================================================================
        // Merchant logo 
        //$m_logo_filename = 'voucher/images/validate_logo.png'; // Testing only
        $logo = Image::make($m_logo_filename)->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
            //$constraint->upsize(); // otherwise images smaller than 200x200 will be stretched.
        });
        //
        // Fill up the blank spaces with transparent color
        $logo->resizeCanvas(200, 200, 'center', false, array(255, 255, 255, .7));
        //
        // insert logo into Voucher image
        $img->insert($logo, 'top-left', 40, 40);
        //
        // =============================================================================
        // Voucher QRcode 
        //$qr_code = 'In the name of ALLAH'; // Testing only
        $QRcode = Image::make(g::QRcode($qr_code))->resize(200, 200, function ($constraint) {
            $constraint->aspectRatio();
        });
        //
        // Fill up the blank spaces with transparent color
        $QRcode->resizeCanvas(200, 200, 'center', false, array(255, 255, 255, .7));
        //
        // insert QRcode into Voucher image
        $img->insert($QRcode, 'top-left', 40, 260);
        //
        // =============================================================================
        // Merchant name
        //$merchant_business_name = 'QUEEN OF JACSON'; // Testing only
        //
        $textData = array(
            'text' => $merchant_business_name,
            'size' => 50,
            'font' => $fontArialB,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 20, 710, 80);
        //
        // =============================================================================
        // Merchant info
        $merchant_info = $merchant_business_address1 . '  ' . $merchant_business_phone;
        //$merchant_info = '181 Jackson Street, Peton, Lower Hutt, 5012   +64 280 9345';  // Testing only
        //
        $textData = array(
            'text' => $merchant_info,
            'size' => 20,
            'font' => $fontArial,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 100, 710, 40);
        //
        // =============================================================================
        // Voucher title
        //$voucher_title = 'Gift Voucher';  // Testing only
        //
        $textData = array(
            'text' => strtoupper($voucher_title),
            'size' => 50,
            'font' => $fontArialB,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 150, 710, 60);
        //
        // =============================================================================
        // Voucher title
        //$voucher_value = 50;  // Testing only
        //
        $textData = array(
            'text' => 'Amount: ' . g::formatCurrency($voucher_value),
            'size' => 33,
            'font' => $fontArialB,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 210, 710, 55);
        //
        // =============================================================================
        // Voucher Dates
        $dates = 'Date Issued: ' . g::formatDate($delivery_date) . '     ' .
                'Expiry Date: ' . g::formatDate($expiry_date);
        //
        $textData = array(
            'text' => $dates,
            'size' => 21,
            'font' => $fontArial,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 265, 710, 45);
        //
        // =============================================================================
        // Voucher Code
        $textData = array(
            'text' => g::formatVoucherCode($qr_code),
            'size' => 60,
            'font' => $fontArialB,
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 310, 710, 60);
        //
        // =============================================================================
        // Terms of use
        //$TermsOfUse = '123456789.123456789.123456789.123456789.123456789.123456789.123456789.123456789.123456789.'; // Testing only
        $textData = array(
            'text' => wordwrap($TermsOfUse, 89, "\n", true),
            'size' => 13,
            'font' => $fontArial,
            'align' => 'left',
            'valign' => 'bottom',
        );
        // writeOnImage($im, $textData, $x, $y, $w, $h) {
        $img = g::writeOnImage($img, $textData, 270, 380, 530, 85);
        //
        // =============================================================================
        // Render img as HTML img tag
        // encode logo to data:image/png;base64,
//        $img->encode('data-url');
//        echo '<img style="" src="' . $img . '" alt="' . $qr_code . '" />';
        //
        // =============================================================================
        // Save img to file and return filename
        $img->encode('png');
        $filename = public_path(md5($qr_code) . '.png');
        $img->save($filename);
        return $filename;
        //==============================================================================
    }

}
