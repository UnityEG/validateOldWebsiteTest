<?php
ob_start();
$img_path = 'voucher/images/';
$fnt_path = 'voucher/fonts/';
$im = imagecreatefrompng($img_path . "1.png");  //GET MAIN IMAGE FROM PATH
$mlogo = imagecreatefrompng($img_path . "mlogo.png"); //GET MERCHANT LOGO FROM PATH
// =============================================================================
//$qr = imagecreatefrompng($img_path . "qr.png");  //GET QR CODE FROM PATH
// include functions
include_once('voucher/s_functions.php');
// Generate PNG file and get its url
$png_filename = qrcodepng($qr_code);
//$png_filename = FALSE; // For testing only
if ($png_filename != FALSE) {
	$qr = imagecreatefrompng($png_filename);  //GET QR CODE FROM PATH
}else{
	$qr = imagecreatefrompng($img_path . "qr.png");  //GET default QR CODE
}
// =============================================================================

$image_width = imagesx($im);  //MAIN IMAGE WIDTH
$image_height = imagesy($im);  //MAIN IMAGE HEIGHT
$mlogo_width = imagesx($mlogo);   //MERCHANT LOGO WIDTH
$mlogo_height = imagesy($mlogo);  //MERCHANT LOGO HEIGHT
$qr_width  = 175; //imagesx($qr);   //QR WIDTH
$qr_height = 175; //imagesy($qr);   //QR HEIGHT
$text_color = imagecolorallocate($im, 0, 0, 0);  //SET TEXT COLOR
$font = $fnt_path . "roboto.ttf";  //SET TEXT FONT FROM PATH

///////// THE FOLLOWING DATA IS THE DATA THAT WE WILL GET FROM DATABASE /////////////

$merchant_name = $merchant_business_name;
$merchant_info = $merchant_business_address1; //Merchant Address, Merchant Suburb, Merchant Postcode, Merchant Phone (WE WILL CONCAT ALL WITH THE SAME WAY AND ASSIGN TO THE VARIABLE)
$amount = "$" . number_format($voucher_value, 2, '.', ',');
// Date Handling
$date_issued   = g::formatDateTime($delivery_date);
$v_expiry_date = g::formatDateTime($expiry_date);
//
$voucher_code = substr($qr_code, 0, 3) . ' - ' . substr($qr_code, 3, 3) . ' - ' . substr($qr_code, 6, 3);
$fine_print = "terms of use terms of use terms of use terms of use terms of use terms of use
terms of use terms of use terms of use terms of use terms of use terms of use
terms of use terms of use terms of use terms of use terms of use terms of use";

////////////////////////////////////////////////////////////////////////////////////////


//////////////////////JUST CALCULATIONS TO JUSTIFY TEXT/////////////////////////
$merchant_name_text_box = imagettfbbox(45,0,$font,$merchant_name);
$merchant_name_text_width = $merchant_name_text_box[2]-$merchant_name_text_box[0];
$merchant_name_text_height = $merchant_name_text_box[3]-$merchant_name_text_box[1];
$merchant_name_x = ($image_width/2) - ($merchant_name_text_width/2) +(($image_width/2) - ($merchant_name_text_width/2))/2.5;
$merchant_name_y = 60;

$merchant_info_text_box = imagettfbbox(15,0,$font,$merchant_info);
$merchant_info_text_width = $merchant_info_text_box[2]-$merchant_info_text_box[0];
$merchant_info_text_height = $merchant_info_text_box[3]-$merchant_info_text_box[1];
$merchant_info_x = ($image_width/2) - ($merchant_info_text_width/2) +(($image_width/2) - ($merchant_info_text_width/2))/1.6;
$merchant_info_y = 100;
/////////////////////////////////////////////////////////////////////////////



/////////////////////////WRITE TEXTS ON THE IMAGE//////////////////////////////
//array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
imagettftext($im, 35, 0, $merchant_name_x, $merchant_name_y, $text_color, $font, $merchant_name);
imagettftext($im, 15, 0, $merchant_info_x, $merchant_info_y, $text_color, $font, $merchant_info);
imagettftext($im, 30, 0, 410, 150, $text_color, $font, 'Gift Voucher');
imagettftext($im, 25, 0, 390, 220, $text_color, $font, 'Amount: ' . $amount);
imagettftext($im, 14, 0, 230, 260, $text_color, $font, 'Date Issued: ' . $date_issued . '       Expiry Date: ' . $v_expiry_date);
//imagettftext($im, 15, 0, 650, 250, $text_color, $font, $v_expiry_date);
imagettftext($im, 30, 0, 390, 310, $text_color, $font, $voucher_code);
imagettftext($im, 10, 0, 225, 370, $text_color, $font, $fine_print);
//////////////////////////////////////////////////////////////////////////////////


//////////////ADD MERCHANT LOGO TO IMAGE///////////
imagecopy($im,$mlogo, 20, 20, 0, 0, $mlogo_width, $mlogo_height);

//////////////ADD QR CODE TO IMAGE////////////////
imagecopy($im,$qr, 20, 220, 0, 0, $qr_width, $qr_height);


imagepng($im); //DISPLAY THE IMAGE


//DISPLAY THE IMAGE IN THE SAME PAGE WITHOUT ENCODED DATA
$imagedata = ob_get_contents();
ob_end_clean();
$imagedata = base64_encode($imagedata);

//VIEW IMAGE
echo '<img style="" src="data:image/png;base64,' . $imagedata . '" alt="Image" />';
?>


