<!DOCTYPE html>
<html lang="pt">
  @include('site.head')
<body>
    @include('site.nav')

    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 280px;">
                    <ul class="nav nav-pills flex-column mb-auto">
                      <li class="nav-item">
                        <a href="{{ route('admin')}}" class="nav-link active" aria-current="page">
                          <i class="fa-solid fa-house-chimney"></i>
                          Home
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('create.project')}}" class="nav-link link-dark">
                            <i class="fa-solid fa-plus"></i>
                          Add Project
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('table')}}" class="nav-link link-dark">
                          <i class="fa-solid fa-scroll"></i>
                          Projects
                        </a>
                      </li>
                      @if (Session::get('isadmin') == 1)
                      <li>
                        <a href="{{ route('create.user')}}" class="nav-link link-dark">
                          <i class="fa-solid fa-users"></i>
                          Add User
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('users')}}" class="nav-link link-dark">
                          <i class="fa-solid fa-address-book"></i>
                          Users
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('create.admin')}}" class="nav-link link-dark">
                          <i class="fa-solid fa-user"></i>
                          Add Admin
                        </a>
                      </li>
                      <li>
                        <a href="{{ route('admins')}}" class="nav-link link-dark">
                          <i class="fa-solid fa-rectangle-list"></i>
                          Admins
                        </a>
                      </li>
                    @endif

                    </ul>
                </div>
            </div>
            <div class="col-8">
                @yield('content')
            </div>
        </div>
    </div>



    @include('site.footer')
</body>
</html>
