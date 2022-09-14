<?php

namespace Jokoli\User\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Jokoli\Permission\Enums\Permissions;
use Jokoli\User\Models\User;

class UserRepository
{

    private function query()
    {
        return User::query();
    }

    public function paginate()
    {
        return $this->query()
            ->with('roles')
            ->paginate();
    }

    public function getCourses($user)
    {
        return $user->courses()
            ->with('teacher')
            ->duration()
            ->accepted()
            ->accepted()
            ->paginate(8);
    }

    public function findOrFailByUsername($username)
    {
        return $this->query()
            ->withCount(['accepted_courses As courses_count', 'students'])
            ->where('username', $username)->firstOrFail();
    }

    public function update($user, $request)
    {
        $user->syncRoles($request->role);
        return $user->update([
            'image_id' => $request->filled('image_id') ? $request->image_id : $user->image_id,
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'username' => $request->username,
            'headline' => $request->headline,
            'telegram' => $request->telegram,
            'password' => $request->filled('password') ? $request->password : $user->password,
            'status' => $request->status,
            'bio' => $request->bio,
        ]);
    }

    public function updateProfile($request)
    {
        return auth()->user()->update(array_filter($request->validated()));
    }

    public function delete($user)
    {
        return $user->delete();
    }

    public function findByEmail(string $email)
    {
        return $this->query()->where('email', $email)->first();
    }

    public function findOrFailById($id, $with = [])
    {
        return $this->query()->when(count($with), function (Builder $builder) use ($with) {
            $builder->with($with);
        })->findOrFail($id);
    }

    public function changePassword($user, $password)
    {
        $user->update(['password' => Hash::make($password)]);
    }

    public function getTeachers()
    {
        return $this->query()->permission(Permissions::Teach)->get();
    }

    public function hasPermission($id, $permission)
    {
        return $this->query()->where('id', $id)->permission($permission)->exists();
    }

    public function manualVerify($user)
    {
        $user->markEmailAsVerified();
    }
}
