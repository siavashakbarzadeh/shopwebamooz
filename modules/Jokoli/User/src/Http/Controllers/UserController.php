<?php

namespace Jokoli\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddRoleUserRequest;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Jokoli\Common\Responses\AjaxResponses;
use Jokoli\Media\Services\MediaFileService;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\Permission\Enums\Roles;
use Jokoli\Permission\Repository\RoleRepository;
use Jokoli\User\Http\Requests\ProfileRequest;
use Jokoli\User\Http\Requests\UserPhotoRequest;
use Jokoli\User\Http\Requests\UserRequest;
use Jokoli\User\Models\User;
use Jokoli\User\Repository\UserRepository;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(RoleRepository $roleRepository)
    {
        $this->authorize('index', User::class);
        $users = $this->userRepository->paginate();
        $roles = $roleRepository->all();
        return view('User::admin.index', compact('users', 'roles'));
    }

    public function info($user)
    {
        $this->authorize('index', User::class);
        $user = $this->userRepository->findOrFailById($user);
        return view('User::admin.info',compact('user'));
    }

    public function edit(RoleRepository $roleRepository, $user)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepository->findOrFailById($user, ['courses']);
        $roles = $roleRepository->all();
        return view('User::admin.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, $user)
    {
        $this->authorize('edit', User::class);
        $user = $this->userRepository->findOrFailById($user);
        if ($request->hasFile('image'))
            $request->merge(['image_id' => MediaFileService::publicUpload($request->file('image'))->id]);
        $this->userRepository->update($user, $request);
        showFeedback();
        return redirect()->route('users.index');
    }

    public function destroy($user)
    {
        $user = $this->userRepository->findOrFailById($user);
        try {
            $this->userRepository->delete($user);
            return AjaxResponses::successResponse();
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function manualVerify($user)
    {
        $user = $this->userRepository->findOrFailById($user);
        try {
            $this->userRepository->manualVerify($user);
            return AjaxResponses::successResponse([
                'status' => trans('User::user.approved'),
                'class' => 'text-success',
            ]);
        } catch (\Throwable $e) {
            return AjaxResponses::errorResponse();
        }
    }

    public function photo(UserPhotoRequest $request)
    {
        try {
            auth()->user()->update([
                'image_id' => MediaFileService::publicUpload($request->file('photo'))->id,
            ]);
            showFeedback();
        } catch (\Throwable $e) {
            dd($e);
            showFeedback("عملیات ناموفق", "بروزرسانی اطلاعات با موفقیت انجام نشد", "error");
        }
        return redirect()->back();
    }

    public function profile()
    {
        return view('User::admin.profile');
    }

    public function updateProfile(ProfileRequest $request)
    {
        $this->userRepository->updateProfile($request);
        showFeedback();
        return redirect()->route('profile');
    }
}
