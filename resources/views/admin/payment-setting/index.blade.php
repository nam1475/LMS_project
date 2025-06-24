@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Payment Settings</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.course-levels.index') }}" class="btn btn-primary">
                            <!-- Download SVG icon from http://tabler-icons.io/i/plus -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l14 0" />
                                <path d="M5 12l6 6" />
                                <path d="M5 12l6 -6" />
                            </svg>
                            Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs nav-fill" data-bs-toggle="tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a href="#vnpay-setting" class="nav-link active" data-bs-toggle="tab" aria-selected="false"
                                        role="tab" tabindex="-1">VNPay Settings</a>
                                </li>
                                {{-- <li class="nav-item" role="presentation">
                                    <a href="#stripe-setting" class="nav-link" data-bs-toggle="tab" aria-selected="false"
                                        role="tab" tabindex="-1">Stripe Settings</a>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active" id="vnpay-setting" role="tabpanel">
                                    <form action="{{ route('admin.vnpay-setting.update') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Status</label>
                                                    <select name="vnpay_status" class="form-control" >
                                                        <option @selected(config('gateway_settings.vnpay_status') === '1') value="1">Active</option>
                                                        <option @selected(config('gateway_settings.vnpay_status') === '0') value="0">Inactive</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('vnpay_status')" class="mt-2" />
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Rate (USD)</label>
                                                    <input type="text" class="form-control" name="vnpay_rate"
                                                        placeholder="Enter vnpay Rate" value="{{ config('gateway_settings.vnpay_rate') }}">
                                                    <x-input-error :messages="$errors->get('vnpay_rate')" class="mt-2" />
                                                </div>
                                            </div> --}}
    
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">TMN Code</label>
                                                    <input type="text" class="form-control" name="vnpay_tmn_code"
                                                        placeholder="Enter vnpay tmn code" value="{{ config('gateway_settings.vnpay_tmn_code') }}">
                                                    <x-input-error :messages="$errors->get('vnpay_tmn_code')" class="mt-2" />
                                                </div>
                                            </div>
    
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Hash Secret</label>
                                                    <input type="text" class="form-control" name="vnpay_hash_secret"
                                                        placeholder="Enter vnpay hash secret" value="{{ config('gateway_settings.vnpay_hash_secret') }}">
                                                    <x-input-error :messages="$errors->get('vnpay_hash_secret')" class="mt-2" />
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">URL</label>
                                                    <input type="text" class="form-control" name="vnpay_url"
                                                        placeholder="Enter vnpay url" value="{{ config('gateway_settings.vnpay_url') }}">
                                                    <x-input-error :messages="$errors->get('vnpay_url')" class="mt-2" />
                                                </div>
                                            </div>
                                            
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>

                                {{-- <div class="tab-pane" id="stripe-setting" role="tabpanel">
                                    <form action="{{ route('admin.stripe-setting.update') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Stripe Staus</label>
                                                    <select name="stripe_status" class="form-control" >
                                                        <option @selected(config('gateway_settings.stripe_status') === 'active') value="active">Active</option>
                                                        <option @selected(config('gateway_settings.stripe_status') === 'inactive') value="inactive">Inactive</option>
                                                    </select>
                                                    <x-input-error :messages="$errors->get('stripe_status')" class="mt-2" />
                                                </div>
                                            </div>
    
                                            <div class="col-md-5">
                                                <div class="mb-3">
                                                    <label class="form-label">Currency</label>
                                                    <select name="stripe_currency" class="form-control select2" >
                                                        @foreach(config('gateway_currencies.stripe_currencies') as $key => $value)
                                                        <option @selected(config('gateway_settings.stripe_currency') == $value)  value="{{ $value }}">{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                    
                                                    <x-input-error :messages="$errors->get('vnpay_currency')" class="mt-2" />
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Rate (USD)</label>
                                                    <input type="text" class="form-control" name="stripe_rate"
                                                        placeholder="Enter Stripe Rate" value="{{ config('gateway_settings.stripe_rate') }}">
                                                    <x-input-error :messages="$errors->get('stripe_rate')" class="mt-2" />
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Publishable Key</label>
                                                    <input type="text" class="form-control" name="stripe_publishable_key"
                                                        placeholder="Enter Stripe publishable key" value="{{ config('gateway_settings.stripe_publishable_key') }}">
                                                    <x-input-error :messages="$errors->get('stripe_publishable_key')" class="mt-2" />
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Clinet Secret</label>
                                                    <input type="text" class="form-control" name="stripe_secret"
                                                        placeholder="Enter Stripe client secret" value="{{ config('gateway_settings.stripe_secret') }}">
                                                    <x-input-error :messages="$errors->get('stripe_secret')" class="mt-2" />
                                                </div>
                                            </div>
                            
    
                                            
                                        </div>
                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
