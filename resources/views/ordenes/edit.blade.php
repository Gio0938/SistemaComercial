@extends('layouts.app')

@section('title', 'Editar Orden #' . $orden->folio)

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-edit me-2"></i>Editar Orden de Servicio #{{ $orden->folio }}
                </h3>
                <div class="card-tools">
                    <a href="{{ route('ordenes.show', $orden->idorden) }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('ordenes.update', $orden->idorden) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <select name="estado" class="form-control">
                                    <option value="Pendiente" {{ $orden->estado == 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="En Proceso" {{ $orden->estado == 'En Proceso' ? 'selected' : '' }}>En Proceso</option>
                                    <option value="Completado" {{ $orden->estado == 'Completado' ? 'selected' : '' }}>Completado</option>
                                    <option value="Entregado" {{ $orden->estado == 'Entregado' ? 'selected' : '' }}>Entregado</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Diagnóstico</label>
                                <textarea name="diagnostico" class="form-control" rows="3">{{ $orden->diagnostico }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Actualizar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
