@extends('layouts.guest')

@section('content')
    <h1 class="text-3xl font-bold mb-6">Smarter Grooming Salon Scheduling Starts Here</h1>

    <p class="mb-4 text-lg leading-relaxed">
        PETSAppeal is the all-in-one software solution for pet grooming salons. Easily manage appointments, clients, pets, and staff — all from one intuitive dashboard.
    </p>

    <p class="mb-8 text-lg leading-relaxed">
        Our platform simplifies day-to-day scheduling so you can focus on what matters most: delivering exceptional grooming services to happy pets and their owners.
    </p>

    <h2 class="text-2xl font-semibold mt-10 mb-4">What’s Available Now</h2>
    <ul class="list-disc list-inside text-lg mb-6">
        <li>Client and pet management</li>
        <li>Staff scheduling and availability</li>
        <li>Appointment booking with calendar view</li>
        <li>Dark mode-friendly user interface</li>
    </ul>

    <h2 class="text-2xl font-semibold mt-10 mb-4">Coming Soon</h2>
    <ul class="list-disc list-inside text-lg mb-10">
        <li><strong>Grooming Reports & Statistics</strong> – Track performance, revenue, and service history</li>
        <li><strong>Retail Point of Sale (POS)</strong> – Sell grooming products and manage inventory</li>
        <li><strong>Boarding Management</strong> – Track stays, feeding, and medication schedules</li>
        <li><strong>Daycare Scheduling</strong> – Streamlined drop-off and pick-up management</li>
    </ul>

    <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-blue-600 text-white text-lg font-semibold rounded hover:bg-blue-700 transition">
        Get Started Free
    </a>
@endsection
