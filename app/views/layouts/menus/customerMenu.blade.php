<li>
    @if(Session::has('facebook_user'))
    {{link_to_route('customer.show', 'Welcome '.$user_info->first_name.'( as '.Session::get('facebook_user')->facebook_username.' Facebook user )', array($user_info->id))}}
    @else
    {{link_to_route('customer.show', 'Welcome '.$user_info->first_name, array($user_info->id))}}
    @endif
</li>
<li>
    {{link_to_route('logout', 'Logout')}}
</li>

