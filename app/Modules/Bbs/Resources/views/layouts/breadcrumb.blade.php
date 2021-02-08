<!--面包屑导航开始-->
<li><a href="{{ url('/') }}"><i class="fa fa-home"></i> 首页</a></li>
@if(!empty($location_menus))
    @foreach($location_menus as $location_menu)
        <li>
            <a class="cat" href="{{ url($location_menu['menu_url']) }}">
                @if(!empty($location_menu['menu_icon']))<i class="fa {{ $location_menu['menu_icon'] }}"></i>@endif
                {{ $location_menu['menu_name'] }}
            </a>
        </li>
    @endforeach
@endif
<!--面包屑导航结束-->
