<?php
use Home\Model\ProfileModel;
?>

<nav class="navbar navbar-default navbar-static-top navbar-fixed-top" role="navigation">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a href="/" class="navbar-brand">
                {$Think.lang.APPLICATION_NAME}
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="nav navbar-nav">
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{:U('/posts')}">{$Think.lang.MENU_POSTS}</a></li>

                <if condition="!isAuthenticated()">
                    <li><a href="{:U('/login')}">{$Think.lang.LOGIN}</a></li>
                    <li><a href="{:U('/signup')}">{$Think.lang.SIGNUP}</a></li>
                <else />
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            {:ProfileModel::getFullName(session('authentication.user'))}
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li><a href="{:U('/profile')}">{$Think.lang.MENU_PROFILE}</a></li>
                            <!-- <li role="separator" class="divider"></li>
                            <li><a href="{:U('/users')}">用户</a></li> -->
                            <li role="separator" class="divider"></li>
                            <li><a href="{:U('/logout')}">{$Think.lang.LOGOUT}</a></li>
                        </ul>
                    </li>
                </if>

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {$Think.lang.SWITCH_LANGUAGE}<span class="caret"></span>
                    </a>

                    <ul class="dropdown-menu" role="menu">
                        <li><a href="?l=zh-cn">{$Think.lang.CHINESE}</a></li>
                        <li><a href="?l=en-us">{$Think.lang.ENGLISH}</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
