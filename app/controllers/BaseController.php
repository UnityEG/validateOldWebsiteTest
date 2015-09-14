<?php

class BaseController extends Controller {

    /**
     * Instance of Authenticated User
     * @var object
     */
    protected $auth_user;

    /**
     * check if the user is one of owner group "developer, owner"
     * @var boolean
     */
    protected $check_owner;

    /**
     * check if the user is one of admin group "developer, owner, admin"
     * @var boolean
     */
    protected $check_admin;
    
    /**
     * Admin default rules
     * @var array
     */
    protected $admin_default_rules = array(
        'admin_show_one',
        'user_activate',
        'customer_show_all',
        'customer_create',
        'customer_update',
        'customer_delete',
        'customer_activate',
        'customer_birthday_modify',
        'franchisor_show_all',
        'franchisor_create',
        'franchisor_update',
        'franchisor_delete',
        'franchisor_activate',
        'franchisor_assign',
        'merchant_show_all',
        'merchant_create',
        'merchant_update',
        'merchant_delete',
        'merchant_activate',
        'merchant_manager_show_all',
        'merchant_manager_create',
        'merchant_manager_update',
        'merchant_manager_delete',
        'merchant_manager_activate',
        'supplier_show_all',
        'supplier_create',
        'supplier_update',
        'supplier_delete',
        'supplier_activate',
        'supplier_assign',
        'Gift Voucher - Edit',
        'Gift Voucher - List',
        'Gift Voucher - Show',
        'Gift Voucher - Check',
        'Gift Voucher - Validate',
        'Gift Voucher Parameter - Create',
        'Gift Voucher Parameter - Edit',
        'Gift Voucher Parameter - List',
        'Gift Voucher Parameter - Show'
    );
    
    /**
     * Customer default rules
     * @var array
     */
    protected $customer_default_rules = array(
        'customer_show_one'
    );
    
    /**
     * Franchisor default rules
     * @var array
     */
    protected $franchisor_default_rules = array(
        'franchisor_show_one',
        'merchant_create',
        'merchant_manager_create',
        'merchant_manager_update',
        'merchant_manager_delete',
        'merchant_manager_activate',
        'Gift Voucher - Edit',
        'Gift Voucher - Show',
        'Gift Voucher - Check',
        'Gift Voucher - Validate',
        'Gift Voucher Parameter - Create',
        'Gift Voucher Parameter - Edit',
        'Gift Voucher Parameter - List',
        'Gift Voucher Parameter - Show'
        
    );
    
    /**
     * Merchant default rules
     * @var array
     */
    protected $merchant_default_rules = array(
        'merchant_show_one',
        'merchant_update',
        'merchant_manager_create',
        'merchant_manager_update',
        'merchant_manager_delete',
        'merchant_manager_activate',
        'Gift Voucher - Edit',
        'Gift Voucher - Show',
        'Gift Voucher - Check',
        'Gift Voucher - Validate',
        'Gift Voucher Parameter - Create',
        'Gift Voucher Parameter - Edit',
        'Gift Voucher Parameter - List',
        'Gift Voucher Parameter - Show',
    );
    
    /**
     * Merchant Manager default rules
     * @var array
     */
    protected $merchant_manager_default_rules = array(
        'merchant_manager_show_one',
        'merchant_manager_update',
        'Gift Voucher - Check',
        'Gift Voucher - Validate',
    );
    
    /**
     * Supplier default rules
     * @var array
     */
    protected $supplier_default_rules = array(
        'supplier_show_one',
    );

    public function __construct() {
        $this->check_admin = FALSE;
        $this->check_owner = FALSE;
        $this->auth_user = Auth::user();

        if ( !is_null($this->auth_user) && is_object( $this->auth_user ) ) {
            if ( $this->auth_user->isOwner() || $this->auth_user->isDeveloper() ) {
                $this->check_owner = TRUE;
            }//if ( $this->auth_user->isOwner() || $this->auth_user->isDeveloper() )
            
            if ( $this->auth_user->isOwner() || $this->auth_user->isAdmin() || $this->auth_user->isDeveloper()) {
                $this->check_admin = true;
            }//( $this->auth_user->isOwner() || $this->auth_user->isAdmin() || $this->auth_user->isDeveloper())
        }//if ( is_object( $auth_user) )
    }

    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout() {
        if ( !is_null( $this->layout ) ) {
            $this->layout = View::make( $this->layout );
        }
    }

}

