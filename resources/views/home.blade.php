@extends('adminlte::page')

@section('title', 'Inicio')

@php
use App\Models\User;
@endphp

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body body">
            <div class="card card-widget widget-user">
                <div class="widget-user-header bg-dark header">
                    <h1 class="title">
                        <strong>Producci&oacute;n Web - Nicaragua</strong>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-3 col-md-4 col-xl-3">
                                            <div class="small-box bg-secondary">
                                                <div class="inner">
                                                    <h3 style="color: #fff !important;">&nbsp;</h3>
                                                    <h4 style="color: #fff !important;">Configuraci&oacute;n</h4>
                                                </div>
                                                <div class="icon">
                                                    <i class="fas fa-cog fa-spin"></i>
                                                </div>
                                                <a href="/home" class="small-box-footer">
                                                    <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
@stop