<?php

/**
 * Description of UserPicController
 *
 * @author mohamed
 */
class UserPicController extends \BaseController {
    
    public static $defaultImagesPath = '/images/defaults';


    public function index( ) {
        return View::make('users.userpics.index');
    }
    
    public function store( ) {
        $inputs = Input::all();
        
        if ( Input::hasFile( 'default_gift_voucher_image') ) {
            $upload_image_options = array(
                    'user_id'    => $this->auth_user->id,
                    'image'      => Input::file( 'default_gift_voucher_image'),
                    'path'       => public_path().self::$defaultImagesPath,
                    'type'       => 'default_gift_voucher_image',
                    'active_pic' => 0
                );
                if ( $this->saveImage( $upload_image_options ) ) {
                    return Redirect::route('userpics.index')->withMessage('Image uploded succsfully');
                }else{
                    return Redirect::back()->withError('Error while uploading Image');
                }
        }//if ( Input::hasFile( 'default_gift_voucher_image') )
        
//        if ( isset( $inputs[ 'active_default_voucher_image' ] ) ) {
//                $images = UserPic::where( 'type', '=', 'default_gift_voucher_image' )->get();
//                foreach ( $images as $image ) {
//                    $image->active_pic = 0;
//                    $image->save();
//                }//foreach ( $images as $image)
//                $image_to_active             = UserPic::find( $inputs[ 'active_default_voucher_image' ] );
//                $image_to_active->active_pic = 1;
//                $image_to_active->save();
//            }//if ( isset( $inputs[ 'active_default_voucher_image' ] ) )
            
            if ( isset( $inputs[ 'delete_default_voucher_image' ] ) ) {
                foreach ( $inputs[ 'delete_default_voucher_image' ] as $image_id ) {
                    $this->deleteImage( $image_id, public_path().self::$defaultImagesPath );
                }//foreach ( $inputs['delete_logo'] as $logo_id)
            }//if ( isset($inputs['delete_logo']) )
            
        return Redirect::route('userpics.index')->withMessage('Done');
    }
    

//    Helper Methods
    
    /**
     * save Image in the database, resize Image, and save the image in the destination directory
     * @param array $upload_image_options
     * @return mix
     */
    public function saveImage( $upload_image_options ) {

        $new_pic = new UserPic;

        $validator = Validator::make( $upload_image_options, $new_pic->create_rules );

        if ( $validator->passes() ) {

            if ( $upload_image_options[ 'image' ]->isValid() ) {
//                prepare image data
                $image = $upload_image_options[ 'image' ];

                $path = $upload_image_options[ 'path' ];

                $image_name = $this->generateFilename();

                $extension = $image->getClientOriginalExtension();
                
                $resized_image = Image::make($image)->resize(310, 195);
                
//                prepare data to be stored in the database
                $new_pic->user_id = $upload_image_options[ 'user_id' ];

                $new_pic->pic = $image_name;

                $new_pic->extension = $extension;

                $new_pic->type = $upload_image_options[ 'type' ];

                $new_pic->active_pic = $upload_image_options[ 'active_pic' ];

                if ( $resized_image->save( $path.'/'.$image_name . '.' . $extension ) ) {

                    if ( $new_pic->save() ) {
                        return TRUE;
                    }//if($new_pic->save())
                    else {
                        return 'Error while saving image!';
                    }
                }//if($image->move( $path, $image_name . '.' . $extension )  )
                else {
                    return 'Error while saving image!';
                }
            }//if ( $upload_image_options['pic']->isValid() )
            else {
                return $validator;
            }
        }//if ( $validator->passes() )
        else {
            return $validator;
        }
    }
    
    /**
     * delete image info from database and delete image file from the destination
     * @param integer $id
     * @param string $path
     * @return boolean
     */
    public function deleteImage( $id, $path ) {
        $image         = UserPic::findOrFail( $id );
        $full_pic_path = $path . '/' . $image->pic . '.' . $image->extension;
        File::delete( $full_pic_path );
        
        return ($image->delete()) ? TRUE : false;
    }

    /**
     * generate file name according to the column name
     * @return string
     */
    private function generateFilename() {
        $filename = mt_rand( 10000001, 99999999 ); // better than rand()
        // call the same function if the $filename exists already
        if ( UserPic::where( 'pic', '=', $filename )->exists() ) {
            return $this->generateFilename();
        }
        if ( strlen( $filename ) != 8 ) {
            return $this->generateFilename();
        }
        return $filename;
    }

}
