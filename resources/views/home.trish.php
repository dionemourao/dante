@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div class="welcome-message">
        <p>{{ $message }}</p>
    </div>
    
    {{-- Este é um comentário que não será renderizado --}}
    
    @if(isset($features) && count($features) > 0)
        <div class="features">
            <h2>Recursos</h2>
            <ul>
                @foreach($features as $feature)
                    <li>{{ $feature }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@endsection