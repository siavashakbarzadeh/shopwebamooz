@extends('Dashboard::master')
@section('title') مدیریت دوره‌ها | @parent @endsection
@section('breadcrumb')
    <li><a href="{{ route('courses.index') }}" title="مدیریت دوره‌ها">مدیریت دوره‌ها</a></li>
@endsection

@section('content')
    <div class="main-content">
        <div class="tab__box">
            <div class="tab__items">
                <a class="tab__item is-active" href="courses.html">لیست دوره ها</a>
                <a class="tab__item" href="approved.html">دوره های تایید شده</a>
                <a class="tab__item" href="new-course.html">دوره های تایید نشده</a>
                <a class="tab__item" href="{{ route('courses.create') }}">ایجاد دوره جدید</a>
            </div>
        </div>
        <div class="bg-white padding-20">
            <div class="t-header-search">
                <form action="" onclick="event.preventDefault();">
                    <div class="t-header-searchbox font-size-13">
                        <input type="text" class="text search-input__box font-size-13" placeholder="جستجوی دوره">
                        <div class="t-header-search-content ">
                            <input type="text" class="text" placeholder="نام دوره">
                            <input type="text" class="text" placeholder="ردیف">
                            <input type="text" class="text" placeholder="قیمت">
                            <input type="text" class="text" placeholder="نام مدرس">
                            <input type="text" class="text margin-bottom-20" placeholder="دسته بندی">
                            <btutton class="btn btn-webamooz_net">جستجو</btutton>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="table__box">
            <table class="table">
                <thead role="rowgroup">
                <tr role="row" class="title-row">
                    <th>شناسه</th>
                    <th>ردیف</th>
                    <th>تصویر</th>
                    <th>عنوان</th>
                    <th>مدرس دوره</th>
                    <th>قیمت (تومان)</th>
                    <th>جزئیات</th>
                    <th>تراکنش ها</th>
                    <th>نظرات</th>
                    <th>تعداد دانشجویان</th>
                    <th>وضعیت تایید</th>
                    <th>درصد مدرس</th>
                    <th>وضعیت دوره</th>
                    <th>عملیات</th>
                </tr>
                </thead>
                <tbody>
                @foreach($courses as $course)
                    <tr role="row">
                        <td>{{ $course->id }}</td>
                        <td>{{ $courses->perPage()*($courses->currentPage()-1)+$loop->iteration }}</td>
                        <td>
                            <img src="{{ optional($course->banner)->thumb }}" width="80" alt="{{ $course->title }}">
                        </td>
                        <td>{{ $course->title }}</td>
                        <td>
                            <a href="">{{ $course->teacher->name }}</a>
                        </td>
                        <td>{{ number_format($course->price) }}</td>
                        <td><a href="{{ route('courses.details',$course->id) }}" class="color-2b4a83">مشاهده</a></td>
                        <td><a href="course-transaction.html" class="color-2b4a83">مشاهده</a></td>
                        <td><a href="" class="color-2b4a83">مشاهده (10 نظر)</a></td>
                        <td>{{ $course->students_count }}</td>
                        <td>
                            <div
                                class="js-text-confirmation-status {{ $course->getConfirmationStatusCssClass() }}">{{ $course->confirmation_status_fa }}</div>
                        </td>
                        <td>{{ $course->percent }}%</td>
                        <td>
                            <div class="js-text-status">{{ $course->status_fa }}</div>
                        </td>
                        <td>
                            @can(\Jokoli\Permission\Enums\Permissions::ManageCourses)
                                <a href=""
                                   onclick="deleteItem(event,'{{ route('courses.destroy',$course->id) }}')"
                                   class="item-delete mlg-15" title="حذف"></a>
                                <a href=""
                                   onclick="updateConfirmationStatus(event,'{{ route('courses.lock',$course->id) }}','js-text-status')"
                                   class="item-lock mlg-15" title="قفل دوره"></a>
                                <a href=""
                                   onclick="updateConfirmationStatus(event,'{{ route('courses.reject',$course->id) }}','js-text-confirmation-status')"
                                   class="item-reject mlg-15" title="رد"></a>
                                <a href=""
                                   onclick="updateConfirmationStatus(event,'{{ route('courses.accept',$course->id) }}','js-text-confirmation-status')"
                                   class="item-confirm mlg-15" title="تایید"></a>
                            @endcan
                            <a href="" target="_blank" class="item-eye mlg-15" title="مشاهده"></a>
                            <a href="{{ route('courses.edit',$course->id) }}" class="item-edit " title="ویرایش"></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div class="py-8">
            {{ $courses->links() }}
        </div>
    </div>
@endsection
