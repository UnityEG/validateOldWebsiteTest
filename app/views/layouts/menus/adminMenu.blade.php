<li class="dropdown"><a id="dLabel"  data-target="#" href="#" class="dropdown-toggle" data-toggle="dropdown">Settings<span class="caret"></span></a>
   <ul class="dropdown-menu multi-level">
		<li class="dropdown-submenu"><a href="#" tabindex="-1">Users</a>    
			<ul class="dropdown-menu">
                <li>{{link_to_route('customer.index', 'Customers')}}</li>
                <li>{{link_to_route('franchisor.index', 'Franchisor')}}</li>
                <li>{{link_to_route('merchant.index', 'Merchant')}}</li>
                <li>{{link_to_route('merchant_manager.index', 'Merchant Managers')}}</li>
                <li>{{link_to_route('supplier.index', 'Suppliers')}}</li>
            </ul>
        </li>
		<li role='separator' class="divider"></li>
        <li>{{link_to_route('rules.index', 'Rules')}}</li>
    </ul>    
</li>


<li>
    {{link_to_route('admin.show', 'Welcome '.$user_info->first_name, array($user_info->id))}}
</li>
<li>
    {{link_to_route('logout', 'Logout')}}
</li>