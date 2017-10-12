<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use DataTables;
use Request;
use App\User;

/**
 * Class UserTableController.
 */
class UserTableController extends Controller
{
    /**
     * @var UserRepository
     */

    /**
     * @param UserRepository $users
     */

    /**
     * @param ManageUserRequest $request
     *
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        $user = User::all();
         return Datatables::of($user)
        // ->editColumn(
        //     'confirmed', function ($user) {
        //         return $user->confirmed_label;
        //     }
        // )
            ->addColumn(
                'actions', function ($user) {
                    return '<a class="btn btn-primary" href="user/'.$user->id.'/edit">Edit</a> <a class="btn btn-danger" href="/user/del/'.$user->id.'">Delete</a>  ';
                }
            )
            ->rawColumns(['actions']
            )
            ->make(true);
    }
}
