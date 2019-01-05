 <!-- Left Panel -->

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">

            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="<?=base_url()?>">Calvary Albums<!-- <img src="images/logo.png" alt="Logo"> --></a>
            </div>

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="<?=set_class('dashboard')?>">
                        <a href="<?=site_url('dashboard')?>"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <!-- <h3 class="menu-title">UI elements</h3> --><!-- /.menu-title -->
                    <li class="<?=set_class('audio')?>">
                        <a href="<?=site_url('audio')?>"> <i class="menu-icon fa  fa-play-circle-o"></i>Audio</a>
                        <!-- <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-puzzle-piece"></i><a href="ui-buttons.html">Buttons</a></li>
                        </ul> -->
                    </li>
                    <li class="<?=set_class('video')?>">
                        <a href="<?=site_url('video')?>"> <i class="menu-icon fa fa-youtube-play"></i>Videos</a>
                        <!-- <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-table"></i><a href="tables-basic.html">Basic Table</a></li>
                            <li><i class="fa fa-table"></i><a href="tables-data.html">Data Table</a></li>
                        </ul> -->
                    </li>
                    <li class="<?=set_class('image')?>">
                        <a href="<?=site_url('image')?>"> <i class="menu-icon fa fa-instagram"></i>Images</a>
                        <!-- <ul class="sub-menu children dropdown-menu">
                            <li><i class="menu-icon fa fa-th"></i><a href="forms-basic.html">Basic Form</a></li>
                            <li><i class="menu-icon fa fa-th"></i><a href="forms-advanced.html">Advanced Form</a></li>
                        </ul> -->
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside><!-- /#left-panel -->

    <!-- Left Panel -->