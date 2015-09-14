<li>
    {{link_to_route('merchant_manager.show', 'Welcome '.$user_info->first_name, array($user_info->id))}}
</li>

<li>
    {{link_to_route('logout', 'Logout')}}
</li>