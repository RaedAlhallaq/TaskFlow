@extends('layouts.app')

@section('title', 'إدارة المهام')

@section('content')
    {{-- المكوّن التفاعلي: كل شيء داخله يُحدَّث عبر Livewire دون إعادة تحميل --}}
    <livewire:task-manager />
@endsection
