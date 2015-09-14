<?php

ob_start();
$img_path = 'voucher/images/';
$fnt_path = 'voucher/fonts/';
$im = imagecreatefrompng($img_path . "voucher_bg.png");  //GET MAIN IMAGE FROM PATH
//$im = imagecreatefrompng($img_path . "voucher_bg_filled.png");  //GET MAIN IMAGE FROM PATH
$m_logo = imagecreatefrompng($img_path . "voucher_m_logo.png"); //GET MERCHANT LOGO FROM PATH
// =============================================================================
$font = $fnt_path . "roboto.ttf";  //SET TEXT FONT FROM PATH
$font = $fnt_path . "arial.ttf";  //SET TEXT FONT FROM PATH
$fontB = $fnt_path . "arialbd.ttf";  //SET TEXT FONT FROM PATH
// =============================================================================
//$qr = imagecreatefrompng($img_path . "qr.png");  //GET QR CODE FROM PATH
// include functions
include_once('voucher/s_functions.php');
// Generate PNG file and get its url
$png_filename = qrcodepng($qr_code);
//$png_filename = FALSE; // For testing only
if ($png_filename != FALSE) {
    $qr = imagecreatefrompng($png_filename);  //GET QR CODE FROM PATH
} else {
    $qr = imagecreatefrompng($img_path . "qr.png");  //GET default QR CODE
}
// =============================================================================
//
// TODO Resapmle merchant and qr images 
//
//
$im_W = imagesx($im);  //MAIN IMAGE WIDTH
$im_H = imagesy($im);  //MAIN IMAGE HEIGHT
$m_logo_W = imagesx($m_logo);   //MERCHANT LOGO WIDTH
$m_logo_H = imagesy($m_logo);  //MERCHANT LOGO HEIGHT
$qr_W = 200; //imagesx($qr);   //QR WIDTH
$qr_H = 200; //imagesy($qr);   //QR HEIGHT
// TODO: $text_color = imagecolorallocate($im, 255, 128, 128);  //SET TEXT COLOR
///////// THE FOLLOWING DATA IS THE DATA THAT WE WILL GET FROM DATABASE /////////////

$m_name = $merchant_business_name;

$m_info = $merchant_business_address1 . '  ' . $merchant_business_phone; //Merchant Address, Merchant Suburb, Merchant Postcode, Merchant Phone (WE WILL CONCAT ALL WITH THE SAME WAY AND ASSIGN TO THE VARIABLE)
$amount = g::formatCurrency($voucher_value);
// Date format
$date_issued = g::formatDateTime($delivery_date);
$expiry_date = g::formatDateTime($expiry_date);
//
$voucher_code = g::formatVoucherCode($qr_code);
$terms = "terms of use terms of use terms of use terms of use terms of use terms of use terms of use
terms of use terms of use terms of use terms of use terms of use terms of use terms of use
terms of use terms of use terms of use terms of use terms of use terms of use terms of use";

///////////////////////////////////////////////////////////////////////////////////////
//////////////////////JUST CALCULATIONS TO JUSTIFY TEXT/////////////////////////
//
//$merchant_name_text_width = $m_name_bbox[2]-$m_name_bbox[0];
//$merchant_name_text_height = $m_name_bbox[3]-$m_name_bbox[1];
//
$im = g::writeOnImage($im, $m_name, $fontB, array(
            'font_size' => 35,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 40,
        ));

$im = g::writeOnImage($im, $m_info, $font, array(
            'font_size' => 15,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 110,
        ));

$im = g::writeOnImage($im, strtoupper($voucher_title), $fontB, array(
            'font_size' => 40,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 160,
        ));

$im = g::writeOnImage($im, 'Amount: ' . $amount, $fontB, array(
            'font_size' => 25,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 225,
        ));

$im = g::writeOnImage($im, 'Date Issued: ' . $date_issued . '       Expiry Date: ' . $expiry_date, $font, array(
            'font_size' => 14,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 285,
        ));

$im = g::writeOnImage($im, $voucher_code, $fontB, array(
            'font_size' => 40,
            'margin_left' => 270,
            'text_valign' => 'top',
            'margin_top' => 320,
        ));

$im = g::writeOnImage($im, $terms, $font, array(
            'font_size' => 10,
            'margin_left' => 270,
            'text_align' => 'left',
            'text_valign' => 'top',
            'margin_top' => 390,
        ));

/////////////////////////////////////////////////////////////////////////////
/////////////////////////WRITE TEXTS ON THE IMAGE//////////////////////////////
//array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
//imagettftext($im, 35, 0, $merchant_name_x, $merchant_name_y, $text_color, $font, $m_name);
//imagettftext($im, 15, 0, 650, 250, $text_color, $font, $v_expiry_date);
//////////////////////////////////////////////////////////////////////////////////
//////////////ADD MERCHANT LOGO TO IMAGE///////////
imagecopy($im, $m_logo, 40, 40, 0, 0, $m_logo_W, $m_logo_H);

//////////////ADD QR CODE TO IMAGE////////////////
imagecopy($im, $qr, 40, 260, 0, 0, $qr_W, $qr_H);

imagepng($im); //DISPLAY THE IMAGE
//DISPLAY THE IMAGE IN THE SAME PAGE WITHOUT ENCODED DATA
$imagedata = ob_get_contents();
ob_end_clean();
$imagedata = base64_encode($imagedata);
//
//VIEW IMAGE
echo '<img style="" src="data:image/png;base64,' . $imagedata . '" alt="Image" />';

?>


