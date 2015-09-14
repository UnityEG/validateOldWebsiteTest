<li>
    {{link_to_route('reports.merchant', 'Reports', array(Auth::user()->merchant->id))}}
</li>
<li>
    {{link_to_route('merchant.show', 'Welcome '.$user_info->first_name, array($user_info->id))}}
</li>
<li>
    {{link_to_route('logout', 'Logout')}}
</li>