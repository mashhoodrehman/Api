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
                    return '<button class="btn btn-primary"><a href="user/'.$user->id.'/edit">Heloo</a></button>  <button class="btn btn-danger"><a href="/user/del/'.$user->id.'">Heloo</a></button>  ';
                }
            )
            ->rawColumns(['actions']
            )
            ->make(true);
    }
}
