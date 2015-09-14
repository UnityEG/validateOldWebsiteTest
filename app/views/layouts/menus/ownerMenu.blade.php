<li class="dropdown"><a id="dLabel"  data-target="#" href="#" class="dropdown-toggle" data-toggle="dropdown">Settings<span class="caret"></span></a>
    <ul class="dropdown-menu multi-level">
        <li class="dropdown-submenu"><a href="#" tabindex="-1">Users</a>
            <ul class="dropdown-menu">
                <li>{{ link_to('customer'	 , 'Customers') }}</li>
                <li role="separator" class="divider"></li>
                <li>{{ link_to('franchisor'	 , 'Franchisors') }}</li>
                <li>{{ link_to('merchant'	 , 'Merchants') }}</li>
                <li>{{ link_to('merchant_manager', 'Merchant Managers') }}</li>
                <li>{{ link_to('supplier'	 , 'Suppliers') }}</li>
                <li role="separator" class="divider"></li>
                <li>{{ link_to('admin', 'Admins') }}</li>
                <li role="separator" class="divider"></li>
                <li>{{ link_to('owner', 'Owner') }}</li>
            </ul> 
        </li>
        <li role="separator" class="divider"></li>
        <li>{{link_to_route('rules.index', 'Rules')}}</li>
        <li role="separator" class="divider"></li>
        <li>{{link_to_route('userpics.index', 'Images')}}</li>
        <li role="separator" class="divider"></li>
        <li class="dropdown-submenu"><a tabindex="-1" href="#">Admin</a>
            <ul class="dropdown-menu">
                <li>{{ link_to('Industrys', 'Industries') }}</li>
                <li>{{ link_to('UseTerms', 'Terms of Use') }}</li> 
            </ul>
        </li>	
    </ul>
</li>

<li><a href="{{route($type.'.show', $user_info->id)}}">Welcome,&nbsp;{{ucfirst($user_info->first_name).' '.$user_info->last_name}}</a></li>
<li>{{link_to('logout', 'Logout')}}</li>

