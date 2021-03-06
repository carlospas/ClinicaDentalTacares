@section('content')
@if(!$user->trashed())
<div class="btn-group">
    <a class="btn btn-info" href="{{ URL::route('user-edit', array('id' => $user->id)) }}">Editar</a>
    <a class="btn btn-default confirm-action" href="{{ URL::route('user-delete', array('id' => $user->id)) }}">Bloquear</a>
</div>
@else
<div class="btn-group">
    <a class="btn btn-default confirm-action" href="{{ URL::route('user-restore', array('id' => $user->id)) }}">Restaurar</a>
</div>
@endif
<br/><br/>
@include('layouts.alerts')
<div class="row">
    <!-- Personal Data -->
    <div class="col-md-6">
        <h4>Datos Personales</h4>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td style="width: 170px"><strong>{{ $user->person->document->description }}</strong></td>
                    <td>{{ $user->person->document_value }}</td>
                </tr>
                <tr>
                    <td><strong>Nombre</strong></td>
                    <td>{{ $user->person->firstname }}</td>
                </tr>
                <tr>
                    <td><strong>Primer Apellido</strong></td>
                    <td>{{ $user->person->middlename }}</td>
                </tr>
                <tr>
                    <td><strong>Segundo Apellido</strong></td>
                    <td>{{ $user->person->lastname }}</td>
                </tr>
                <tr>
                    <td><strong>Género</strong></td>
                    <td>{{ $user->person->gender->description }}</td>
                </tr>
                <tr>
                    <td><strong>Fecha de Nacimiento</strong></td>
                    <td>{{ date('d F, Y', strtotime($user->person->birthdate)) }}</td>
                </tr>
                <tr>
                    <td><strong>Edad</strong></td>
                    <td>{{ $user->person->age() }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- Account -->
    <div class="col-md-6">
        <h4>Cuenta</h4>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td style="width: 170px"><strong>Correo Electrónico</strong></td>
                    <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
                </tr>
                <tr>
                    <td><strong>Rol</strong></td>
                    <td>{{ $user->roles()->first()->name }}</td>
                </tr>
                <tr>
                    <td><strong>Estado</strong></td>
                    <td>
                        <span class="label {{ !$user->trashed() ? 'label-success' : 'label-danger' }}" >
                            {{ !$user->trashed() ? 'Activo' : 'Bloqueado' }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td><strong>Confirmación</strong></td>
                    <td><a href="#" data-role="popover" data-content="Código de confirmación: {{ $user->confirmation_code }}">
                        {{ $user->is_confirmed ? 'Completado' : 'Pendiente' }}
                    </a></td>
                </tr>
                <tr>
                    <td><strong>Fecha de Creación</strong></td>
                    <td>{{ date('d/m/Y h:i:s a', strtotime($user->created_at)) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div><!-- End Row -->
<hr/>
<div class="row">
    <!-- Addresses -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                @if(!$user->trashed())
                <a class="btn btn-info btn-xs pull-right" href="{{ URL::route('address-add', $user->person_id) }}">Agregar</a>
                @endif
                <h3 class="panel-title">Direcciones</h3>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Ciudad</th>
                        <th>Provincia</th>
                        <th class="hidden-xs">Dirección</th>
                        @if(!$user->trashed())
                        <th class="actions hidden-xs">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(!count($user->person->addresses))
                    <tr>
                        <td class="text-center" colspan="100">Ningún dato disponible en esta tabla.</td>
                    </tr>
                    @endif
                    @foreach($user->person->addresses as $a)
                    <tr>
                        <td>{{ $a->city->name }}</td>
                        <td>{{ $a->estate->name }}</td>
                        <td class="hidden-xs">
                            <button class="btn btn-link btn-xs" type="button" data-toggle="popover" data-content="{{ $a->address }}">
                            {{ substr($a->address, 0, 30) }}
                            </button>
                        </td>
                        @if(!$user->trashed())
                        <td class="actions hidden-xs">
                            <div class="btn-group">
                                <a class="btn btn-default btn-xs confirm-action" href="{{ URL::route('address-delete', array('id' => $a->id)) }}" title="Eliminar">
                                    <i class="fa fa-trash-o"></i>
                                </a>
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Telephones -->
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading">
                @if(!$user->trashed())
                <a class="btn btn-info btn-xs pull-right" href="{{ URL::route('telephone-add', $user->person_id) }}">Agregar</a>
                @endif
                <h3 class="panel-title">Teléfonos</h3>
            </div>
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Número Teléfonico</th>
                        @if(!$user->trashed())
                        <th class="actions">Acciones</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(!count($user->person->telephones))
                    <tr>
                        <td class="text-center" colspan="100">Ningún dato disponible en esta tabla.</td>
                    </tr>
                    @endif
                    @foreach($user->person->telephones as $t)
                    <tr>
                        <td>{{ '+506' . $t->number }}</td>
                        @if(!$user->trashed())
                        <td class="actions">
                            <a class="btn btn-success btn-xs visible-xs" href="tel:{{ '+506' . $t->number }}" title="Llamar">
                                <i class="fa fa-phone"></i>
                            </a>
                            <a class="btn btn-default btn-xs hidden-xs confirm-action" href="{{ URL::route('telephone-delete', $t->id) }}" title="Eliminar">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div><!-- End Row -->
@stop