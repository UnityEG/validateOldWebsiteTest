<li class="dropdown"><a id="dLabel"  data-target="#" href="#" class="dropdown-toggle" data-toggle="dropdown">Settings<span class="caret"></span></a>
    <ul class="dropdown-menu multi-level">
        <li class="dropdown-submenu"><a href="#" tabindex="-1">Users</a>
            <ul class="dropdown-menu">
                <li>{{link_to_route('owner.index', 'Owners')}}</li>
                <li>{{link_to_route('admin.index', 'Admins')}}</li>
                <li>{{link_to_route('customer.index', 'Customers')}}</li>
                <li>{{link_to_route('merchant.index', 'Merchants')}}</li>
                <li>{{link_to_route('merchant_manager.index', 'Merchant Managers')}}</li>
                <li>{{link_to_route('franchisor.index', 'Franchisors')}}</li>
                <li>{{link_to_route('supplier.index', 'Suppliers')}}</li>
            </ul>
        </li>
        <li role='separator' class="divider"></li>
        <li>{{link_to_route('rules.index', 'Rules')}}</li>
        <li role="separator" class="divider"></li>
        <li>{{link_to_route('userpics.index', 'Images')}}</li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-submenu"><a href="#" tabindex="-1">Admins</a>
            <ul class="dropdown-menu">
                <li>{{ link_to('Industrys', 'Industries') }}</li>
                <li>{{ link_to('UseTerms', 'Terms of Use') }}</li> 
            </ul>
        </li>
    </ul>
    
</li>

<li>
    <a>Welcome Developer</a>
</li>

<li>
    {{link_to_route('logout', 'Logout')}}
</li>
