<?php

class ReportController extends \BaseController {

    private $timeFilters = array(
        ''              => '-',
        'today'         => 'Today',
        'yesterday'     => 'Yesterday',
        'this_week'     => 'This week',
        'last_week'     => 'Last week',
        'this_month'    => 'This month',
        'last_month'    => 'Last month',
        'custom_period' => 'Custom period'
    );

//    Owner reports

    /**
     * Dashboard of Owner
     * @param integer $id
     * @return object
     */
    public function ownerReportIndex(  ) {
        $data  = array();
//        $owner = Owner::find( $id );
//
//        $data[ 'owner' ] = $owner;
        return View::make( 'users.reports.owner.index', compact( 'data' ) );
    }

    /**
     * Owner Sales report
     * @param integer $id
     * @return object|collection
     */
    public function ownerReportSales( ) {
        $data             = array();
        $row_data         = array();
        $total_quantity   = 0;
        $total_value      = 0;
        $total_commission = 0;
        $time_filters = $this->timeFilters;
        $time_filter_applied = '';
        $custom_time_filter_applied = '';
        $inputs = Input::all();
        if ( isset($inputs['time_filter']) && !empty($inputs['time_filter']) ) {
            $time_filter_applied = $inputs['time_filter'];
            $time_filter = $this->reportTimeFilter($inputs['time_filter']);
            if ( 'custom_period' == $inputs['time_filter'] ) {
                $custom_time_filter_applied = "From: " . $inputs[ 'date_from' ] . '   To: ' . $inputs[ 'date_to' ] . '  as   ';
            }//if ( 'custom_period' == $inputs['time_filter'] )
            
        }//if ( isset($inputs['time_filter']) && !empty($inputs['time_filter']) )
        
        $voucher_types    = array( 'Gift Vouchers', 'Deal Vouchers' );
        foreach ( $voucher_types as $voucher_type ) {
            switch ( $voucher_type ) {
                case 'Gift Vouchers':
                    $row_data[ 'voucher_type' ]                = $voucher_type;
//                    online
                    $row_data[ 'online' ]                      = array();
                    $row_data[ 'online' ][ 'voucher_sub_type' ]  = 'Online';
                    if ( isset($time_filter) && (FALSE !== $time_filter) ) {
                        if ( is_array( $time_filter) ) {
                            $row_data[ 'online' ][ 'quantity' ]= GiftVoucher::whereBetween('created_at', $time_filter)->count();
                            $total_quantity += $row_data[ 'online' ][ 'quantity' ];
                            $row_data[ 'online' ][ 'value' ]=  (int)GiftVoucher::whereBetween('created_at', $time_filter)->sum( 'voucher_value' ) ;
                            $total_value += GiftVoucher::whereBetween('created_at', $time_filter)->sum( 'voucher_value' );
                            $row_data[ 'online' ][ 'commission' ]        = 'No available yet';
                            $total_commission += $row_data[ 'online' ][ 'commission' ];
                        }//if ( is_array( $time_filter) )
                        else{
                            $row_data[ 'online' ][ 'quantity' ]= GiftVoucher::where('created_at', '>=', $time_filter)->count();
                            $total_quantity += $row_data[ 'online' ][ 'quantity' ];
                            $row_data[ 'online' ][ 'value' ]=  (int)GiftVoucher::where('created_at', '>=', $time_filter)->sum( 'voucher_value' ) ;
                            $total_value += GiftVoucher::where('created_at', '>=', $time_filter)->sum( 'voucher_value' );
                            $row_data[ 'online' ][ 'commission' ]        = 'No available yet';
                            $total_commission += $row_data[ 'online' ][ 'commission' ];
                        }
                    }//if ( isset($time_filter) && (FALSE !== $time_filter) )
                    else{
                        $row_data[ 'online' ][ 'quantity' ]          = GiftVoucher::count();
                        $total_quantity += $row_data[ 'online' ][ 'quantity' ];
                        $row_data[ 'online' ][ 'value' ]             =  (int)GiftVoucher::sum( 'voucher_value' ) ;
                        $total_value += GiftVoucher::sum( 'voucher_value' );
                        $row_data[ 'online' ][ 'commission' ]        = 'No available yet';
                        $total_commission += $row_data[ 'online' ][ 'commission' ];
                    }
                    
//                    Instore
                    $row_data[ 'instore' ]                     = array();
                    $row_data[ 'instore' ][ 'voucher_sub_type' ] = 'Instore';
                    $row_data[ 'instore' ][ 'quantity' ]         = 'Not available yet';
                    $total_quantity += $row_data[ 'instore' ][ 'quantity' ];
                    $row_data[ 'instore' ][ 'value' ]            = 'Not available yet';
                    $total_value += $row_data[ 'instore' ][ 'value' ];
                    $row_data[ 'instore' ][ 'commission' ]       = 'Not available yet';
                    $total_commission += $row_data[ 'instore' ][ 'commission' ];
                    $data[ 'table_content' ][]                 = $row_data;
                    break;
                case 'Deal Vouchers':
                    $row_data[ 'voucher_type' ]                = $voucher_type;
                    $row_data[ 'quantity' ]                    = 'Not available yet';
                    $total_quantity += $row_data[ 'quantity' ];
                    $row_data[ 'value' ]                       = 0;
                    $total_value += $row_data[ 'value' ];
                    $row_data[ 'commission' ]                  = 'Not available yet';
                    $total_commission += $row_data[ 'commission' ];
                    $data[ 'table_content' ][]                 = $row_data;
                    break;

                default:
                    break;
            }//switch ( $voucher_type )
            $row_data = array();
        }//foreach ( $voucher_types as $voucher_type)
        $row_total_data    = ['total_quantity' => $total_quantity, 'total_value' => $total_value , 'total_commission' => $total_commission  ];
        
        $data[ 'total_row' ] = $row_total_data;
        $data['time_filters'] = $time_filters;
        $data['time_filter_applied'] = $time_filter_applied;
        $data['custom_time_filter_applied'] = $custom_time_filter_applied;
        
        
        return $data ;
    }
    
    /**
     * 
     * @return Json collection
     */
    public function ownerReportSalesHourly( ) {
        $data = array();
        
        $time_auckland = Carbon::today('Pacific/Auckland');
        
        $time_utc = Carbon::today('Pacific/Auckland')->setTimezone('utc');
        
        $time_utc_next = clone $time_utc;
        
        $time_utc_next->addHours(2);

        for ($i = 0; $i <= 12; $i++){
            $graph_data['value'] = GiftVoucher::whereBetween('created_at', array($time_utc, $time_utc_next))->sum( 'voucher_value');
            
            $graph_data['time_auckland'] = (0 == $i) ? $time_auckland->format('h:i A') : $time_auckland->addHours(2)->format('h:i A');
            $time_utc->addHours(2);
            $time_utc_next->addHours(2);
            $data[] = $graph_data;
            $graph_data = [];
        }
//                dd($time_auckland->format('h:i A'));
        
        return $data;
    }

//    Merchant reports
    /**
     * Dashboard of Merchant Reports
     * @param integer $id
     * @return object
     */
    public function merchantReportIndex( $id ) {

        $data = array();

        $merchant = Merchant::find( $id );

        $data[ 'merchant' ] = $merchant;

        return View::make( 'users.reports.merchant.index', compact( 'data' ) );
    }

    /**
     * Active Vouchers report for merchants
     * @param integer $id
     * @return object
     */
    public function merchantReportActiveVouchers( $id ) {

        $inputs = Input::all();

        $data = array();

        $active_gift_vouchers = array();

        $time_filters = $this->timeFilters;
                
        $time_filter_applied = '';

        $custom_time_filter_applied = '';

        $raw_active_gift_vouchers = GiftVouchersParameter::where( 'MerchantID', '=', $id )->whereNull( 'Expires' )->get();
//        time filter
        if ( isset( $inputs[ 'time_filter' ] ) && !empty( $inputs[ 'time_filter' ] ) ) {
            switch ( $inputs[ 'time_filter' ] ):
                case 'today':
                    $today = $this->reportTimeFilter( 'today' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $today_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->where( 'created_at', '>=', $today )->exists();
                        if ( $today_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $today_sold_voucher )
                    }//foreach ( $data as $value )
                    break;

                case 'yesterday':
                    $time_filter = $this->reportTimeFilter( 'yesterday' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $yesterday_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->whereBetween( 'created_at', $time_filter )->exists();
                        if ( $yesterday_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $yesterday_sold_voucher )
                    }//foreach ( $active_gift_vouchers as $voucher)

                    break;

                case'this_week':
                    $time_filter = $this->reportTimeFilter( 'this_week' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $this_week_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->where( 'created_at', '>=', $time_filter )->exists();
                        if ( $this_week_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $this_week_sold_voucher )
                    }//foreach ( $raw_active_gift_vouchers as $voucher)
                    break;

                case 'last_week':
                    $time_filter = $this->reportTimeFilter( 'last_week' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $last_week_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->whereBetween( 'created_at', $time_filter )->exists();
                        if ( $last_week_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $last_week_sold_voucher )
                    }//foreach($raw_active_gift_vouchers as $voucher)
                    break;

                case 'this_month':
                    $time_filter = $this->reportTimeFilter( 'this_month' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $month_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->where( 'created_at', '>=', $time_filter )->exists();
                        if ( $month_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $month_sold_voucher )
                    }//foreach ( $raw_active_gift_vouchers as $voucher)
                    break;

                case 'last_month':
                    $time_filter = $this->reportTimeFilter( 'last_month' );
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $last_month_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->whereBetween( 'created_at', $time_filter )->exists();
                        if ( $last_month_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $last_month_sold_voucher )
                    }//foreach ( $raw_active_gift_vouchers as $voucher)
                    break;

                case 'custom_period':
                    $time_filter                = $this->reportTimeFilter( 'custom_period' );
                    $inputs                     = Input::all();
                    $custom_time_filter_applied = "From: " . $inputs[ 'date_from' ] . '   To: ' . $inputs[ 'date_to' ] . '  as   ';
                    foreach ( $raw_active_gift_vouchers as $voucher ) {
                        $custom_period_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->whereBetween( 'created_at', $time_filter )->exists();
                        if ( $custom_period_sold_voucher ) {
                            $active_gift_vouchers[] = $voucher;
                        }//if ( $custom_period_sold_voucher )
                    }//foreach($raw_active_gift_vouchers as $voucher)
                    break;

                default :
                    $active_gift_vouchers = $raw_active_gift_vouchers;
                    break;
            endswitch;

            $time_filter_applied = $inputs[ 'time_filter' ];
        }//if(isset($inputs['time_filter']) && !empty($inputs['time_filter']))
        else {
            $active_gift_vouchers = $raw_active_gift_vouchers;
        }


        $data[ 'active_gift_vouchers' ] = $active_gift_vouchers;

        $data[ 'merchant_id' ] = $id;

        $data[ 'time_filters' ] = $time_filters;

        $data[ 'time_filter_applied' ] = $time_filter_applied;

        $data[ 'custom_time_filter_applied' ] = $custom_time_filter_applied;

        return View::make( 'users.reports.merchant.activeVouchers', compact( 'data' ) );
    }

    /**
     * return with active voucher report as Json
     * @param integer $id
     * @return collection
     */
    public function merchantReportActiveVouchersJson( $id ) {
        $data = array();

        $active_gift_vouchers = GiftVouchersParameter::where( 'MerchantID', $id )->whereNull( 'Expires' )->get();

        foreach ( $active_gift_vouchers as $voucher ) {
//            voucher title
            $title = $voucher->Title;

//            get value validated for each voucher
            if ( $voucher->SingleUse == 1 ) {
                $vouchers_sold   = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->get();
                $value_validated = 0;
                foreach ( $vouchers_sold as $voucher_sold ) {
                    $validation_for_single_use = GiftVoucherValidation::where( 'giftvoucher_id', $voucher_sold->id )->exists();
                    $value_validated += ($validation_for_single_use) ? $voucher_sold->voucher_value : 0;
                }//foreach ( $vouchers_sold as $voucher_sold)
            }//if ( $voucher->SingleUse == 1 )
            else {
                $vouchers_sold   = GiftVoucher::where( 'gift_vouchers_parameters_id', $voucher->id )->get();
                $value_validated = 0;
                foreach ( $vouchers_sold as $voucher_sold ) {
                    $validation_for_each_voucher_sold = GiftVoucherValidation::where( 'giftvoucher_id', $voucher_sold->id )->get();
                    foreach ( $validation_for_each_voucher_sold as $validation ) {
                        $value_validated += $validation->value;
                    }//foreach ( $validation_for_each_voucher_sold as $validation)
                }//foreach($vouchers_sold as $voucher_sold)
            }
            $data[] = array( 'title' => $title, 'value_validated' => $value_validated );
        }//foreach ( $active_gift_vouchers as $voucher )

        return $data;
    }

    /**
     * Validations report for merchants
     * @param integer $id
     * @return object
     */
    public function merchantReportValidations( $id ) {
        $data = array();

//        $merchant = Merchant::find($id);

        $raw_gift_vouchers = GiftVouchersParameter::where( 'MerchantID', $id )->get();

        $row_data = array();

        foreach ( $raw_gift_vouchers as $gift_voucher ) {
            $check_sold_voucher = GiftVoucher::where( 'gift_vouchers_parameters_id', $gift_voucher->id )->exists();
            if ( !$check_sold_voucher ) {
                continue;
            }//if ( !$check_sold_voucher )
            $sold_vouchers = GiftVoucher::where( 'gift_vouchers_parameters_id', $gift_voucher->id )->get();
            foreach ( $sold_vouchers as $sold_voucher ) {
                $check_validated_voucher = GiftVoucherValidation::where( 'giftvoucher_id', $sold_voucher->id )->exists();
                if ( !$check_validated_voucher ) {
                    continue;
                }//if ( !$check_validated_voucher )
                $validated_vouchers = GiftVoucherValidation::where( 'giftvoucher_id', $sold_voucher->id )->get();
                foreach ( $validated_vouchers as $validated_voucher ) {
                    $row_data[ 'sold_voucher_id' ] = $sold_voucher->id;
                    $row_data[ 'voucher_number' ]  = g::formatVoucherCode( $sold_voucher->qr_code );
                    $row_data[ 'pruchase_date' ]   = g::formatDateTime( $sold_voucher->created_at );
                    $row_data[ 'expiry_date' ]     = g::formatDateTime( $sold_voucher->expiry_date );
//                    todo check if brent means value purchased or actual value validated
                    $row_data[ 'value' ]           = g::formatCurrency( $validated_voucher->value );
                    $row_data[ 'validation_date' ] = g::formatDateTime( $validated_voucher->created_at );
                }//foreach($validated_vouchers as $validated_voucher)
                $data[] = $row_data;
            }//foreach ( $sold_vouchers as $sold_voucher)
        }//foreach ( $raw_gift_vouchers as $gift_voucher)

        return View::make( 'users.reports.merchant.validations', compact( 'data' ) );
    }

//    Helper methods
    private function reportTimeFilter( $time_filter = '' ) {
        switch ( $time_filter ) {
            case 'today':
                $today_utc = Carbon::today( 'Pacific/Auckland' )->setTimezone( 'UTC' );
                return $today_utc;

            case 'yesterday':
                $today_utc     = Carbon::today( 'Pacific/Auckland' )->setTimezone( 'UTC' );
                $yesterday_utc = Carbon::yesterday( 'Pacific/Auckland' )->setTimezone( 'UTC' );
                return array( $yesterday_utc, $today_utc );

            case 'this_week':
                $start_of_this_week_utc = Carbon::today( 'Pacific/Auckland' )->startOfWeek()->setTimezone( 'UTC' );
                return $start_of_this_week_utc;

            case 'last_week':
                $start_of_this_week_utc = Carbon::today( 'Pacific/Auckland' )->startOfWeek()->setTimezone( 'UTC' );
                $start_of_last_week_utc = Carbon::today( 'Pacific/Auckland' )->startOfWeek()->subDay()->startOfWeek()->setTimezone( 'UTC' );
                return array( $start_of_last_week_utc, $start_of_this_week_utc );

            case 'this_month':
                $start_of_this_month = Carbon::today( 'Pacific/Auckland' )->startOfMonth()->setTimezone( 'UTC' );
                return $start_of_this_month;

            case 'last_month':
                $start_of_this_month = Carbon::today( 'Pacific/Auckland' )->startOfMonth()->setTimezone( 'UTC' );
                $start_of_last_month = Carbon::today( 'Pacific/Auckland' )->startOfMonth()->subDay()->startOfMonth()->setTimezone( 'UTC' );
                return array( $start_of_last_month, $start_of_this_month );

            case 'custom_period':
                $date_from_utc = g::utcDateTime( Input::get( 'date_from' ) . ' 00:00:00', 'd/m/Y H:i:s' );
                $date_to_utc   = g::utcDateTime( Input::get( 'date_to' ) . ' 00:00:00', 'd/m/Y H:i:s' )->addDay();
                return array( $date_from_utc, $date_to_utc );

            default:
                return FALSE;
        }//switch ( $time_filter )
    }
    
    private function reportHourFilter( ) {
        
    }

}
