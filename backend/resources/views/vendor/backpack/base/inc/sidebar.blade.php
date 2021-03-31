@if (Auth::check())
    <!-- Left side column. contains the sidebar -->

    <!-- Left side column. contains the sidebar -->
    <aside class="main-sidebar">
      <!-- sidebar: style can be found in sidebar.less -->
      <section class="sidebar">
        <!-- Sidebar user panel -->
        @include('backpack::inc.sidebar_user_panel')

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
          {{-- <li class="header">{{ trans('backpack::base.administration') }}</li> --}}
          <!-- ================================================ -->
          <!-- ==== Recommended place for admin menu items ==== -->
          <!-- ================================================ -->
          <li><a href="{{ backpack_url('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ trans('backpack::base.dashboard') }}</span></a></li>

          <li><a href="{{backpack_url('consultent') }}"><i class="fa fa-user"></i> <span>Consultants</span></a></li>

          <li><a href="{{backpack_url('client') }}"><i class="fa fa-user"></i> <span>Clients <span class="badge badge-light"  style="margin-top: -2px; margin-left: 10px; background-color:red;">
          @php
            echo(App\Models\Client::where('status', "=", "current")->count());
          @endphp
          </span>
          </span></a></li>

          <li><a href="{{backpack_url('user') }}"><i class="fa fa-user"></i> <span>Utilisateurs</span></a></li>   

          <li><a href="{{backpack_url('request_client') }}"><i class="fa fa-user"></i> <span>Demandes des clients</span></a></li>

          <li><a href="{{backpack_url('request_contact') }}"><i class="fa fa-user"></i> <span>Demandes des RDV  <span class="badge badge-light"  style="margin-top: -2px; margin-left: 10px; background-color:red;">
          @php
            echo(App\Models\RequestContact::where('status', "=", "sent")->count());
          @endphp
          </span></span></a></li>

          <li><a href="{{backpack_url('mission') }}"><i class="fa fa-user"></i> <span>Missions</span></a></li>

          <li><a href="{{backpack_url('news') }}"><i class="fa fa-user"></i> <span>News</span></a></li>
		  
		  <li><a href="{{backpack_url('assistance') }}"><i class="fa fa-commenting-o"></i> <span>Assistance</span></a></li>

          <li class="header"></li>       

          <li><a href="{{backpack_url('technology') }}"><i class="fa fa-user"></i> <span>Technologies</span></a></li>

          <li><a href="{{backpack_url('skill') }}"><i class="fa fa-user"></i> <span>Compétences</span></a></li>

          <li><a href="{{backpack_url('diploma') }}"><i class="fa fa-user"></i> <span>Diplômes</span></a></li>          

          <li><a href="{{backpack_url('secteur') }}"><i class="fa fa-user"></i> <span>Secteurs</span></a></li>

          <li><a href="{{backpack_url('city') }}"><i class="fa fa-globe"></i> <span>Cities</span></a></li>
          
          <li><a href="{{backpack_url('page') }}"><i class="fa fa-file-o"></i> <span>Pages</span></a></li>


          <li><a href="{{  backpack_url('elfinder') }}"><i class="fa fa-files-o"></i> <span>File manager</span></a></li>

          <!-- <li><a href="{{  backpack_url('language') }}"><i class="fa fa-flag-o"></i> <span>Languages</span></a></li> -->
          <!-- <li><a href="{{ backpack_url( 'language/texts') }}"><i class="fa fa-language"></i> <span>Language Files</span></a></li> -->

          <li><a href="{{ backpack_url('log') }}"><i class="fa fa-terminal"></i> <span>Logs</span></a></li>

          <li><a href="{{ backpack_url('setting') }}"><i class="fa fa-cog"></i> <span>Settings</span></a></li>


          <!-- ======================================= -->
          {{-- <li class="header">Other menus</li> --}}
        </ul>
      </section>
      <!-- /.sidebar -->
    </aside>
@endif
