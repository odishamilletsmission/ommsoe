<?php

namespace App\Filters;

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LoginFilter implements FilterInterface
{
	/**
	 * Do whatever processing this filter needs to do.
	 * By default it should not return anything during
	 * normal execution. However, when an abnormal state
	 * is found, it should return an instance of
	 * CodeIgniter\HTTP\Response. If it does, script
	 * execution will end and that Response will be
	 * sent back to the client, allowing for error pages,
	 * redirects, etc.
	 *
	 * @param RequestInterface $request
	 * @param array|null       $arguments
	 *
	 * @return mixed
	 */
	public function before(RequestInterface $request, $arguments = null)
	{
	    helper('aio');
		
        //$session = session();
        $user = service('user');
		$template = service('template');
		$uri = service('uri');
		//printr($user->getId());
        //$session->set('redirect',current_url());
		$route=uri_string();
		$ignore = array(
            env('app.adminDIR').'login',
            env('app.adminDIR').'logout',
            env('app.adminDIR').'forgotten',
            env('app.adminDIR').'reset',
            'error/not_found',
            'error/permission'
        );

        if(!$user->isLogged() && !in_array($route, $ignore)){
			echo view_cell('\Admin\Common\Controllers\Auth::login');
			exit;
            //return redirect()->to(base_url('login'));
        }
	}

	/**
	 * Allows After filters to inspect and modify the response
	 * object as needed. This method does not allow any way
	 * to stop execution of other after filters, short of
	 * throwing an Exception or Error.
	 *
	 * @param RequestInterface  $request
	 * @param ResponseInterface $response
	 * @param array|null        $arguments
	 *
	 * @return mixed
	 */
	public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
	{
		//
	}
}
