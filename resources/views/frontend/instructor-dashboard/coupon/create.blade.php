@extends('frontend.layouts.master')

@section('content')
    <!--===========================
                BREADCRUMB START
            ============================-->
    <section class="wsus__breadcrumb" style="background: url({{ asset(config('settings.site_breadcrumb')) }});">
        <div class="wsus__breadcrumb_overlay">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInUp">
                        <div class="wsus__breadcrumb_text">
                            <h1>Add Courses</h1>
                            <ul>
                                <li><a href="#">Home</a></li>
                                <li>Add Courses</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--===========================
                BREADCRUMB END
            ============================-->


    <!--=============================
                DASHBOARD ADD COURSE START
            ==============================-->
    <section class="wsus__dashboard mt_90 xs_mt_70 pb_120 xs_pb_100">
        <div class="container">
            <div class="row">
                @include('frontend.instructor-dashboard.sidebar')

                <div class="col-xl-9 col-md-8 wow fadeInRight">
                    <div class="wsus__dashboard_contant">
                        <div class="wsus__dashboard_contant_top">
                            <div class="wsus__dashboard_heading relative">
                                <h5>Coupons</h5>
                                <p>Manage your coupons and its update like live, draft and insight.</p>
                            </div>
                        </div>
                        <div class="dashboard_add_courses">
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                    <div class="add_course_basic_info">
                                        <form action="{{ route('instructor.coupons.store') }}" method="post" class="basic_info_form course-form" enctype="multipart/form-data">
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
                                                        <label class="form-label text-capitalize" >Minimum Order Amount</label>
                                                        <input type="number" value="0" id="minimum_order_amount" name="minimum_order_amount" class="form-control">
                                                    </div>
                                                </div> 
                    
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        <label for="" >Course Categories</label>
                                                        <select class="select_2" name="course_category_id[]" multiple>
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

                                                <div class="col-xl-12">
                                                    <div class="add_course_basic_info_imput mb-0">
                                                        <button type="submit" class="common_btn mt_20">Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--=============================
                DASHBOARD ADD COURSE END
            ==============================-->

@endsection