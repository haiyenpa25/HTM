@extends('layouts.app')

@section('title', 'Phân Quyền Theo Vai Trò')
@section('page-title', 'Quản Lý Phân Quyền Theo Vai Trò')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Vai Trò</a></li>
    <li class="breadcrumb-item active">Phân Quyền</li>
@endsection

@section('needs_select2', true)

@section('content')
<div class="row">
    <div class="col-12">
        <form id="role-permissions-form" action="{{ route('roles.permissions.update') }}" method="POST">
            @csrf
            <div class="card card-primary card-outline card-outline-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="role-permission-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tab-permissions-tab" data-toggle="pill" 
                                href="#tab-permissions" role="tab" aria-controls="tab-permissions" 
                                aria-selected="true">
                                <i class="fas fa-key mr-1"></i> Phân Quyền
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tab-redirect-tab" data-toggle="pill" 
                                href="#tab-redirect" role="tab" aria-controls="tab-redirect" 
                                aria-selected="false">
                                <i class="fas fa-link mr-1"></i> URL Mặc Định
                            </a>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body">
                    <div class="tab-content" id="role-permission-content">
                        <!-- Permissions Tab -->
                        <div class="tab-pane fade show active" id="tab-permissions" role="tabpanel" 
                            aria-labelledby="tab-permissions-tab">
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="permission-search" 
                                            placeholder="Tìm kiếm quyền...">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 text-md-right mt-2 mt-md-0">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="select-all-btn">
                                        <i class="fas fa-check-square mr-1"></i> Chọn tất cả
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" id="deselect-all-btn">
                                        <i class="fas fa-square mr-1"></i> Bỏ chọn tất cả
                                    </button>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="permissions-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 250px">Vai Trò</th>
                                            <th>Quyền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr class="role-row" data-role-id="{{ $role->id }}">
                                                <td class="font-weight-bold">
                                                    <div class="d-flex align-items-center">
                                                        <span class="badge bg-primary mr-2">{{ $role->users->count() }}</span>
                                                        {{ $role->name }}
                                                    </div>
                                                    <div class="text-muted small mt-1">
                                                        <i class="fas fa-info-circle mr-1"></i> 
                                                        {{ $role->permissions->count() }} quyền
                                                    </div>
                                                </td>
                                                <td>
                                                    <select name="permissions[{{ $role->id }}][]" 
                                                        class="form-control select2 permission-select" 
                                                        data-role-id="{{ $role->id }}" multiple>
                                                        @foreach ($permissions as $permission)
                                                            <option value="{{ $permission->name }}"
                                                                {{ $role->hasPermissionTo($permission->name) ? 'selected' : '' }}
                                                                data-group="{{ explode('_', $permission->name)[0] ?? 'other' }}">
                                                                {{ $permission->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- URL Redirect Tab -->
                        <div class="tab-pane fade" id="tab-redirect" role="tabpanel" 
                            aria-labelledby="tab-redirect-tab">
                            
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-1"></i> 
                                URL mặc định sẽ được sử dụng sau khi người dùng đăng nhập thành công. Hệ thống sẽ chọn URL dựa trên quyền đầu tiên phù hợp.
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Vai Trò</th>
                                            <th>URL Mặc Định</th>
                                            <th>Dựa trên quyền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td class="font-weight-bold">{{ $role->name }}</td>
                                                <td>
                                                    <span class="default-url" data-role-id="{{ $role->id }}">/dashboard</span>
                                                </td>
                                                <td>
                                                    <span class="default-permission" data-role-id="{{ $role->id }}">-</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="card bg-light mt-4">
                                <div class="card-header">
                                    <h3 class="card-title">Cấu hình ánh xạ Quyền → URL</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Quyền</th>
                                                        <th>URL</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($permissionRoutes ?? [] as $permission => $route)
                                                        <tr>
                                                            <td>{{ $permission }}</td>
                                                            <td>{{ $route }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            
                                            @if(empty($permissionRoutes))
                                                <div class="text-center text-muted py-3">
                                                    <i class="fas fa-info-circle mr-1"></i> 
                                                    Không có ánh xạ nào được cấu hình. Chỉnh sửa file cấu hình để thêm ánh xạ.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                    </button>
                    <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo Select2 với hỗ trợ nhóm quyền
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            templateResult: formatPermission,
            templateSelection: formatPermissionSelection
        });
        
        // Format option nhóm quyền trong dropdown
        function formatPermission(permission) {
            if (!permission.id) return permission.text;
            
            const group = $(permission.element).data('group');
            const $container = $(
                `<div class="permission-option">
                    <span class="badge badge-secondary mr-2">${group}</span>
                    <span>${permission.text}</span>
                </div>`
            );
            return $container;
        }
        
        // Format hiển thị quyền khi đã chọn
        function formatPermissionSelection(permission) {
            if (!permission.id) return permission.text;
            return permission.text;
        }
        
        // Tìm kiếm trong bảng
        $('#permission-search').keyup(debounce(function() {
            const value = $(this).val().toLowerCase();
            $(".role-row").filter(function() {
                const roleName = $(this).find('td:first').text().toLowerCase();
                const showRole = roleName.indexOf(value) > -1;
                $(this).toggle(showRole);
            });
        }, 300));
        
        // Chọn tất cả các quyền cho tất cả vai trò
        $('#select-all-btn').click(function() {
            $('.permission-select').each(function() {
                const options = $(this).find('option').map(function() {
                    return $(this).attr('value');
                }).get();
                
                $(this).val(options);
                $(this).trigger('change');
            });
            
            Toast.fire({
                icon: 'success',
                title: 'Đã chọn tất cả quyền'
            });
        });
        
        // Bỏ chọn tất cả các quyền
        $('#deselect-all-btn').click(function() {
            $('.permission-select').val(null).trigger('change');
            
            Toast.fire({
                icon: 'info',
                title: 'Đã bỏ chọn tất cả quyền'
            });
        });
        
        // Cập nhật URL mặc định dựa trên quyền được chọn
        function updateDefaultUrl() {
            $('.permission-select').each(function() {
                const roleId = $(this).data('role-id');
                const selectedPermissions = $(this).val() || [];
                
                // Ánh xạ quyền đến URL
                let redirectUrl = '/dashboard';
                let redirectPermission = '-';
                
                for (const permission of selectedPermissions) {
                    if (permissionToRoute[permission]) {
                        redirectUrl = permissionToRoute[permission];
                        redirectPermission = permission;
                        break;
                    }
                }
                
                // Cập nhật URL mặc định hiển thị
                $(`.default-url[data-role-id="${roleId}"]`).text(redirectUrl);
                $(`.default-permission[data-role-id="${roleId}"]`).text(redirectPermission);
            });
        }
        
        // Khởi tạo URL mặc định ban đầu
        updateDefaultUrl();
        
        // Cập nhật URL khi thay đổi quyền
        $('.permission-select').on('change', function() {
            updateDefaultUrl();
        });
        
        // Xác nhận trước khi gửi form
        $('#role-permissions-form').on('submit', function(e) {
            if (confirm('Bạn có chắc chắn muốn cập nhật phân quyền?')) {
                return true;
            }
            e.preventDefault();
            return false;
        });
    });
</script>
@endsection