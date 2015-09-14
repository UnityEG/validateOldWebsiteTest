<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

    use UserTrait,
        RemindableTrait,
        SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table    = 'users';
    protected $dates    = ['deleted_at' ];
    protected $fillable = ['email', 'password', 'user_type', 'ip', 'access_level_id', 'active' ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden    = array( 'password', 'remember_token' );
    
    /**
     * Creating Rules
     * @var array
     */
    public $create_rules = array(
        'email'                 => 'required|email|unique:users',
        'password'              => 'required|confirmed',
        'password_confirmation' => 'required',
        'user_type'             => 'required|in:developer,owner,admin,merchant,merchant_manager,supplier,franchisor,customer',
        'ip'                    => 'ip',
        'access_level_id'       => 'integer',
        'active'                => 'boolean'
    );
    
    /**
     * Updating Rules
     * @var array
     */
    public $update_rules = array(
        'email'                 => 'required|email',
        'password'              => 'confirmed',
        'password_confirmation' => '',
        'user_type'             => 'required|in:developer,owner,admin,merchant,merchant_manager,supplier,franchisor,customer',
        'ip'                    => 'ip',
        'access_level_id'       => 'integer',
        'active'                => 'boolean'
    );
    
    /**
     * Relationship method between User Model and Owner Model (one to one)
     * @return object
     */
    public function owner() {
        return $this->hasOne( 'Owner' );
    }
    
    /**
     * Relationship method between User Model and Admin Model (one to one)
     * @return object
     */
    public function admin() {
        return $this->hasOne( 'Admin' );
    }
    
    /**
     * Relationship method between User Model and Developer Model (one to one)
     * @return object
     */
    public function developer() {
        return $this->hasOne( 'Developer' );
    }
    
    /**
     * Relationship method between User Model and Customer Model (one to one)
     * @return object
     */
    public function customer() {
        return $this->hasOne( 'Customer' );
    }
    
    /**
     * Relationship method between User Model and Merchant Model (one to one)
     * @return object
     */
    public function merchant() {
        return $this->hasOne( 'Merchant' );
    }
    
    /**
     * Relationship method between User Model and MerchantManager model (one to one)
     * @return object
     */
    public function merchantManager() {
        return $this->hasOne( 'MerchantManager' );
    }
    
    /**
     * Relation method between User Model and Supplier Model (one to one)
     * @return object
     */
    public function supplier() {
        return $this->hasOne( 'Supplier' );
    }
    
    /**
     * Relationship method between User Model and Franchisor Model (one to one)
     * @return object
     */
    public function franchisor() {
        return $this->hasOne( 'Franchisor' );
    }
    
    /**
     * Relationship method between User Model and Rule Model (many to many)
     * @return object
     */
    public function rules() {
        return $this->belongsToMany( 'Rule', 'users_rules_link', 'user_id', 'rule_id' )->withPivot( 'active_rule' );
    }
    
    /**
     * Relationship method between User Model and UsersRulesLink Model (one to Many)
     * @return object
     */
    public function usersRulesLink() {
        return $this->hasMany( 'UsersRulesLink', 'user_id' );
    }
    
    /**
     * Relationship method between User model and UserPic model (one to many)
     * @return object
     */
    public function userPic( ) {
        return $this->hasMany('UserPic');
    }
    
//    Helper Methods
    
    /**
     * check if user is active or not
     * @return boolean
     */
    public function isActiveUser( ) {
        $active_user = (bool) $this->active;
        return ($active_user) ? TRUE : FALSE;
    }

    /**
     * Check that rule exist and associate to the logged in user and is active
     * @param string $rule_name
     * @return boolean
     */
    public function hasRule( $rule_name ) {
//        check if user is developer
        if ( $this->user_type == 'developer' ) {
            return true;
        }//if ( $this->user_type == 'developer' )
//        check if user is active user
        $active_user = ( bool ) $this->active;
        
        if ( !$active_user ) {
            return false;
        }//if ( $this->acitve == 0 )
//        get the rule object according to the user and $rule_name
        $rule = $this->rules()->where( 'rule_name', '=', $rule_name )->first();

        if ( is_null( $rule ) || !is_object( $rule ) || empty( $rule ) ) {
            return false;
        }//if (  is_null( $rule ) || !is_object( $rule ) || empty( $rule ) )
//        check if rule is active or not
        $check_active_rule = $rule->pivot->active_rule;

        if ( $check_active_rule == 0 || is_null( $check_active_rule ) ) {
            return FALSE;
        }

        return TRUE;
    }
    
    /**
     * check if Authenticated user has a Cusotmer account.
     * @return boolean|string
     */
    public function hasCustomerAccount( ) {
        if ( 'developer' == $this->user_type ) {
            return TRUE;
        }//if ( 'developer' == $this->user_type )
        
//        check if user is active
        $active_user = (bool)  $this->active;
        if ( !$active_user ) {
            return FALSE;
        }//if ( !$active_user )
        
        $customer_exist = $this->customer;
        
        if ( !is_null( $customer_exist ) && is_object( $customer_exist ) ) {
            
//            check if customer is active
            $active_customer = (bool)$customer_exist->active_customer;
            if ( !$active_customer ) {
                return 'inactive customer';
            }//if ( !$active_customer )
            
            return TRUE;
        }//if ( !is_null( $customer_exist ) && is_object( $customer_exist ) )
        return FALSE;
    }
    
    /**
     * check if user is developer
     * @return boolean
     */
    public function isDeveloper(  ) {
        $developer_exist = $this->developer;
        if ( !is_null( $developer_exist ) && is_object( $developer_exist ) ) {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * check if user is owner or developer
     * @return boolean
     */
    public function isOwner( ) {
        $owner_exist = $this->owner;
        if ( !is_null( $owner_exist ) && is_object( $owner_exist ) ) {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check if user is admin, owner or developer
     * @return boolean
     */
    public function isAdmin( ) {
        $admin_exist = $this->admin;
        if ( !is_null( $admin_exist ) && is_object( $admin_exist ) ) {
            return TRUE;
        }
        return FALSE;
    }
    
    /**
     * check if admin is active or not
     * @return boolean
     */
    public function isActiveAdmin( ) {
        if($this->isAdmin()){
            $active_admin = (bool)$this->admin->active_admin;
            return ($active_admin) ? TRUE : FALSE;
        }//if($this->isAdmin())
        return FALSE;
    }
    
    /**
     * check if user is merchant and is active merchant
     * @return string|boolean
     */
    public function isMerchant( ) {
        $merchant_exist = $this->merchant;
        if ( !is_null( $merchant_exist ) && is_object( $merchant_exist ) ) {
            return TRUE;
        }//if ( !is_null( $merchant_exist ) && is_object( $merchant_exist ) )
        return FALSE;
    }
    
    /**
     * check if merchant is active or not
     * @return boolean
     */
    public function isActiveMerchant( ) {
        if ( $this->isMerchant() ) {
            $active_merchant = (bool)  $this->merchant->active_merchant;
            return ($active_merchant) ? TRUE : FALSE;
        }//if ( $this->isMerchant() )
        return FALSE;
    }
    
    /**
     * check if user has customer profile or not
     * @return boolean
     */
    public function isCustomer( ) {
        $customer_exist = $this->customer;
        
        if ( !is_null($customer_exist) && is_object( $customer_exist ) ) {
            return TRUE;
        }//if ( !is_null($customer_exist) && is_object( $customer_exist ) )
        
        return FALSE;
    }
    
    /**
     * check if customer is active or not
     * @return boolean
     */
    public function isActiveCustomer( ) {
        if ( $this->isCustomer() ) {
            $active_customer = (bool) $this->customer->active_customer;
            return ($active_customer) ? TRUE : FALSE;
        }//if ( $this->isCustomer() )
        return FALSE;
    }
    
    /**
     * check if user has supplier profile or not
     * @return boolean
     */
    public function isSupplier( ) {
        $supplier_exist = $this->supplier;
        if ( !is_null( $supplier_exist ) && is_object( $supplier_exist ) ) {
            return TRUE;
        }//if ( !is_null( $supplier_exist ) && is_object( $supplier_exist ) )
        return FALSE;
    }
    
    /**
     * check if supplier is active or not
     * @return boolean
     */
    public function isActiveSupplier( ) {
        if ( $this->isSupplier() ) {
            $active_supplier = (bool)  $this->supplier->active_supplier;
            return ($active_supplier) ? TRUE : FALSE;
        }//if ( $this->isSupplier() )
        return FALSE;
    }
    
    /**
     * check if user has franchisor account or not
     * @return boolean
     */
    public function isFranchisor( ) {
        $franchisor_exist = $this->franchisor;
        if ( !is_null( $franchisor_exist ) && is_object( $franchisor_exist ) ) {
            return TRUE;
        }//if ( !is_null( $franchisor_exist ) && is_object( $franchisor_exist ) )
        return FALSE;
    }
    
    /**
     * check if franchisor is active or not
     * @return boolean
     */
    public function isActiveFranchisor( ) {
        if ( $this->isFranchisor() ) {
            $active_franchisor = (bool)  $this->franchisor->active_franchisor;
            return ($active_franchisor) ? TRUE : FALSE;
        }//if ( $this->isFranchisor() )
        return FALSE;
    }
    
    /**
     * check if user has merchant manager account or not
     * @return boolean
     */
    public function isMerchantManager( ) {
        $merchant_manager_exist = $this->merchantManager;
        if ( !is_null( $merchant_manager_exist ) && is_object( $merchant_manager_exist ) ) {
            return TRUE;
        }//if ( !is_null( $merchant_manager_exist ) && is_object( $merchant_manager_exist ) ) 
        return FALSE;
    }
    
    /**
     * check if merchant manager is active or not
     * @return boolean
     */
    public function isActiveMerchantManager( ) {
        if ( $this->isMerchantManager() ) {
            $active_merchant_manager = (bool) $this->merchant_manager->active_merchant_manager;
            return ($active_merchant_manager) ? TRUE : FALSE;
        }//if ( $this->isMerchantManager() )
        return FALSE;
    }
    
    /**
     * current user types collection
     * @return array
     */
    public function getTypes() {
        $userTypes = [];
        if ( $this->isDeveloper() ) {
            $userTypes[] = 'Developer';
        }
        if ( $this->isOwner() ) {
            $userTypes[] = 'Owner';
        }
        if ($this->isAdmin()) {
            $userTypes [] = 'Admin';
        }
        if ($this->isMerchant()) {
            $userTypes [] = 'Merchant';
        }
        if ( $this->isCustomer() ) {
            $userTypes[] = 'Customer';
        }
        if ( $this->isSupplier() ) {
            $userTypes[] = 'Supplier';
        }
        if ( $this->isFranchisor() ) {
            $userTypes[] = 'Franchisor';
        }
        if ( $this->isMerchantManager() ) {
            $userTypes[] = 'MerchantManager';
        }
        
        return $userTypes;
    }
}
