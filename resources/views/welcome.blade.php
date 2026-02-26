@extends('layouts.frontend.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="text-center p-8 bg-white rounded-xl shadow-2xl max-w-2xl w-full">
            <h1 class="text-6xl font-extrabold text-blue-700 mb-6">প্রজেক্ট ম্যানেজমেন্ট সিস্টেম</h1>
            <p class="text-2xl text-gray-700 mb-12">স্বাগতম! আপনার সব প্রজেক্ট এখানে সহজে ম্যানেজ করুন।</p>

            <div class="flex justify-center space-x-8">
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-10 py-4 text-xl">লগইন করুন</a>
                <a href="{{ route('register') }}" class="btn btn-success btn-lg px-10 py-4 text-xl">নতুন একাউন্ট খুলুন</a>
            </div>
        </div>
    </div>
@endsection