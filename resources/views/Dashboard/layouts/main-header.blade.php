<!-- main-header opened -->
<div class="main-header sticky side-header nav nav-item bg-gray-200">
    <div class="container-fluid">
        <div class="main-header-left ">
            <div class="responsive-logo">
                <a href="{{ route('Dashboard.user') }}">تسليم </a>

            </div>
            <div class="app-sidebar__toggle" data-toggle="sidebar">
                <a class="open-toggle" href="#"><i class="header-icon fe fe-align-left" ></i></a>
                <a class="close-toggle" href="#"><i class="header-icons fe fe-x"></i></a>
            </div>
            <div class="main-header-center mr-3 d-sm-none d-md-none d-lg-block">
                <input class="form-control" placeholder="Search for anything..." type="search"> <button class="btn"><i class="fas fa-search d-none d-md-block"></i></button>
            </div>
        </div>
        <div class="main-header-right">
            <ul class="nav">
                <li class="">

                 
                </li>
            </ul>
            <div class="nav nav-item  navbar-nav-right ml-auto">
                <div class="nav-link" id="bs-example-navbar-collapse-1">
                    <form class="navbar-form" role="search">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search">
                            <span class="input-group-btn">
											<button type="reset" class="btn btn-default">
												<i class="fas fa-times"></i>
											</button>
											<button type="submit" class="btn btn-default nav-link resp-btn">
												<svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs"
                                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                     stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                     class="feather feather-search"><circle cx="11" cy="11"
                                                                                            r="8"></circle><line x1="21"
                                                                                                                 y1="21"
                                                                                                                 x2="16.65"
                                                                                                                 y2="16.65"></line></svg>
											</button>
										</span>
                        </div>
                    </form>
                </div>
                <div class="dropdown nav-item main-header-message ">
                
                  
                </div>
          
                <div class="nav-item full-screen fullscreen-button">
                    <a class="new nav-link full-screen-link" href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" class="header-icon-svgs" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="feather feather-maximize">
                            <path
                                d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                        </svg>
                    </a>
                </div>
                <div class="dropdown main-profile-menu nav nav-item nav-link">
                    <a class="profile-user d-flex" href=""> <a class="avatar avatar-md bg-success rounded-circle">
                              
                        {{ Str::substr(auth()->user()->name, 0, 2) }}
                    
                    </a>
                    <div class="dropdown-menu">
                        <div class="main-header-profile bg-primary p-3">
                            <div class="d-flex wd-100p">
                                <div class="avatar avatar-md bg-success rounded-circle">
                              
                                {{ Str::substr(auth()->user()->name, 0, 2) }}
                            
                                </div>
                                <div class="mr-3 my-auto">
                                    <h6>{{auth()->user()->name}}</h6><span>{{auth()->user()->email}}</span>
                                </div>
                            </div>
                        </div>

                        @if(Auth::guard('admin')->check())
                        @php $permission = Auth::guard('admin')->user()->permission; @endphp
                        
                        @if($permission == 1)
                        <a class="dropdown-item" href="{{route('admin.admins.edit',auth()->user()->id)}}"><i class="bx bx-cog"></i>تعديل الملف الشخصي</a>
                        @elseif($permission == 2)

                        @elseif($permission == 3)
                    
                        @endif
                    
                    @endif
                   

                     
                    <form method="get" action="{{ route('logout.admin') }}">
                        <a class="dropdown-item" href="#"  onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="bx bx-log-out"></i> تسجيل الخروج
                        </a>
                    </form>

                    </div>
                </div>
             
            </div>
        </div>
    </div>
</div>






<!-- /main-header -->
