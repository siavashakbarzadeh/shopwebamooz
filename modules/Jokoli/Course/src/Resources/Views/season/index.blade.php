<div class="col-12 bg-white margin-bottom-15 border-radius-3">
    <p class="box__title">سرفصل ها</p>
    <form action="{{ route('seasons.store',$course->id) }}" method="post" class="padding-30">
        @csrf
        <x-input type="text" name="title" placeholder="عنوان سرفصل" required/>
        <x-input type="text" name="priority" placeholder="شماره سرفصل"/>
        <button class="btn btn-webamooz_net">اضافه کردن</button>
    </form>
    <div class="table__box padding-30">
        <table class="table">
            <thead role="rowgroup">
            <tr role="row" class="title-row">
                <th class="p-r-90">ردیف</th>
                <th>عنوان فصل</th>
                <th>وضعیت</th>
                <th>وضعیت تایید</th>
                <th>عملیات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($course->seasons as $season)
                <tr role="row" class="">
                    <td>{{ $season->priority }}</td>
                    <td>{{ $season->title }}</td>
                    <td class="js-text-status {{ $season->getStatusCssClass() }}">{{ $season->status_fa }}</td>
                    <td class="js-text-confirmation-status {{ $season->getConfirmationStatusCssClass() }}">{{ $season->confirmation_status_fa }}</td>
                    <td>
                        <a href=""
                           onclick="deleteItem(event,'{{ route('seasons.destroy',$season->id) }}')"
                           class="c-item-delete mlg-15" title="حذف"></a>
                        @can(\Jokoli\Permission\Enums\Permissions::ManageCourses)
                            <a href=""
                               onclick="updateConfirmationStatus(event,'{{ route('seasons.lock',$season->id) }}','js-text-status')"
                               class="item-lock mlg-15" title="قفل فصل"></a>
                            <a href=""
                               onclick="updateConfirmationStatus(event,'{{ route('seasons.unlock',$season->id) }}','js-text-status')"
                               class="c-item-recover mlg-15" title="بازکردن فصل"></a>
                            <a href=""
                               onclick="updateConfirmationStatus(event,'{{ route('seasons.reject',$season->id) }}','js-text-confirmation-status')"
                               class="item-reject mlg-15" title="رد"></a>
                            <a href=""
                               onclick="updateConfirmationStatus(event,'{{ route('seasons.accept',$season->id) }}','js-text-confirmation-status')"
                               class="item-confirm mlg-15" title="تایید"></a>
                        @endcan
                        <a href="{{ route('seasons.edit',$season->id) }}" class="item-edit " title="ویرایش"></a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
