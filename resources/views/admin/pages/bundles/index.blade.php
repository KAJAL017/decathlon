@extends('admin.layouts.app')

@section('title', 'Product Bundles')

@section('content')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Product Bundles</h1>
            <p class="text-sm text-gray-600 mt-1">Create and manage product bundles to increase AOV</p>
        </div>
        <button onclick="openAddBundleModal()" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#0082C3] text-white text-sm font-semibold rounded-lg hover:bg-[#006ba3] transition-all shadow-sm hover:shadow-md">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Create Bundle
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Total Bundles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="totalBundles">0</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 p-5">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-600">Active Bundles</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1" id="activeBundles">0</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Bundles Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bundle</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pricing</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="bundlesTableBody" class="divide-y divide-gray-200 bg-white">
                    <!-- Bundles will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection