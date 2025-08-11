@extends('layouts.app')

@section('content')
<div class="container text-center" style="margin-top: 50px;">
    <h2>Register AS</h2>
    <div class="mt-4">
        <a href="{{ route('register.patient') }}" class="btn btn-primary mx-3 px-5 py-3 fs-5">Patient</a>
        <a href="{{ route('register.caregiver') }}" class="btn btn-secondary mx-3 px-5 py-3 fs-5">Caregiver</a>
    </div>
</div>
@endsection
