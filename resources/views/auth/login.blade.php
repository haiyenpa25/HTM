<!DOCTYPE html>
  <html lang="en">
  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>Đăng nhập</title>
      <!-- CSS của AdminLTE -->
      <link rel="stylesheet" href="{{ asset('assets/adminlte/css/adminlte.min.css') }}">
  </head>
  <body class="hold-transition login-page">
  <div class="login-box">
      <div class="login-logo">
          <a href="#"><b>Quản Lý Hội Thánh</b></a>
      </div>
      <div class="card">
          <div class="card-body login-card-body">
              <p class="login-box-msg">Đăng nhập để bắt đầu</p>

              <form action="{{ route('login') }}" method="post">
                  @csrf
                  <div class="input-group mb-3">
                      <input type="email" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}" required>
                      <div class="input-group-append">
                          <div class="input-group-text">
                              <span class="fas fa-envelope"></span>
                          </div>
                      </div>
                  </div>
                  @error('email')
                      <div class="text-danger mb-3">{{ $message }}</div>
                  @enderror

                  <div class="input-group mb-3">
                      <input type="password" name="password" class="form-control" placeholder="Mật khẩu" required>
                      <div class="input-group-append">
                          <div class="input-group-text">
                              <span class="fas fa-lock"></span>
                          </div>
                      </div>
                  </div>
                  @error('password')
                      <div class="text-danger mb-3">{{ $message }}</div>
                  @enderror

                  <div class="row">
                      <div class="col-12">
                          <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                      </div>
                  </div>
              </form>
          </div>
      </div>
  </div>
  </body>
  </html>