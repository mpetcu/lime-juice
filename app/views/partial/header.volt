<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid ">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{ url() }}">
                <img src="{{ url() }}img/logo.png" alt="Lime Juice - Reports and data exports tool" />
                <span>Lime Juice<span><?php echo VERSION ?></span></span>
            </a>
            {% if showMenu %}
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {% endif %}
        </div>
        {% if showMenu %}
        <div class="collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav navbar-right ">
                {% if showLogOut %}
                    <li><p class="navbar-text lmj"> &nbsp;&nbsp;&nbsp; {{ sessionUserName }}</p></li>
                {% endif %}
                <li><a href="{{ url('report/index') }}"><span class="glyphicon glyphicon-play"></span> {% if userRole == 'master' %}Reports{% else %}My reports{%endif%}</a></li>
                {% if userRole == 'master' %}
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="Settings"><span class="glyphicon glyphicon-cog"></span> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('settings/index') }}"><span class="glyphicon glyphicon-user"></span> Users & permissions</a></li>
                            <li><a href="{{ url('settings/update') }}"><span class="glyphicon glyphicon-flag"></span> Updates</a></li>
                        </ul>
                    </li>
                {% endif %}
                {% if showLogOut %}
                    <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" title="My profile"><span class="glyphicon glyphicon-user"></span> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ url('user/edit') }}"><span class="glyphicon glyphicon-user"></span> Edit profile</a></li>
                            <li><a href="{{ url('user/pass') }}"><span class="glyphicon glyphicon-lock"></span> Change password</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="{{ url('user/logout') }}"><span class="glyphicon glyphicon-ban-circle"></span> Logout</a></li>
                        </ul>
                    </li>
                {% endif %}
            </ul>
        </div>
        {% endif %}
    </div>
</nav>

