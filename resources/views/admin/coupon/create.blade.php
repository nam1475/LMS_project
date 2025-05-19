@extends('admin.layouts.master')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create Coupon</h3>
                    <div class="card-actions">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-primary">
                            <i class="ti ti-arrow-left"></i>
                            Back 
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.coupons.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-md-12 mt-3">
                                <x-input-block name="code" placeholder="Enter code" value="{{ old('code') }}" />
                            </div>   

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="" >Type</label>
                                    <select name="type" id="" class="form-control mt-2">
                                        <option value="">Select</option>
                                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                                        <option value="percent" {{ old('type') == 'percent' ? 'selected' : ''  }}>Percent</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                                </div>
                            </div>  

                            <div class="col-md-12 mt-3">
                                <x-input-block name="value" placeholder="Enter value" value="{{ old('value') }}" />
                            </div>  

                            <div class="col-md-12 mt-3">
                                <div class="mb-3">
                                    <label class="form-label text-capitalize" >Mnimum Order Amount</label>
                                    <input type="number" value="0" id="minimum_order_amount" name="minimum_order_amount" class="form-control">
                                </div>
                            </div> 

                            <div class="col-md-12 mt-3">
                                <div class="form-group">
                                    <label for="" >Course Categories</label>
                                    <select class="select2" name="course_category_id[]" multiple>
                                        @foreach($courseCategories as $category)
                                            @if($category->subCategories->isNotEmpty())
                                                <optgroup label="{{ $category->name }}">
                                                @foreach($category->subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, old('course_category_id', [])) ? 'selected' : '' }}>
                                                            {{ $subCategory->name }}
                                                        </option>
                                                @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('course_category_id')" class="mt-2" />
                                </div>
                            </div>
                            
                            <div class="col-md-12 mt-3">
                                <label for="" class="mb-2">Expire Date</label>
                                <input type="date" name="expire_date" class="form-control" value="{{ old('expire_date') }}" class="form-control" />
                            </div>  
                            
                            <div class="col-md-12">
                                <div class="form-group mt-3">
                                    <label for="" class="mb-2" >Description</label>
                                    <textarea name="description" class="editor"  >{{ old('description') }}</textarea>
                                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                                </div>
                            </div>

                            <div class="col-md-3">
                                <x-input-toggle-block name="status" label="Status" :checked="old('status') == 1" />
                            </div>
                        </div>

                        <div class="mb-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="ti ti-device-floppy"></i>  
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
