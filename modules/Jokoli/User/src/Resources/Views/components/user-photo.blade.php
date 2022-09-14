<div class="profile__info border cursor-pointer text-center">
    <form action="{{ route('users.photo') }}" method="post" class="d-inline" enctype="multipart/form-data">
        @csrf
        <div class="avatar__img">
            <img src="{{ auth()->user()->thumb }}" alt="{{ auth()->user()->name }}" class="avatar___img">
            <input type="file" accept="image/*" name="photo" class="hidden avatar-img__input">
            <div class="v-dialog__container" style="display: block;"></div>
            <div class="box__camera default__avatar"></div>
        </div>
        <span class="profile__name white-space-nowrap">کاربر : {{ auth()->user()->name }}</span>
    </form>
</div>
