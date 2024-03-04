@extends('adminlte::page')

@section('title', 'Dashboard')
@section('content_header')
    <h1>Área Fiscal</h1>
@stop
@section('content')
<section class="content">
    <div class="container-fluid">
       
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>150</h3>
                        <p>NF-e</p>
                    </div>
                    <div class="icon">
                        <i>
                            <img src="{{ asset('img/icon-nfe.png') }}" style="max-width: 40%; max-height: 40%; float: right; filter: opacity(50%);" />
                        </i>
                    </div>
                        <a href="#" class="small-box-footer"> Detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>53</h3>
                        <p>NFS-e</p>
                    </div>
                    <div class="icon">
                        <i>
                            <img src="{{ asset('img/icon-nfse.png') }}" style="max-width: 40%; max-height: 40%; float: right; filter: opacity(50%);" />
                        </i>
                    </div>
                        <a href="#" class="small-box-footer"> Detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>44</h3>
                        <p>CT-e</p>
                    </div>
                    <div class="icon">
                        <i>
                            <img src="{{ asset('img/icon-cte.png') }}" style="max-width: 40%; max-height: 40%; float: right; filter: opacity(50%);" />
                        </i>
                    </div>
                        <a href="#" class="small-box-footer"> Detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>65</h3>
                        <p>MD-e</p>
                    </div>
                    <div class="icon">
                        <i>
                            <img src="{{ asset('img/icon-mde.png') }}" style="max-width: 40%; max-height: 40%; float: right; filter: opacity(50%);" />
                        </i>
                    </div>
                        <a href="#" class="small-box-footer"> Detalhes <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
@stop
@section('css')
@stop

@section('js')
    <script>
        console.log('Hi!');
    </script>
@stop
