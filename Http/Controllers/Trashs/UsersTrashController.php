<?php
namespace LaccUser\Http\Controllers\Trashs;

use Illuminate\Http\Request;
use LaccBook\Http\Controllers\Controller;
use LaccUser\Repositories\UserRepository;
use LaccUser\Services\UserService;

class UsersTrashController extends Controller
{
		/**
		 * @var UserService
		 */
		protected $userService;

		/**
		 * @var UserRepository
		 */
		protected $userRepository;

		protected $urlDefault = 'users.index';

		public function __construct( UserService $userService, UserRepository $userRepository )
		{
				$this->userService    = $userService;
				$this->userRepository = $userRepository;
		}

		/**
		 * @param Request $request
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function index( Request $request )
		{
				$search = $request->get( 'search' );
				$this->userRepository->onlyTrashed();
				$users = $this->userRepository->paginate( 15 );
				
				return view( 'laccuser::trashs.index', compact( 'users', 'search' ) );
		}

		/**
		 * @param Request $request
		 * @param         $id
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function update( Request $request, $id )
		{
				$data = $request->all();
				
				$this->userRepository->onlyTrashed();
				$this->userRepository->restore( $id );
				$urlTo = $this->userService->checksTheCurrentUrl( $data[ 'redirect_to' ], $this->urlDefault );
				$request->session()->flash( 'message', [ 'type' => 'success', 'msg' => "User successfully restored!" ] );

				return redirect()->to( $urlTo );
		}
}
