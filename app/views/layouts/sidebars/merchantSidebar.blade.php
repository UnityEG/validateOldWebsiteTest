<style>
    .custom-nav{
        border-radius: 10px;
        width: 110px;
        position: fixed;
    }

    .custom-nav li a{
        text-align: center;
        font-weight: bold;
        color: white;
    }

    .custom-nav .active, .custom-nav .active a{
        background: #222222;
        color: white;
    }

    nav .custom-nav .open>a, .nav .open>a:focus, .nav .open>a:hover{
        background: white;
        color: #222222;
        transition-delay: 100ms;
    }
    
    .custom-nav .nav>li>a:focus, .nav>li>a:hover{
        background: white;
        color: #222222;
        transition-delay: 100ms;
    }

    .custom-nav .dropdown-menu{
        background-color: #0C0C0C;
    }

    .nav-end{
        width: 50%;
        float: left;
    }
</style>
<nav>
    <ul class="nav navbar-inverse custom-nav">
        <li class="active">{{link_to_route('myHome', 'Dashboard')}}</li>
        <li class="active"><a href="#">Payments</a></li>
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" tabindex="-1">Reports<span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li>{{link_to_route('reports.merchant.active_vouchers', 'Active Vouchers', array($user->merchant->id))}}</li>
                <li>{{link_to_route('reports.merchant.validations', 'Validations', array($user->merchant->id))}}</li>
            </ul>
        </li>
        <li class="active">{{link_to_route('merchant.show', 'Profile', array($user->$type->id))}}</li>
        <li>{{link_to_route('merchant.edit', 'Settings', array($user->merchant->id))}}</li>
        <li>{{link_to_route('logout', 'Logout')}}</li>
    </ul>
</nav>