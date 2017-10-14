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
        $user = User::where('role',null)->get();
         return Datatables::of($user)
        // ->editColumn(
        //     'confirmed', function ($user) {
        //         return $user->confirmed_label;
        //     }
        // )
            ->addColumn(
                'actions', function ($user) {
                    return '<a class="btn btn-primary" href="user/'.$user->id.'/edit">Edit</a> <a class="btn btn-danger" href="user/del/'.$user->id.'">Delete</a>  ';
                }
            )
            ->addColumn(
                'status', function ($user) {
                    return '<a class="btn btn-primary" href="changestatus/'.$user->id.'">'.$user->status.'</a>';
                }
            )
            ->rawColumns(['actions','status']
            )
            ->make(true);
    }
}
