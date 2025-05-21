@extends('layouts.app')

@section('title', 'Phân Quyền Theo Người Dùng')
@section('page-title', 'Ma Trận Phân Quyền Người Dùng')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Người Dùng</a></li>
    <li class="breadcrumb-item active">Phân Quyền</li>
@endsection

@section('needs_datatables', true)
@section('needs_select2', true)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-user-shield mr-1"></i> Quản lý quyền người dùng
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="maximize">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="user-filter">Lọc người dùng</label>
                            <select id="user-filter" class="form-control select2" data-placeholder="Chọn người dùng">
                                <option value="">Tất cả người dùng</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="permission-filter">Lọc quyền</label>
                            <select id="permission-filter" class="form-control select2" multiple data-placeholder="Chọn quyền">
                                @foreach ($permissions as $permission)
                                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-info"></i> Hướng dẫn!</h5>
                    <ul class="mb-0">
                        <li>Bạn có thể phân quyền trực tiếp cho người dùng hoặc thông qua vai trò.</li>
                        <li>Quyền từ vai trò được hiển thị dưới dạng <span class="badge badge-secondary">kế thừa</span></li>
                        <li>Quyền trực tiếp được hiển thị dưới dạng <span class="badge badge-primary">trực tiếp</span></li>
                    </ul>
                </div>
                
                <form id="user-permissions-form" action="{{ route('users.permissions.update') }}" method="POST">
                    @csrf
                    
                    <!-- Bảng Phân Quyền Người Dùng - Vai Trò -->
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-users-cog mr-1"></i> Phân Quyền Theo Vai Trò
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped permission-matrix" id="roles-table">
                            <thead class="bg-light">
                                <tr>
                                    <th class="user-column">Người Dùng</th>
                                    @foreach ($roles as $role)
                                        <th>{{ $role->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="user-row" data-user-id="{{ $user->id }}">
                                        <td class="user-column">
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <img src="{{ asset('adminlte/img/user-default.png') }}" 
                                                        class="img-circle elevation-1" 
                                                        width="32" height="32" alt="User Image">
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        @foreach ($roles as $role)
                                            <td class="text-center">
                                                <div class="icheck-primary d-inline">
                                                    <input type="checkbox" 
                                                        id="role-{{ $user->id }}-{{ $role->id }}" 
                                                        name="roles[{{ $user->id }}][]" 
                                                        value="{{ $role->name }}" 
                                                        {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                                    <label for="role-{{ $user->id }}-{{ $role->id }}"></label>
                                                </div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Bảng Phân Quyền Người Dùng - Quyền Trực Tiếp -->
                    <h5 class="mt-4 mb-3">
                        <i class="fas fa-key mr-1"></i> Phân Quyền Trực Tiếp 
                        <small class="text-muted">(ghi đè vai trò)</small>
                    </h5>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="permissions-table">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 300px">Người Dùng</th>
                                    <th>Quyền Trực Tiếp</th>
                                    <th style="width: 350px">Tất Cả Quyền (Bao gồm từ vai trò)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr class="user-row" data-user-id="{{ $user->id }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="mr-2">
                                                    <img src="{{ asset('adminlte/img/user-default.png') }}" 
                                                        class="img-circle elevation-1" 
                                                        width="32" height="32" alt="User Image">
                                                </div>
                                                <div>
                                                    <div class="font-weight-bold">{{ $user->name }}</div>
                                                    <div class="text-muted small">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <select name="permissions[{{ $user->id }}][]" 
                                                class="form-control select2 direct-permissions" 
                                                data-user-id="{{ $user->id }}" multiple 
                                                data-placeholder="Chọn quyền trực tiếp">
                                                @foreach ($permissions as $permission)
                                                    <option value="{{ $permission->name }}"
                                                        {{ $user->hasDirectPermission($permission->name) ? 'selected' : '' }}>
                                                        {{ $permission->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <div class="user-all-permissions" data-user-id="{{ $user->id }}">
                                                @foreach ($user->getAllPermissions() as $permission)
                                                    <span class="badge {{ $user->hasDirectPermission($permission->name) ? 'badge-primary' : 'badge-secondary' }} mr-1 mb-1">
                                                        {{ $permission->name }}
                                                    </span>
                                                @endforeach
                                                
                                                @if ($user->getAllPermissions()->isEmpty())
                                                    <span class="text-muted">Không có quyền nào</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Lưu Thay Đổi
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Quay Lại
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-scripts')
<script>
    $(document).ready(function() {
        // Khởi tạo DataTables
        const rolesTable = $('#roles-table').DataTable({
            "paging": false,
            "searching": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "emptyTable": "Không có dữ liệu"
            },
            "columnDefs": [
                { "width": "300px", "targets": 0 }
            ],
            "fixedColumns": {
                leftColumns: 1
            }
        });
        
        // Khởi tạo Select2
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
        
        // Lọc người dùng
        $('#user-filter').on('change', function() {
            const userId = $(this).val();
            
            if (userId) {
                $('.user-row').hide();
                $(`.user-row[data-user-id="${userId}"]`).show();
            } else {
                $('.user-row').show();
            }
        });
        
        // Lọc quyền 
        $('#permission-filter').on('change', function() {
            const selectedPermissions = $(this).val() || [];
            
            // Nếu không có quyền nào được chọn, hiển thị tất cả
            if (selectedPermissions.length === 0) {
                $('.user-row').show();
                return;
            }
            
            // Duyệt qua từng hàng người dùng
            $('.user-row').each(function() {
                const userId = $(this).data('user-id');
                const userPermissions = $(this).find('.user-all-permissions').text();
                
                let shouldShow = false;
                // Kiểm tra nếu có ít nhất một quyền được chọn trong danh sách quyền của người dùng
                for (const permission of selectedPermissions) {
                    if (userPermissions.includes(permission)) {
                        shouldShow = true;
                        break;
                    }
                }
                
                $(this).toggle(shouldShow);
            });
        });
        
        // Cập nhật hiển thị tất cả quyền khi thay đổi vai trò hoặc quyền trực tiếp
        function updateAllPermissions() {
            // Xử lý logic phức tạp hơn thực tế - Đây chỉ là mẫu
            Toast.fire({
                icon: 'info',
                title: 'Đã cập nhật hiển thị quyền.'
            });
        }
        
        // Sự kiện thay đổi vai trò
        $('input[name^="roles"]').on('change', function() {
            updateAllPermissions();
        });
        
        // Sự kiện thay đổi quyền trực tiếp
        $('.direct-permissions').on('change', function() {
            updateAllPermissions();
        });
        
        // Xác nhận trước khi gửi form
        $('#user-permissions-form').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Xác nhận thay đổi?',
                text: "Bạn có chắc chắn muốn cập nhật phân quyền cho người dùng?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Xác nhận',
                cancelButtonText: 'Hủy'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection