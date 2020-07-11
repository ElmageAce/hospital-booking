<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(User $user)
    {
        $pageTitle = "Profile - {$user->name}";
        return view('profile.show', compact('user', 'pageTitle'));
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        $pageTitle = "Edit profile";
        return view('profile.edit', compact('user', 'pageTitle'));
    }

    /**
     * @param UpdateUserProfileRequest $request
     * @param User $user
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(UpdateUserProfileRequest $request, User $user): JsonResponse
    {
        // Authorize user to update profile
        $this->authorize('update-profile', $request->input('id'));
        // Update user data
        $status = $user->updateUserData($request);

        return $status ?
            response()->json([
                'status' => 'success',
                'message' => 'User profile updated successfully!',
                'data' => $user
            ], 200)
            :
            response()->json([
                'status' => 'error',
                'message' => 'Error updating user profile'
            ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * @param User $user
     * @return JsonResponse
     */
    public function getUserData(User $user): JsonResponse
    {
        $user->info;
        return response()->json([
            'status' => 'success',
            'data' => new UserResource($user)
        ]);
    }
}
