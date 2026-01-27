@extends('layouts.app')

@section('title', 'Editar Servicio')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2"><i class="fas fa-edit me-2"></i>Editar Servicio</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('servicios.update', $servicio->idserv) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Servicio *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $servicio->nombre) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $servicio->descripcion) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" class="form-control" id="precio" name="precio" value="{{ old('precio', $servicio->precio) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto del Servicio</label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            @if($servicio->foto)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($servicio->foto) }}" alt="Foto actual" width="100" class="rounded">
                                    <small class="text-muted d-block">Foto actual</small>
                                </div>
                            @endif
                            <div class="form-text">Dejar vacío para mantener la foto actual</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label">Tipo de Servicio *</label>
                            <div class="checkbox-group">
                                <div class="form-check checkbox-item">
                                    <input class="form-check-input" type="radio" name="tipo_servicio" id="interno" value="Interno" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'Interno' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="interno">Interno</label>
                                </div>
                                <div class="form-check checkbox-item">
                                    <input class="form-check-input" type="radio" name="tipo_servicio" id="externo" value="Externo" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'Externo' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="externo">Externo</label>
                                </div>
                                <div class="form-check checkbox-item">
                                    <input class="form-check-input" type="radio" name="tipo_servicio" id="domicilio" value="Domicilio" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'Domicilio' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="domicilio">Domicilio</label>
                                </div>
                                <div class="form-check checkbox-item">
                                    <input class="form-check-input" type="radio" name="tipo_servicio" id="online" value="Online" {{ old('tipo_servicio', $servicio->tipo_servicio) == 'Online' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="online">Online</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" value="{{ old('categoria', $servicio->categoria) }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="duracion" class="form-label">Duración (horas)</label>
                                    <input type="number" step="0.5" class="form-control" id="duracion" name="duracion" value="{{ old('duracion', $servicio->duracion) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="personal_requerido" class="form-label">Personal Requerido</label>
                                    <input type="number" class="form-control" id="personal_requerido" name="personal_requerido" value="{{ old('personal_requerido', $servicio->personal_requerido) }}">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="disponible" name="disponible" {{ old('disponible', $servicio->disponible) ? 'checked' : '' }}>
                                <label class="form-check-label" for="disponible">Disponible</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="materiales_incluidos" name="materiales_incluidos" {{ old('materiales_incluidos', $servicio->materiales_incluidos) ? 'checked' : '' }}>
                                <label class="form-check-label" for="materiales_incluidos">Materiales Incluidos</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="garantia" name="garantia" {{ old('garantia', $servicio->garantia) ? 'checked' : '' }}>
                                <label class="form-check-label" for="garantia">Incluye Garantía</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Actualizar Servicio
                    </button>
                    <a href="{{ route('servicios.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
