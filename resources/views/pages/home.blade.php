@extends('layouts.app')

@section('title', 'Decathlon - Sports Equipment & Sportswear')

@section('content')
    <div class="space-y-0">
        @isset($sections)
            @foreach($sections as $section)
                @if(view()->exists("sections.{$section->type}"))
                    @include("sections.{$section->type}", ['data' => $section->data, 'section' => $section])
                @endif
            @endforeach
        @endisset
    </div>

@endsection
