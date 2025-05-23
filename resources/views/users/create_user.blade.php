@extends('layouts.app')

@section('title', 'Tạo Người Dùng')
@section('page-title', 'Tạo Người Dùng Mới')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Người Dùng</a></li>
    <li class="breadcrumb-item active">Tạo mới</li>
@endsection

@section('needs_select2', true)

@section('content')
<div class="row">
    <div class="col-lg-8 col-md-10">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Thông tin người dùng</h3>
            </div>
            
            <form id="create-user-form" action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Họ và tên</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                            id="name" name="name" value="{{ old('name') }}" 
                            placeholder="Nhập họ và tên" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email') }}" 
                                placeholder="Nhập email" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password">Mật khẩu</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                        id="password" name="password" placeholder="Nhập mật khẩu" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary toggle-password" type="button">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation">Xác nhận mật khẩu</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    </div>
                                    <input type="password" class="form-control" 
                                        id="password_confirmation" name="password_confirmation" 
                                        placeholder="Xác nhận mật khẩu" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="roles">Vai trò</label>
                        <select name="roles[]" id="roles" class="form-control select2 @error('roles') is-invalid @enderror" 
                            multiple data-placeholder="Chọn vai trò">
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" 
                                    {{ in_array($role->name, old('roles', [])) ? 'selected' : '' }}
                                    data-permissions='{{ json_encode($role->permissions->pluck('name')->toArray()) }}'>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('roles')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>URL mặc định sau khi đăng nhập</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-link"></i></span>
                            </div>
                            <input type="text" class="form-control" id="default-url" 
                                value="/dashboard" readonly>
                        </div>
                        <small class="text-muted">URL này được tự động xác định dựa trên vai trò đã chọn</small>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Tạo người dùng
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-1"></i> Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-2">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title">Hỗ trợ</h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info-circle mr-2"></i> Lưu ý:</h5>
                    <ul class="pl-3">
                        <li>Mật khẩu phải có ít nhất 8 ký tự</li>
                        <li>Vai trò xác định những quyền mà người dùng có</li>
                        <li>URL mặc định là trang người dùng sẽ được chuyển đến sau khi đăng nhập</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            placeholder: "Chọn vai trò",
            allowClear: true,
            width: '100%'
        });
        
        // Hiển thị/ẩn mật khẩu
        $('.toggle-password').click(function() {
            const passwordField = $(this).closest('.input-group').find('input');
            const fieldType = passwordField.attr('type');
            
            // Toggle password visibility
            if (fieldType === 'password') {
                passwordField.attr('type', 'text');
                $(this).find('i').removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordField.attr('type', 'password');
                $(this).find('i').removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Logic hiển thị URL mặc định
        function updateDefaultUrl() {
            const selectedRoles = $('#roles').val() || [];
            let permissions = [];

            // Lấy danh sách quyền từ các vai trò được chọn
            selectedRoles.forEach(role => {
                const option = $('#roles option[value="' + role + '"]');
                const rolePermissions = JSON.parse(option.attr('data-permissions') || '[]');
                permissions = permissions.concat(rolePermissions);
            });

            // Loại bỏ quyền trùng lặp
            permissions = [...new Set(permissions)];

            // Ánh xạ quyền đến URL
            let redirectUrl = '/dashboard';
            for (const permission of permissions) {
                if (permissionToRoute[permission]) {
                    redirectUrl = permissionToRoute[permission];
                    break;
                }
            }

            // Cập nhật URL mặc định hiển thị
            $('#default-url').val(redirectUrl);
        }

        // Khởi tạo URL mặc định ban đầu
        updateDefaultUrl();

        // Cập nhật URL khi thay đổi vai trò
        $('#roles').on('change', updateDefaultUrl);
        
        // Form validation
        $('#create-user-form').on('submit', function(e) {
            if (!validateForm('create-user-form')) {
                e.preventDefault();
                Toast.fire({
                    icon: 'error',
                    title: 'Vui lòng điền đầy đủ thông tin bắt buộc'
                });
                return false;
            }
            
            // Kiểm tra mật khẩu trùng khớp
            const password = $('#password').val();
            const confirmPassword = $('#password_confirmation').val();
            
            if (password !== confirmPassword) {
                e.preventDefault();
                $('#password_confirmation').addClass('is-invalid');
                Toast.fire({
                    icon: 'error',
                    title: 'Mật khẩu xác nhận không khớp'
                });
                return false;
            }
        });
    });
</script>
@endsection