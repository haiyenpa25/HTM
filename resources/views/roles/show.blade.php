@extends('layouts.app')

  @section('title', 'Xem Vai Trò')
  @section('page-title', 'Xem Vai Trò')
  @section('breadcrumb')
      <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Vai Trò</a></li>
      <li class="breadcrumb-item active">Xem</li>
  @endsection

  @section('content')
  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">Thông Tin Vai Trò</h4>
                  <p>Tên Vai Trò: {{ $role->name }}</p>
                  <p>Quyền: {{ implode(', ', $role->permissions->pluck('name')->toArray()) }}</p>
                  <a href="{{ route('roles.index') }}" class="btn btn-secondary">Quay Lại</a>
              </div>
          </div>
      </div>
  </div>
  @endsection