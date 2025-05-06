@extends('layouts.app')

@section('content')
    <h1>{{ $title }}</h1>
    
    <div class="about-content">
        <p>{{ $content }}</p>
        
        @php
            $year = date('Y');
            $foundedYear = 2023;
            $yearsActive = $year - $foundedYear;
        @endphp
        
        <p>Estamos ativos há {{ $yearsActive }} {{ $yearsActive == 1 ? 'ano' : 'anos' }}.</p>
    </div>
    
    @include('partials.team')
@endsection