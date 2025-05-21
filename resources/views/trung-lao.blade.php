@extends('layouts.app')

    @section('title', 'Ban Trung Lão')
    @section('page-title', 'Ban Trung Lão')
    @section('breadcrumb')
        <li class="breadcrumb-item active">Ban Trung Lão</li>
    @endsection

    @section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Chào mừng đến với Ban Trung Lão</h4>
                    <p>Xin chào, {{ Auth::user()->name }}!</p>
                </div>
            </div>
        </div>
    </div>
    @endsection