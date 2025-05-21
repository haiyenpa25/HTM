@extends('layouts.app')

  @section('title', 'Xem Người Dùng')
  @section('page-title', 'Xem Người Dùng')
  @section('breadcrumb')
      <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Người Dùng</a></li>
      <li class="breadcrumb-item active">Xem</li>
  @endsection

  @section('content')
  <div class="row">
      <div class="col-12">
          <div class="card">
              <div class="card-body">
                  <h4 class="card-title">Thông Tin Người Dùng</h4>
                  <p>Email: {{ $user->email }}</p>
                  <p>Tên: {{ $user->name }}</p>
                  <a href="{{ route('users.index') }}" class="btn btn-secondary">Quay Lại</a>
              </div>
          </div>
      </div>
  </div>
  @endsection